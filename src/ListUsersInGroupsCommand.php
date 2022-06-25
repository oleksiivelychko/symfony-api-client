<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'list-group',
    description: 'List of users of each group using API',
    aliases: ['api:list-group']
)]
class ListUsersInGroupsCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::OPTIONAL, 'Input group ID:');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->get($this->apiVersion.'/groups/'.$input->getArgument('id') ?: '', [
                'headers' => $this->requestHeaders(),
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