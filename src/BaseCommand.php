<?php

namespace Console;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    protected Client $apiClient;
    protected string $apiVersion;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->apiClient = new Client([
            'headers' => [
                'accept' => 'application/json'
            ],
            'base_uri' => $_ENV['API_URL'] ?? 'http://localhost:8000',
            'timeout'  => 2.0,
        ]);

        $this->apiVersion = $_ENV['API_VERSION'] ? '/api-v'.$_ENV['API_VERSION'] : '/api';
    }

    protected function getGroupsIds(string $groupsIds): array
    {
        return array_map(function (int $id) {
            if (!$_ENV['API_VERSION']) {
                return "/api/groups/$id";
            } else {
                return $id;
            }
        }, explode(',', $groupsIds));
    }
}