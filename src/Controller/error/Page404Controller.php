<?php

namespace App\Controller\error;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Page404Controller extends AbstractController
{
    #[Route('/page404', name: 'app_page404')]
    public function index(): Response
    {
        return $this->render('exception/page404/index.html.twig', [
            'controller_name' => 'Page404Controller',
        ]);
    }
}
