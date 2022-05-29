<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListUsersInGroupsCommand extends BaseCommand
{
    protected static $defaultName = 'api:list-group';
    private const DELIMITER = '----------------------------------------';

    protected function configure(): void
    {
        $this
            ->setDescription('List of users of each group via API')
            ->setHelp('This command allows you to display users of the group.')
        ;

        $this->addArgument('id', InputArgument::OPTIONAL, 'Input group ID:');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->apiClient->get($this->apiVersion);
        } catch (RequestException $e) {
            $output->writeln('Something was happened with API server.😢');
            return Command::FAILURE;
        }

        try {
            $response = $this->apiClient->get($this->apiVersion.'/groups/' . $input->getArgument('id') ?: '');
        } catch (RequestException $e) {
            $data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $output->writeln([$data['title'] ?? '', $data['detail'] ?? '']);
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
        $formatted = [
            'Group ID: '.$item['id'],
            'Group name: '.$item['name']
        ];

        foreach ($item['data']['users'] as $user) {
            $formatted[] = 'User name is '.$user['name'].', email is '.$user['email'];
        }

        if ($delimiter) {
            $formatted[] = self::DELIMITER;
        }

        return $formatted;
    }
}