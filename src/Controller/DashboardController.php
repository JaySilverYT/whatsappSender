<?php

namespace App\Controller;

use App\Entity\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request): Response
    {
        $token = new Token();

        if ($request->getMethod() == "POST")
        {
            $tokenID = $request->get('tokenID');
            $instanceID = $request->get('instanceID');

            $session = $request->getSession(); //Guardamos las variables en sesion
            $session->set('token', $tokenID);
            $session->set('instance', $instanceID);

            $em = $this->getDoctrine()->getManager(); //Las guardamos en la base de datos (Para checkearlas cuando tengamos lo de la api de e-blaster.
            $token->setTokenID($tokenID);
            $token->setInstanceID($instanceID);
            $em->persist($token);
            $em->flush();
        }

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
