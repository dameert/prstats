<?php

declare(strict_types=1);

namespace App\Counts;

class ApiRate
{
    /** @var int */
    private $rate;

    public function __construct()
    {
        $this->rate = 0;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function increment(): void
    {
        $this->rate++;

    }
}
