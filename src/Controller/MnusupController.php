<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MnusupController extends AbstractController
{
    /**
     * @Route("/mnusup", name="mnusup")
     */
    public function index(): Response
    {
        return $this->render('mnusup/index.html.twig', [
            'controller_name' => 'MnusupController',
        ]);
    }
}
