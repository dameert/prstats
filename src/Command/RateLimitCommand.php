<?php

declare(strict_types=1);

namespace App\Command;

use App\Github\ClientFactory;
use Github\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RateLimitCommand extends Command
{
    /** @var Client */
    private $client;
    /** @var SymfonyStyle */
    private $style;

    public function __construct(ClientFactory $clientFactory)
    {
        parent::__construct();
        $this->client = $clientFactory->getClient();
    }

    protected function configure()
    {
        $this
            ->setDescription('Get Remaining Github Rate');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
        $this->style = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->style->title('Github Rate Limit');
        $this->style->text(sprintf('%s API calls remaining', $this->client->api('rate_limit')->getResource('core')->getRemaining()));
        return 1;
    }



}
