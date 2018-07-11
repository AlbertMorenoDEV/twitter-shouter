<?php
namespace App\Application\Tweet;

use App\Domain\Tweet\Tweet;

class ShoutLastTweetsResponse
{
    private $data;

    private function __construct()
    {
        $this->data = [];
    }

    public static function fromTweetsArray(array $tweets): self
    {
        $response = new self();

        foreach ($tweets as $tweet) {
            $response->addTweet($tweet);
        }

        return $response;
    }

    private function addTweet(Tweet $tweet): void
    {
        $this->data[] = $tweet->content()->value();
    }

    public function data(): array
    {
        return $this->data;
    }
}
