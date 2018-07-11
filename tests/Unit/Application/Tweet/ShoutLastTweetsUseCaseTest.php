<?php
namespace App\Tests\Unit\Application\Tweet;

use App\Application\Tweet\ShoutLastTweetsRequest;
use App\Application\Tweet\ShoutLastTweetsUseCase;
use App\Domain\Tweet\Transform\ShoutTransformer;
use App\Domain\Tweet\Transform\TransformerFactory;
use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\TweetCollection;
use App\Domain\Tweet\TweetRepository;
use App\Tests\Unit\Domain\Tweet\TweetFaker;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ShoutLastTweetsUseCaseTest extends TestCase
{
    private const NUMBER_OF_TWEETS = 3;

    /** @var MockInterface|TweetRepository */
    private $repositoryMock;

    /** @var MockInterface|ShoutTransformer */
    private $shoutTransformerMock;

    /** @var MockInterface|TransformerFactory */
    private $transformerFactoryMock;

    /** @var ShoutLastTweetsUseCase */
    private $useCase;

    /**
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->repositoryMock = $this->createTweetRepositoryMock();
        $this->shoutTransformerMock = $this->createShoutTransformerMock();
        $this->transformerFactoryMock = $this->createTransformerFactoryMock($this->shoutTransformerMock);
        $this->useCase = new ShoutLastTweetsUseCase($this->repositoryMock, $this->transformerFactoryMock);
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Transform\TransformerNotFoundException
     * @throws \App\Application\InvalidRequestException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function shouldGetTweets(): void
    {
        $this->repositoryMock->shouldReceive('findLatestByUsername');
        $request = new ShoutLastTweetsRequest('test', 1);

        $this->useCase->execute($request);

        $this->repositoryMock->mockery_verify();
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Transform\TransformerNotFoundException
     * @throws \App\Application\InvalidRequestException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function shouldGetTransformerFromFactory(): void
    {
        $request = new ShoutLastTweetsRequest('test', 1);

        $this->useCase->execute($request);

        $this->transformerFactoryMock->mockery_verify();
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Transform\TransformerNotFoundException
     * @throws \App\Application\InvalidRequestException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function shouldTransformAllTweets(): void
    {
        $request = new ShoutLastTweetsRequest('test', 1);

        $this->useCase->execute($request);

        $this->shoutTransformerMock->mockery_verify();
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
    }

    /**
     * @return MockInterface|TweetRepository
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    protected function createTweetRepositoryMock(): MockInterface
    {
        $repositoryMock = \Mockery::mock(TweetRepository::class);

        $repositoryMock->shouldReceive('findLatestByUsername')
            ->andReturn(TweetCollection::fromArray($this->createRandomTweets()))
            ->once();

        return $repositoryMock;
    }

    /**
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    private function createRandomTweets(): array
    {
        $tweets = [];

        for ($x = 0; $x < self::NUMBER_OF_TWEETS; $x++) {
            $tweets[] = TweetFaker::random();
        }

        return $tweets;
    }

    /**
     * @param ShoutTransformer $shoutTransformerMock
     * @return MockInterface|TransformerFactory
     */
    protected function createTransformerFactoryMock($shoutTransformerMock): MockInterface
    {
        $transformerFactoryMock = \Mockery::mock(TransformerFactory::class);

        $transformerFactoryMock->shouldReceive('create')
            ->with('shout')
            ->andReturn($shoutTransformerMock)
            ->once();

        return $transformerFactoryMock;
    }

    /**
     * @return MockInterface|ShoutTransformer
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    private function createShoutTransformerMock()
    {
        $shoutTransformerMock = \Mockery::mock(ShoutTransformer::class);
        $shoutTransformerMock->shouldReceive('transform')
            ->with(Tweet::class)
            ->andReturn(TweetFaker::random())
            ->times(self::NUMBER_OF_TWEETS);
        return $shoutTransformerMock;
    }
}
