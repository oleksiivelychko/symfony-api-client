<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class UpdateGroupCommand extends BaseCommand
{
    protected static $defaultName = 'api:update-group';

    protected function configure(): void
    {
        $this
            ->setDescription('Update an group via API')
            ->setHelp('This command allows you to edit a group...')
        ;

        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Input group ID:')
            ->addArgument('name', InputArgument::REQUIRED, 'Input group name:')
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

        try {
            $response = $this->apiClient->put($this->apiVersion.'/groups/'.$input->getArgument('id'), [
                'json' => [
                    'name' => $input->getArgument('name')
                ]
            ]);
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '']);
            return Command::INVALID;
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $output->writeln([
            'Group has been updated!',
            '=======================',
            'GroupID: '.$data['id'],
            'Group name: '.$data['name']
        ]);

        return Command::SUCCESS;
    }
}