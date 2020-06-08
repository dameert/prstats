<?php

declare(strict_types=1);

namespace App\Command;

use App\ValueObject\ApiRate;
use App\PullRequest\Statistics;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PullRequestCommand extends Command implements CommandInterface
{
    /** @var Statistics */
    private $statistics;
    /** @var SymfonyStyle */
    private $style;

    public function __construct(Statistics $statistics)
    {
        parent::__construct();
        $this->statistics = $statistics;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get Github pull request statistics')
            ->addArgument('repositories', InputArgument::IS_ARRAY, 'name of your repository')
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
        $this->style->title('Pull Requests Statistics');

        $repositories = $input->getArgument('repositories');
        $maxAge = new \DateTimeImmutable($input->getOption('max-age'));

        foreach ($repositories as $repository) {
            $this->style->comment(sprintf('Statistics for %s', $repository));

            $rate = new ApiRate();
            $prData = $this->statistics->getPullRequestData($repository, $maxAge, $rate);

            $this->style->note(sprintf('Performed %d api calls', $rate->getRate()));
            $this->style->table($this->statistics->getTableHeader(), $prData->toArray());
        }

        return Command::SUCCESS;
    }


}
