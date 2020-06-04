<?php

declare(strict_types=1);

namespace App\Github;

use Github\Client;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ClientFactory
{
    /** @var Client */
    private $client;

    public function __construct(string $authToken)
    {
        $this->client = new Client();

        if ($this->client instanceof LoggerAwareInterface) {
            $this->client->setLogger(new ConsoleLogger);
        }

        $this->client->authenticate($authToken, Client::AUTH_HTTP_TOKEN);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
