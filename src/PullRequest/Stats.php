<?php

declare(strict_types=1);

namespace App\PullRequest;

use App\ValueObject\ApiRate;
use App\ValueObject\Users;
use Github\Client;
use Github\ResultPager;
use Github\ResultPagerInterface;
use Symfony\Component\HttpClient\HttpClient;

class Stats
{
    /** @var HttpClient */
    private $client;

    /** @var string */
    private $organisation;

    private CONST PULL_REQUESTS = '/repos/%s/%s/pulls';

    public function __construct(string $organisation, Client $client)
    {
        $this->client = $client;
        $this->organisation = $organisation;
    }

    public function getAllPullRequests(string $repository, ApiRate $rate): array
    {
        $pullRequestApi = $this->client->api('pull_request');
        $paginator  = new ResultPager($this->client);
        $parameters = array($this->organisation, $repository, array('state' => 'all'));

        $rate->increment();
        $firstPage = $paginator->fetch($pullRequestApi, 'all', $parameters);
        return $result = $this->paginateRequest($paginator, $firstPage, $rate);
    }

    public function generateUsersReviewCount(array $pulls, string $repository, ApiRate $rate, \DateTimeImmutable $maxAge): Users
    {
        $users = new Users();

        foreach ($pulls as $pull) {
            if ($this->isPullOlderThen($pull, $maxAge)){
                continue;
            }

            $rate->increment();
            $reviews = $this->client->api('pull_request')->reviews()->all($this->organisation, $repository, $pull['number']); //defaults to max 30 reviews
            $users->addReviews($reviews);
        }

        return $users;
    }

    private function paginateRequest(ResultPagerInterface $paginator, array $result, ApiRate $rate): array
    {
        if (!$paginator->hasNext()) {
            return $result;
        }

        $rate->increment();
        return $this->paginateRequest($paginator, \array_merge($result, $paginator->fetchNext()), $rate);
    }

    private function isPullOlderThen(array $pull, \DateTimeImmutable $date)
    {
        $pullRequestDate = new \DateTimeImmutable();

        if (isset($pull['created_at'] )) {
            $pullRequestDate = new \DateTimeImmutable($pull['created_at']);
        }

        if (isset($pull['updated_at'] )) {
            $pullRequestDate = new \DateTimeImmutable($pull['updated_at']);
        }

        return $date > $pullRequestDate;
    }
}
