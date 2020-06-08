<?php

declare(strict_types=1);

namespace App\Github;

use Github\Client;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ClientFactory
{
    public function __invoke(string $authToken): Client
    {
        $client = new Client();

        if ($client instanceof LoggerAwareInterface) {
            $client->setLogger(new ConsoleLogger);
        }

        if ($authToken !== '') {
            $client->authenticate($authToken, Client::AUTH_HTTP_TOKEN);
        }

        return $client;
    }
}
