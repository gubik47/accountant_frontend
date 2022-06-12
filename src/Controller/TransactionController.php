<?php

namespace App\Controller;

use App\Service\Api\Resource\TransactionResource;
use App\Type\TransactionType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends BaseController
{
    #[Route("/ajax/load-transactions", name: "load_transactions", methods: ["GET"])]
    public function loadTransactions(Request $request): Response
    {
        if (!$this->isUserSelected()) {
            return $this->json(["status" => "error" , "message" => "User not logged"]);
        }

        $transactionResponse = $this->httpClient->request("GET", "http://api.accountant.local/api/transactions?account=$id");

        $transactions = json_decode($transactionResponse->getContent(), true)["transactions"];

        return $this->render("accounts/detail.html.twig", [
            "account" => $account,
            "transactions" => $transactions
        ]);
    }

    #[Route("/account/{id}/upload-transactions", name: "transactions_upload", methods: ["GET", "POST"])]
    public function uploadTransactions(int $id, Request $request, TransactionResource $resource): Response
    {
        if (!$this->isUserSelected()) {
            return $this->redirectToRoute("user_list");
        }

        $form = $this->createForm(TransactionType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get("file")->getData();

            $apiResponse = $resource->uploadTransactions([
                "accountId" => $id,
                "file" => base64_encode($file->getContent())
            ]);

            $response = $apiResponse->upload;

            if ($response["status"] === "success") {
                $this->addFlash("success", $response["message"]);

                return $this->redirectToRoute("account_detail", ["id" => $id]);
            }

            $this->addFlash("error", $response["message"]);
        }

        return $this->render("transactions/form.html.twig", [
            "form" => $form->createView()
        ]);
    }
}