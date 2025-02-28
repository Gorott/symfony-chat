<?php

declare(strict_types=1);

namespace App\Controller\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     */
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('authentication/login.html.twig', [
            'error' => null,
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }
}
