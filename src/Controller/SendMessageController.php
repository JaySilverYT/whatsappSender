<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\User;
use IsoCodes\PhoneNumber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendMessageController extends AbstractController
{
    /**
     * @Route("/send-message", name="send-message")
     */
    public function index(Request $request): Response
    {
        $errors = false;
        $errors_message = "";
        $log = new Log();

        if ($request->getMethod() == "POST")
        {
            $tokenID = $request->get('token');
            $instaceID = $request->get('instance');
            $phone = $request->get('phone');
            $message = $request->get('message');

            //VALIDACION TOKEN
            if ($tokenID != $request->getSession()->get('token'))
            {
                $errors = true;
                $errors_message .= "El token introducido no es correcto, ";
            }

            //VALIDACION INSTANCIA
            if ($instaceID != $request->getSession()->get('instance'))
            {
                $errors = true;
                $errors_message .= "La instancia introducida no es correcta, ";
            }

            //VALIDACION PHONE
            if (!(PhoneNumber::validate($phone)))
            {
                $errors = true;
                $errors_message .= "Telefono no valido, ";
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
            else
            {

                $JSON = 'https://e-blaster.com/api/send.php?number='.substr($phone, 1).'&type=text&message='.$message.'&instance_id='.$instaceID.'&access_token='.$tokenID.'';

                $em = $this->getDoctrine()->getManager();
                $query = $em->getRepository(User::class)->findBy(array('phone' => substr($phone, 1)));

                if (empty($query))
                {
                    return new JsonResponse(
                        [
                            'Errors' => 'El Usuario introducido no existe en la base de datos'
                        ]
                    );
                }
                else
                {
                    $log->setDate(new \DateTime());
                    $log->setMessage($message);
                    $log->setTokenUserEmisor($tokenID);
                    $log->setUserReceptor($query[0]->getId());

                    $em->persist($log);
                    $em->flush();

                    return $this->redirect($JSON);
                }
            }
        }

        return $this->render('send_message/index.html.twig', [
            'controller_name' => 'SendMessageController',
        ]);
    }
}
