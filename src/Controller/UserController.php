<?php

namespace App\Controller;

use App\Model\User;
use App\Service\Api\Resource\UserResource;
use App\Service\Factory\PageFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    #[Route("/", name: "user_list", methods: ["GET"])]
    public function userList(PageFactory $factory): Response
    {
        if ($this->isUserSelected()) {
            return $this->redirectToRoute("account_list");
        }

        $content = $factory->createUsersPageContent();

        return $this->render("login.html.twig", [
            "content" => $content
        ]);
    }

    #[Route("/select-user", name: "login", methods: ["POST"])]
    public function login(Request $request, UserResource $resource): Response
    {
        $userId = $request->request->get("user");

        try {
            $apiResponse = $resource->getUser(intval($userId));
        } catch (NotFoundHttpException) {
            $this->addFlash("error", "This user does not exist.");

            return $this->redirectToRoute("user_list");
        }

        $this->session->set("user", new User($apiResponse->user));

        return $this->redirectToRoute("account_list");
    }

    #[Route("/logout", name: "logout", methods: ["GET"])]
    public function logout(): Response
    {
        $this->session->remove("user");

        return $this->redirectToRoute("user_list");
    }
}