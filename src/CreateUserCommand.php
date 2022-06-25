<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'create-user',
    description: 'Create an user via API',
    aliases: ['api:create-user']
)]
final class CreateUserCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Input user name:')
            ->addArgument('email', InputArgument::REQUIRED, 'Input user email:')
            ->addArgument('groups', InputArgument::IS_ARRAY, 'Input groups IDs:')
        ;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->post($this->apiVersion.'/users', [
                'headers' => $this->requestHeaders(),
                'json' => [
                    'name' => $input->getArgument('name'),
                    'email' => $input->getArgument('email'),
                    'groups' => $input->getArgument('groups'),
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