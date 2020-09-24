<?php

declare(strict_types=1);

namespace App\Github;

use Github\Client;

final class Rate
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRemaining(): int
    {
        return $this->client->api('rate_limit')->getResource('core')->getRemaining();
    }
}
