<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'api:update-group',
    description: 'Update a group using API',
    aliases: ['api:update-group']
)]
class UpdateGroupCommand extends BaseCommand
{
    protected function configure(): void
    {
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
        try {
            $response = $this->apiClient->put($this->apiVersion.'/groups/'.$input->getArgument('id'), [
                'json' => [
                    'name' => $input->getArgument('name')
                ]
            ]);
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $data['error'] ?? '']);
            return Command::INVALID;
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $output->writeln([
            'Group has been updated!',
            '=======================',
            'Group ID: '.$data['id'],
            'Group name: '.$data['name']
        ]);

        return Command::SUCCESS;
    }
}