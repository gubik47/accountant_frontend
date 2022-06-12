<?php

namespace App\Controller;

use App\Model\User;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class BaseController extends AbstractController
{
    protected SessionInterface $session;

    public function __construct(RequestStack $rs)
    {
        $this->session = $rs->getSession();
    }

    protected function isUserSelected(): bool
    {
        return $this->session->has("user");
    }

    protected function getSessionUser(): User
    {
        $user = $this->session->get("user");
        if (!$user instanceof User) {
            throw new RuntimeException("User not present in session");
        }

        return $this->session->get("user");
    }
}