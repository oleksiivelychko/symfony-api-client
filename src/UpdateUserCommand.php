<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'update-user',
    description: 'Update an user using API',
    aliases: ['api:update-user']
)]
class UpdateUserCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Input user ID:')
            ->addArgument('name', InputArgument::REQUIRED, 'Input user name:')
            ->addArgument('groups', InputArgument::IS_ARRAY, 'Input groups IDs:')
        ;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->put($this->apiVersion.'/users/'.$input->getArgument('id'), [
                'headers' => $this->requestHeaders(),
                'json' => [
                    'name' => $input->getArgument('name'),
                    'groups' => $input->getArgument('groups'),
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