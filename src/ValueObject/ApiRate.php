<?php

declare(strict_types=1);

namespace App\ValueObject;

final class ApiRate implements ApiRateInterface
{
    private int $rate;

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
