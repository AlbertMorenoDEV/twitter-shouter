<?php
namespace App\Infrastructure\DataSource\InMemory;

use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\TweetCollection;
use App\Domain\Tweet\TweetRepository;
use App\Domain\Tweet\Username\Username;

class InMemoryTweetRepository implements TweetRepository
{
    /** @var array|Tweet[] */
    private $tweets;

    public function __construct()
    {
        $this->tweets = [];
    }

    public function addTweet(Tweet $tweet): void
    {
        $this->tweets[] = $tweet;
    }

    public function findLatestByUsername(Username $username, int $number = 0): TweetCollection
    {
        $result = [];

        foreach ($this->tweets as $tweet) {
            if ($tweet->username()->equals($username)) {
                $result[] = $tweet;

                if ($number !== 0 && count($result) >= $number) return TweetCollection::fromArray($result);
            }
        }

        return TweetCollection::fromArray($result);
    }
}
