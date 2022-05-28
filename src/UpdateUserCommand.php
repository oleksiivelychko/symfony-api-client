<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class UpdateUserCommand extends BaseCommand
{
    protected static $defaultName = 'api:update-user';

    protected function configure(): void
    {
        $this
            ->setDescription('Update an user via API')
            ->setHelp('This command allows you to edit a user...')
        ;

        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Input user ID:')
            ->addArgument('name', InputArgument::REQUIRED, 'Input user name:')
            ->addArgument('groups', InputArgument::OPTIONAL, 'Input groups IDs:')
        ;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'WARNING! API SERVER MUST BE RUNNING!',
            '====================================',
        ]);

        $json = [
            'name' => $input->getArgument('name'),
            'groups' => []
        ];

        if ($input->getArgument('groups')) {
            $json['groups'] = $this->getGroupsIds($input->getArgument('groups'));
        }

        try {
            $response = $this->apiClient->put($this->apiVersion.'/users/'.$input->getArgument('id'), [
                'json' => $json
            ]);
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $e->getMessage()]);
            return Command::INVALID;
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $output->writeln([
            'User has been updated!',
            '======================',
            'User ID: '.$data['id'],
            'User name: '.$data['name']
        ]);

        return Command::SUCCESS;
    }
}