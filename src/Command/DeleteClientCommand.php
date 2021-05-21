<?php
namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Input\InputArgument;

class DeleteClientCommand extends BaseCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('client:delete')
            ->setDescription('Delete a client')
            ->addArgument('id', InputArgument::REQUIRED, "Client's id")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $httpClient = HttpClient::create();
        $url = $input->getOption('apiUrl');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $id = $this->parseInt($input->getArgument('id'));
        $response = $httpClient->request('DELETE', $url.'/api/clients/'.$id, ['auth_basic' => [$username, $password],]);
        return $this->returnCode($response, $output);
    }
}
