<?php

declare(strict_types=1);

namespace App\ValueObject;

class Review
{
    /** @var array  */
    private $review;

    public function __construct(array $review)
    {
        $this->review = $review;
    }
}
