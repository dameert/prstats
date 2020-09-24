<?php

declare(strict_types=1);

namespace App\ValueObject;

use Symfony\Component\Console\Style\SymfonyStyle;

final class CommandApiRate implements ApiRateInterface
{
    private SymfonyStyle $style;
    private ApiRate $rate;

    public function __construct(SymfonyStyle $style)
    {
        $this->style = $style;
        $this->rate = new ApiRate();
    }

    public function increment(): void
    {
        $this->rate->increment();
        $this->style->progressAdvance();
    }

    public function getRate(): int
    {
        return $this->rate->getRate();
    }
}