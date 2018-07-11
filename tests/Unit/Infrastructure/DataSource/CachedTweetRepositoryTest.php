<?php
namespace App\Tests\Unit\Infrastructure\DataSource;

use App\Domain\Tweet\Username\Username;
use App\Infrastructure\DataSource\CachedTweetRepository;
use App\Infrastructure\DataSource\InMemory\InMemoryTweetRepository;
use App\Tests\Unit\Domain\Tweet\TweetFaker;
use PHPUnit\Framework\TestCase;

class CachedTweetRepositoryTest extends TestCase
{
    /**
     * @test
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function shouldSerializeTweetCollection(): void
    {
        $username = new Username('john');
        $repository = new InMemoryTweetRepository();
        $repository->addTweet(TweetFaker::random($username->value()));
        $repository->addTweet(TweetFaker::random($username->value()));
        $repository->addTweet(TweetFaker::random($username->value()));
        $repository->addTweet(TweetFaker::random($username->value()));
        $cacheTime = 2;
        $cachedRepository = new CachedTweetRepository($repository, $cacheTime);

        $cachedTweetCollection = $cachedRepository->findLatestByUsername($username);

        $this->assertCount(4, $cachedTweetCollection);
    }
}
