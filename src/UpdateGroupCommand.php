<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'update-group',
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
            ->addArgument('users', InputArgument::IS_ARRAY, 'Input users IDs:')
        ;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->put($this->apiVersion.'/groups/'.$input->getArgument('id'), [
                'headers' => $this->requestHeaders(),
                'json' => [
                    'name' => $input->getArgument('name'),
                    'users' => $input->getArgument('users'),
                ]
            ]);
        } catch (RequestException $e) {
            $data = $this->getResponseContent($e->getResponse());
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $data['error'] ?? '']);
            return Command::INVALID;
        }

        $data = $this->getResponseContent($response);

        $output->writeln([
            self::SUCCESSFUL_OP, $this->prettyPrint($data)
        ]);

        return Command::SUCCESS;
    }
}