<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'delete-group',
    description: 'Delete a group using API',
    aliases: ['api:delete-group']
)]
class DeleteGroupCommand extends BaseCommand
{
    protected function configure(): void
    {
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
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $data['error'] ?? '']);
            return Command::INVALID;
        }

        $output->writeln([
            'Group has been removed!',
            '=======================',
        ]);

        return Command::SUCCESS;
    }
}