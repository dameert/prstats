<?php

declare(strict_types=1);

namespace App\ValueObject;

class PullRequestData
{
    /** @var array */
    private $users;

    public function __construct()
    {
        $this->users = [];
    }

    public function addReviews(array $reviews)
    {
        foreach($reviews as $review){
            $this->users[$review['user']['login']][] = new Review($review);
        }
    }

    public function toArray(): array
    {
        return
            \array_map(
                function($username, $reviews) {
                    return [$username, \count($reviews)];
                },
                \array_keys($this->users),
                \array_values($this->users)
            );
    }
}
