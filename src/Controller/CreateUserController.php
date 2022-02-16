<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use IsoCodes\PhoneNumber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{
    /**
     * @Route("/create-user", name="create-user")
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $errors = false;
        $errors_message = "";

        if ($request->getMethod() == 'POST') {

            //Cogemos las variables por POST (Formulario)

            $name = $request->request->get('firstname');
            $surname1 = $request->request->get('surname1');
            $surname2 = $request->request->get('surname2');
            $birthday = $request->request->get('birthday');
            $mail = $request->request->get('mail');
            $phone = $request->request->get('phone');

            //VALIDACION NAME
            if (preg_match('~[0-9]+~', $name) > 0 || strlen($name) < 1) {
                $errors = true;
                $errors_message .= "Nombre no valido, ";
            }

            //VALIDACION SURNAME1
            if (preg_match('~[0-9]+~', $surname1) > 0 || strlen($surname1) < 1) {
                $errors = true;
                $errors_message .= "Primer apellido no valido, ";
            }

            //VALIDACION SURNAME2
            if (preg_match('~[0-9]+~', $surname2) > 0 || strlen($surname2) < 1) {
                $errors = true;
                $errors_message .= "Segundo apellido no valido, ";
            }

            //VALIDACION EMAIL
            if (!(filter_var($mail, FILTER_VALIDATE_EMAIL))) {
                $errors = true;
                $errors_message .= "Email no valido, ";
            }

            //VALIDACION PHONE
            if (!(PhoneNumber::validate($phone))) {
                $errors = true;
                $errors_message .= "Telefono no valido, ";
            }

            //VALIDACION DATE
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
            } else {

                $em = $this->getDoctrine()->getManager();

                str_replace(' ', '', $name); //Quitamos los espacios en blanco en caso de que haya escrito "David " <- o -> " David"
                $user->setName($name);

                str_replace(' ', '', $surname1);
                $user->setSurname1($surname1);

                str_replace(' ', '', $surname2);
                $user->setSurname2($surname2);

                $user->setBirthday(new \DateTime($birthday));
                $user->setMail($mail);

                //substr para quitar el '+' y que la api de E-Blaster lo acepte en la url
                $user->setPhone(substr($phone, 1));

                $em->persist($user);
                $em->flush();
            }
        }

        return $this->render('create_user/index.html.twig', [
            'controller_name' => 'CreateUserController',

        ]);
    }


    //BIRTHDATE VALIDATION
    private function checkDate($date)
    {
        if (false === strtotime($date)) {
            return false;
        }
        list($year, $month, $day) = explode('-', $date);
        return checkdate($month, $day, $year);
    }
}
