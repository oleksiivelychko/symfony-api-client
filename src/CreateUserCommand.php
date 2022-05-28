<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

final class CreateUserCommand extends BaseCommand
{
    protected static $defaultName = 'api:create-user';

    protected function configure(): void
    {
        $this
            ->setDescription('Create a new user via API')
            ->setHelp('This command allows you to create a user...')
        ;

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Input user name:')
            ->addArgument('email', InputArgument::REQUIRED, 'Input user email:')
            ->addArgument('groups', InputArgument::OPTIONAL, 'Input groups IDs:')
        ;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $json = [
            'name' => $input->getArgument('name'),
            'email' => $input->getArgument('email'),
        ];

        if ($input->getArgument('groups')) {
            $json['groups'] = $this->getGroupsIds($input->getArgument('groups'));
        }

        try {
            $response = $this->apiClient->post($this->apiVersion.'/users', ['json' => $json]);
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $e->getMessage()]);
            return Command::INVALID;
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $output->writeln([
            'User has been created!',
            '======================',
            'User ID: '.$data['id'],
            'User name: '.$data['name'],
            'User email: '.$data['email'],
        ]);

        return Command::SUCCESS;
    }
}