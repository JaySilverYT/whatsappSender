<?php

namespace App\Controller;

use App\Entity\Token;
use App\Form\TokenFormType;
use Container4KVgOY9\getSession_FactoryService;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    /**
     * @Route("/", name="token")
     */
    public function index(): Response
    {

        return $this->render('token/index.html.twig', [
            'controller_name' => 'TokenController'
        ]);
    }
}
