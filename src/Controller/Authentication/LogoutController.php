<?php

declare(strict_types=1);

namespace App\Controller\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{

    /**
     * @Route("/logout")
     */
    public function __invoke(): Response
    {
        // This method will not be called, as the logout is handled by Symfony's security system.
    }
}
