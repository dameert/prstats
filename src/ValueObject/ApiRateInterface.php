<?php

declare(strict_types=1);

namespace App\ValueObject;

interface ApiRateInterface
{
    public function getRate(): int;
    public function increment(): void;
}