<?php

namespace App\Controller;

use App\Entity\User;
use http\Message;
use IsoCodes\Nif;
use IsoCodes\PhoneNumber;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class ApiCreateUserController extends AbstractController
{
    /**
     * @Route("/api/create-user", name="api_create-user")
     */
    public function index(Request $request, KernelInterface $kernel): Response
    {
            $user = new User();
            $errors = false;
            $errors_message = "";

            //TODO: Cogemos las variables por POST
            $name = $request->request->get('name');
            $surname1 = $request->request->get('surname');
            $surname2 = $request->request->get('surname1');
            $birthday = $request->request->get('birthday');
            $email = $request->request->get('mail');
            $phone = $request->request->get('phone');

            //TODO: VALIDACION NAME
            if (preg_match('~[0-9]+~', $name) > 0 || strlen($name) < 1 )
            {
                $errors = true;
                $errors_message .= "Nombre no valido, ";
            }

            //TODO: VALIDACION SURNAME1
            if (preg_match('~[0-9]+~', $surname1) > 0 || strlen($surname1) < 1 )
            {
                $errors = true;
                $errors_message .= "Primer apellido no valido, ";
            }

            //TODO: VALIDACION SURNAME2
            if (preg_match('~[0-9]+~', $surname2) > 0 || strlen($surname2) < 1 )
            {
                $errors = true;
                $errors_message .= "Segundo apellido no valido, ";
            }

            //TODO: VALIDACION EMAIL
            if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
                $errors = true;
                $errors_message .= "Email no valido, ";
            }

            //TODO: VALIDACION PHONE
            if (!(PhoneNumber::validate($phone))) {
                $errors = true;
                $errors_message .= "Telefono no valido, ";
            }

            //TODO: VALIDACION DATE
            if (!($this->checkDate($birthday))) {
                $errors = true;
                $errors_message .= "Fecha de nacimiento no valida, ";
            }

            if ($errors) {
                return new JsonResponse(
                    [
                        'Errors' => $errors_message
                    ]
                );
            }
            else
            {
                $em = $this->getDoctrine()->getManager();

                str_replace(' ', '', $name); //Quitamos los espacios en blanco en caso de que haya escrito "David " <- o -> " David"
                $user->setName($name);

                str_replace(' ', '', $surname1);
                $user->setSurname1($surname1);

                str_replace(' ', '', $surname2);
                $user->setSurname2($surname2);

                $user->setBirthday(new \DateTime($birthday));
                $user->setMail($email);
                $user->setPhone($phone);

                $em->persist($user);
                $em->flush();

                return new JsonResponse(
                    [
                        'Validate' => "Datos inseridos correctamente en la BBDD"
                    ]
                );

            }
    }

        //BIRTHDATE VALIDATION
        private function checkDate($date) {
        if (false === strtotime($date)) {
            return false;
        }
        list($year, $month, $day) = explode('-', $date);
        return checkdate($month, $day, $year);
    }

}
