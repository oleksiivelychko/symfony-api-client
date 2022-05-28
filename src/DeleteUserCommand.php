<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DeleteUserCommand extends BaseCommand
{
    protected static $defaultName = 'api:delete-user';

    protected function configure(): void
    {
        $this
            ->setDescription('Delete an user via API')
            ->setHelp('This command allows you to delete a user...')
        ;

        $this->addArgument('id', InputArgument::REQUIRED, 'Input user ID:');
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
            $this->apiClient->delete($this->apiVersion.'/users/'.$input->getArgument('id'));
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $e->getMessage()]);
            return Command::INVALID;
        }

        $output->writeln([
            'User has been removed!',
            '======================'
        ]);

        return Command::SUCCESS;
    }
}