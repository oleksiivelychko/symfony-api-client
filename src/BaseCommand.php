<?php

namespace Console;

use GuzzleHttp\Client;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    const SUCCESSFUL_OP = "👌\033[1mOperation has been successful!\n\033[0m\033[32m";

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
            'timeout'  => 1000.0, // in seconds
        ]);

        $this->apiVersion = $_ENV['API_VERSION'] ? '/api-v'.$_ENV['API_VERSION'] : '/api';
    }

    #[ArrayShape(['Content-Type' => "string", 'Accept' => "string"])]
    final public function requestHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    final public function getResponseContent(ResponseInterface $response): ?array
    {
        return json_decode($response->getBody()?->getContents(), true);
    }

    final public function prettyPrint($arr, int $i=0): string
    {
        $retStr = '';

        foreach ($arr as $key => $val) {
            $retStr .= str_repeat("\t", $i);
            $key = '・' . $key . (is_numeric($key) ? '' : "\t") . " ➤ \t";

            if (is_array($val)) {
                $i++;
                $retStr .= $key . "\n" . $this->prettyPrint($val, $i);
                $i--;
            } else {
                $retStr .= $key . $val . "\n";
            }
        }

        return $retStr;
    }
}