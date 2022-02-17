<?php

namespace App\Controller;

use App\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogCSVController extends AbstractController
{
    /**
     * @Route("/export/csv/log", name="log")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Log::class);
        $logs = $repository->findAll();

        $rows = array();


        foreach ($logs as $log) {

            $data = array($log->getId(), $log->getDate()->format('Y-m-d'), $log->getTokenUserEmisor(), $log->getUserReceptor(), $log->getMessage());

            $rows[] = implode(',', $data);
        }

        $content = implode("\n", $rows);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }
}
