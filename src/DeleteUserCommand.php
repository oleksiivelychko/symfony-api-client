<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'delete-user',
    description: 'Delete an user using API',
    aliases: ['api:delete-user']
)]
class DeleteUserCommand extends BaseCommand
{
    protected static $defaultName = 'api:delete-user';

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Input user ID:');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->apiClient->delete($this->apiVersion.'/users/'.$input->getArgument('id'));
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $data['error'] ?? '']);
            return Command::INVALID;
        }

        $output->writeln([
            'User has been removed!',
            '======================'
        ]);

        return Command::SUCCESS;
    }
}