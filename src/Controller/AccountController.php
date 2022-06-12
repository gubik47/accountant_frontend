<?php

namespace App\Controller;

use App\Service\Factory\PageFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends BaseController
{
    #[Route("/accounts", name: "account_list", methods: ["GET"])]
    public function accountList(PageFactory $factory): Response
    {
        if (!$this->isUserSelected()) {
            return $this->redirectToRoute("user_list");
        }

        $content = $factory->createAccountsPageContent($this->getSessionUser()->getId());

        return $this->render("accounts/list.html.twig", [
            "content" => $content
        ]);
    }

    #[Route("/account/{id}", name: "account_detail", methods: ["GET"])]
    public function accountDetail(int $id, Request $request, PageFactory $factory): Response
    {
        if (!$this->isUserSelected()) {
            return $this->redirectToRoute("user_list");
        }

        $content = $factory->createAccountDetailPageContent($id, $request);

        if ($content->getAccount()->getUserId() !== $this->getSessionUser()->getId()) {
            // access to another user's accounts is forbidden
            throw new NotFoundHttpException();
        }

        return $this->render("accounts/detail.html.twig", [
            "content" => $content
        ]);
    }
}