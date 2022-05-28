<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DeleteGroupCommand extends BaseCommand
{
    protected static $defaultName = 'api:delete-group';

    protected function configure(): void
    {
        $this
            ->setDescription('Delete a group via API')
            ->setHelp('This command allows you to delete a group...')
        ;

        $this->addArgument('id', InputArgument::REQUIRED, 'Input group ID:');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->delete($this->apiVersion.'/groups/'.$input->getArgument('id'));
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '']);
            return Command::INVALID;
        }

        $output->writeln([
            'Group has been removed!',
            '=======================',
        ]);

        return Command::SUCCESS;
    }
}