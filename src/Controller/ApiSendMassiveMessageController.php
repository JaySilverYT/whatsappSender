<?php

namespace App\Controller;

use App\Entity\MassiveMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

// Importar las clases requeridas
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ApiSendMassiveMessageController extends AbstractController
{
    /**
     * @Route("/api/send-massive-message", name="api_send_massive_message")
     */
    public function index(Request $request, KernelInterface $kernel): Response
    {
        if ($request->getMethod()=="POST")
        {
            $errors = false;
            $errors_message ="";
            $massiveMessage = new MassiveMessage();

            $tokenID = $request->request->get('token');
            $instanceID = $request->request->get('instance');
            $message = $request->request->get('message');



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

                //TEST
                $application = new Application($kernel);
                $application->setAutoExit(false);

                $input = new ArrayInput([
                    'command' => 'app.massiveMessage'
                ]);

                $output = new BufferedOutput();
                $application->run($input, $output);

                $content = $output->fetch();
                return new Response($content);
                //HASTA AQUI EL TEST

            }
        }

        return $this->render('api_send_massive_message/index.html.twig', [
            'controller_name' => 'ApiSendMassiveMessageController',
        ]);
    }
}
