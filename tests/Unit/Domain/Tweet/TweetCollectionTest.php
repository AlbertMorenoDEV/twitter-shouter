<?php
namespace App\Tests\Unit\Domain\Tweet;

use App\Domain\Tweet\TweetCollection;
use PHPUnit\Framework\TestCase;

class TweetCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldImplementsIteratorAggretate(): void
    {
        $tweetCollection = new TweetCollection();

        $this->assertInstanceOf(\IteratorAggregate::class, $tweetCollection);
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function shouldHaveThreeTweets(): void
    {
        $tweetCollection = new TweetCollection(TweetFaker::random(), TweetFaker::random(), TweetFaker::random());

        $this->assertCount(3, $tweetCollection);
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function shouldHaveAddedTweets(): void
    {
        $tweetCollection = new TweetCollection(TweetFaker::random(), TweetFaker::random(), TweetFaker::random());
        $tweetCollection->add(TweetFaker::random());

        $this->assertCount(4, $tweetCollection);
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function shouldHaveTweetsFromArray(): void
    {
        $array = [TweetFaker::random(), TweetFaker::random(), TweetFaker::random()];
        $tweetCollection = TweetCollection::fromArray($array);

        $this->assertEquals($array, $tweetCollection->toArray());
    }
}
