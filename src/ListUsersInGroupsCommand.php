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
    private const DELIMITER = '----------------------------------------';

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
            $response = $this->apiClient->get($this->apiVersion.'/groups/' . $input->getArgument('id') ?: '');
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '', $data['error'] ?? '']);
            return Command::INVALID;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        $formatted =  [];
        if ($input->getArgument('id')) {
            $formatted = $this->formatItem($data);
        } else {
            foreach ($data as $group) {
                $formatted = array_merge($formatted, $this->formatItem($group, true));
            }
        }

        $output->writeln($formatted);
        $output->writeln(['Users in group has been displayed', self::DELIMITER]);

        return Command::SUCCESS;
    }

    private function formatItem(array $item, bool $delimiter=false): array
    {
        $item = $item[0] ?? $item;

        $formatted = [
            'Group ID: '.$item['id'],
            'Group name: '.$item['name']
        ];

        foreach ($item['data']['users'] ?? $item['users']  as $user) {
            $formatted[] = 'User name is '.$user['name'].', email is '.$user['email'];
        }

        if ($delimiter) {
            $formatted[] = self::DELIMITER;
        }

        return $formatted;
    }
}