<?php

namespace App\Controller\error;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Page500Controller extends AbstractController
{
    #[Route('/page500', name: 'app_page500')]
    public function index(): Response
    {
        return $this->render('exception/page500/index.html.twig', [
            'controller_name' => 'Page500Controller',
        ]);
    }
}
