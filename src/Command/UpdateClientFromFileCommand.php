<?php
namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class UpdateClientFromFileCommand extends BaseCommand
{
    
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('client:update:file')
            ->setDescription('Update client from json file')
            ->addArgument('id', InputArgument::REQUIRED, "Id of the client to update")
            ->addArgument('inputFile', InputArgument::REQUIRED, "Json file with data to replace")
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, "Output file to save client")
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $httpClient = HttpClient::create();
        $url = $input->getOption('apiUrl');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        try {
            $id = $this->parseInt($input->getArgument('id'));
        } catch (\InvalidArgumentException $e) {
            $output->writeln("Id of the client must be a integer");
            return self::INVALID_ARGUMENT;
        }
        $inputFilename = $input->getArgument('inputFile');
        $inputFile = fopen($inputFilename, 'r');
        $json = fread($inputFile, filesize($inputFilename));
        fclose($inputFile);
        $response = $httpClient->request('PUT', $url.'/api/clients/'.$id, [
            'auth_basic' => [
                $username,
                $password
            ],
            'json' => json_decode($json, true)
        ]);
        return $this->returnCode($response, $output);
    }
}
