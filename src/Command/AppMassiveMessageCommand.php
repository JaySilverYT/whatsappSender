<?php

namespace App\Command;

use App\Entity\Log;
use App\Entity\MassiveMessage;
use App\Entity\User;
use App\Repository\LogRepository;
use App\Repository\MassiveMessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppMassiveMessageCommand extends Command
{
    protected static $defaultName = 'app.massiveMessage';

    public function __construct(UserRepository $userRepository, MassiveMessageRepository $massiveMessageRepository, LogRepository $logRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->massiveMessageRepository = $massiveMessageRepository;
        $this->LogRepository = $logRepository;
        $this->entityManager = $em;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Enviar correos a usuarios')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findAll(); //Coge todos los registros de la entidad UserRepository
        $massiveMessages = $this->massiveMessageRepository->findAll();
        $em = $this->entityManager;

        $io->progressStart(count($massiveMessages)); //Hace un recuento de los procesos que tiene que hacer (Visual en consola)

        foreach ($massiveMessages as $massiveMessage) {
            foreach ($users as $user) {

                $message = $this->buildURL($user, $massiveMessage);
                $client = new Client();

                try {
                    $result = json_decode(
                        $client->request(
                            "POST",
                            $message //ENDPOINT
                        )
                            ->getBody()
                            ->getContents(),
                        true
                    );

                } catch (GuzzleException $e) {
                    dump($e);
                    die();
                }

                $log = new Log();
                $log->setMessage($massiveMessage->getMessage());
                $log->setDate(new \DateTime());
                $log->setUserReceptor($user->getId());
                $log->setTokenUserEmisor($massiveMessage->getToken());
                $em->persist($log);
                $em->flush();

            }

            $em->remove($massiveMessage);
            $em->flush();

            $io->progressAdvance();

        }

        $io->progressFinish();
        $io->success('Mensajes enviados correctamente!');

        return 1; //Se retorna 1 para que no pete
    }

    private function buildURL(
        User $xUser,
        MassiveMessage $xMassiveMessage
    ) : string {
        return sprintf(
            'https://e-blaster.com/api/send.php?number=%s&type=text&message=%s&instance_id=%s&access_token=%s',
            $xUser->getPhone(),
            $xMassiveMessage->getMessage(),
            $xMassiveMessage->getInstance(),
            $xMassiveMessage->getToken()
        );
    }
}
