<?php

declare(strict_types=1);

namespace App\Command;

use App\ValueObject\ApiRate;
use App\PullRequest\Stats;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PullRequestCommand extends Command
{
    /** @var Stats */
    private $stats;
    /** @var SymfonyStyle */
    private $style;

    public function __construct(Stats $stats)
    {
        parent::__construct();
        $this->stats = $stats;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get Github pull request statistics')
            ->addArgument('repository', InputArgument::REQUIRED, 'name of your repository')
            ->addOption('max-age', null, InputOption::VALUE_OPTIONAL, 'max age of the pull request latest update', 'last month')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
        $this->style = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->style->title('Fetching list of Pull Requests');
        $repository = $input->getArgument('repository');
        $maxAge = $input->getOption('max-age');

        $rate = new ApiRate();

        try {
            $pulls = $this->stats->getAllPullRequests($repository, $rate);
            $reviewCount = $this->stats->generateUsersReviewCount($pulls, $repository, $rate, new \DateTimeImmutable($maxAge));
        } catch (\Exception $e) {
            $this->style->error(sprintf('Github rate limit exceeded, performed %d api calls.', $rate->getRate()));
            throw $e;
        }

        $this->style->note(sprintf('Performed %d api calls', $rate->getRate()));
        $this->style->table(['User', 'Number of reviews'], $reviewCount->toArray());

        return 1;
    }


}