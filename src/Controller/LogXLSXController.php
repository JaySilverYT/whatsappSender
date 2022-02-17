<?php

namespace App\Controller;

use App\Entity\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Yectep\PhpSpreadsheetBundle\Factory;
use Symfony\Component\Routing\Annotation\Route;


class LogXLSXController extends AbstractController
{
    /**
     * @Route("/export/xlsx/log", name="log-xlsx")
     */
    public function index(Factory $factory): Response
    {
        $em = $this->getDoctrine()->getManager();
        $logRepository = $em->getRepository(Log::class);
        $logs = $logRepository->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($logs as $log) {
            $dataLog[] = array($log->getId(), $log->getDate()->format('Y-m-d'), $log->getTokenUserEmisor(), $log->getUserReceptor(), $log->getMessage());


        }


        $sheet->setCellValue('A1', 'Hello World !');
        $sheet->setTitle("My First Worksheet");

        // Crear tu archivo Office 2007 Excel (XLSX Formato)
        $writer = new Xlsx($spreadsheet);

        // Crear archivo temporal en el sistema
        $fileName = 'log.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $sheet->setTitle('User List');

        $sheet->getCell('A1')->setValue('Id');
        $sheet->getCell('B1')->setValue('Date');
        $sheet->getCell('C1')->setValue('Token');
        $sheet->getCell('D1')->setValue('User Receptor');
        $sheet->getCell('E1')->setValue('Message');



        $sheet->fromArray($dataLog,null, 'A2', true);

        // Increase row cursor after header write


        // Guardar el archivo de excel en el directorio temporal del sistema
        $writer->save($temp_file);

        // Retornar excel como descarga
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
