<?php
namespace App\Domain\Tweet;

class TweetCollection implements \IteratorAggregate
{
    private $tweets;

    public function __construct(Tweet ...$tweets)
    {
        $this->tweets = $tweets;
    }

    public static function fromArray(array $tweets): self
    {
        $collection = new self();

        foreach ($tweets as $tweet) {
            $collection->add($tweet);
        }

        return $collection;
    }

    public function add(Tweet $tweet): void
    {
        $this->tweets[] = $tweet;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->tweets);
    }

    /**
     * @return array|Tweet[]
     */
    public function toArray(): array
    {
        return $this->tweets;
    }
}
