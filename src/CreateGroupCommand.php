<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'create-group',
    description: 'Create a group using API',
    aliases: ['api:create-group']
)]
class CreateGroupCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
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
            $response = $this->apiClient->post($this->apiVersion.'/groups', [
                'headers' => $this->requestHeaders(),
                'json' => [
                    'name' => $input->getArgument('name'),
                    'users' => $input->getArgument('users')
                ],
            ]);
        } catch (RequestException $e) {
            $output->writeln([$this->getResponseError($e->getResponse())]);
            return Command::INVALID;
        }

        $data = $this->getResponseContent($response);

        $output->writeln([
            self::SUCCESSFUL_OP, $this->prettyPrint($data)
        ]);

        return Command::SUCCESS;
    }


}