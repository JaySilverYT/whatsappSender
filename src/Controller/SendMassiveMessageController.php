<?php

namespace App\Controller;

use App\Entity\MassiveMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendMassiveMessageController extends AbstractController
{
    /**
     * @Route("/send-massive-message", name="send-massive-message")
     */
    public function index(Request $request): Response
    {
            if ($request->getMethod()=="POST")
            {
                $errors = false;
                $errors_message ="";
                $massiveMessage = new MassiveMessage();

                $tokenID = $request->request->get('token');
                $instanceID = $request->request->get('instance');
                $message = $request->request->get('message');

                //VALIDACION TOKEN
                if ($tokenID != $request->getSession()->get('token'))
                {
                    $errors = true;
                    $errors_message .= "El token introducido no es correcto, ";
                }

                //VALIDACION INSTANCIA
                if ($instanceID != $request->getSession()->get('instance'))
                {
                    $errors = true;
                    $errors_message .= "La instancia introducida no es correcta, ";
                }

                //VALIDACION MESSAGE
                if (strlen($message) > 10000)
                {
                    $errors = true;
                    $errors_message .= "El mensaje introducido supera el limite permitido, ";
                }

                if ($errors == true)
                {
                    return new JsonResponse(
                        [
                            'Errors' => $errors_message
                        ]
                    );
                }
                else {

                    $em = $this->getDoctrine()->getManager();

                    $massiveMessage->setInstance($instanceID);
                    $massiveMessage->setToken($tokenID);
                    $massiveMessage->setMessage($message);
                    $massiveMessage->setDate(new \DateTime());

                    $em->persist($massiveMessage);
                    $em->flush();

                }
            }

        return $this->render('send_massive_message/index.html.twig', [
            'controller_name' => 'SendMassiveMessageController',
        ]);
    }
}
