<?php
namespace App\Tests\Unit\Infrastructure\DataSource\TwitterAPI;

use App\Domain\Tweet\Username\Username;
use App\Infrastructure\DataSource\TwitterAPI\TwitterAPITweetRepository;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class TwitterAPITweetRepositoryTest extends TestCase
{
    private const API_RESPONSE_FIXTURE = 'tests/Unit/Infrastructure/DataSource/TwitterAPI/user_timeline.json';

    private $arrayApiResponse;
    private $username;
    private $number;

    /** @var TwitterAPITweetRepository */
    private $repository;

    /** @var TwitterAPITweetRepository|MockInterface */
    private $twitterAPIExchangeMock;

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        parent::setUp();

        $apiResponse = file_get_contents(self::API_RESPONSE_FIXTURE);
        $this->arrayApiResponse = json_decode($apiResponse, true);
        $this->username = new Username('someUsername');
        $this->number = random_int(1, 10);
        $this->twitterAPIExchangeMock = $this->createTwitterAPIExchangeMock($apiResponse, $this->username, $this->number);
        $this->repository = new TwitterAPITweetRepository($this->twitterAPIExchangeMock);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldCallTwitterAPIExchange(): void
    {
        $this->repository->findLatestByUsername($this->username, $this->number);

        $this->twitterAPIExchangeMock->mockery_verify();
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldConvertJSONAPIResponseToTweetArray(): void
    {
        $tweets = $this->repository->findLatestByUsername($this->username, $this->number)->toArray();

        $this->assertCount(2, $tweets);
        $this->assertEquals($this->arrayApiResponse[0]['text'], $tweets[0]->content()->value());
        $this->assertEquals($this->arrayApiResponse[1]['text'], $tweets[1]->content()->value());
        $this->assertEquals($this->arrayApiResponse[0]['user']['screen_name'], $tweets[0]->username()->value());
        $this->assertEquals($this->arrayApiResponse[1]['user']['screen_name'], $tweets[1]->username()->value());
    }

    /**
     * @return \Mockery\MockInterface|\TwitterAPIExchange
     */
    protected function createTwitterAPIExchangeMock(string $apiResponse, Username $username, int $number): \Mockery\MockInterface
    {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$username->value().'&count='.$number;
        $requestMethod = 'GET';

        $twitterAPIExchangeMock = \Mockery::mock(\TwitterAPIExchange::class);
        $twitterAPIExchangeMock->shouldReceive('setGetfield')
            ->with($getfield)
            ->andReturn($twitterAPIExchangeMock)
            ->once();
        $twitterAPIExchangeMock->shouldReceive('buildOauth')
            ->with($url, $requestMethod)
            ->andReturn($twitterAPIExchangeMock)
            ->once();
        $twitterAPIExchangeMock->shouldReceive('performRequest')
            ->andReturn($apiResponse)
            ->once();

        return $twitterAPIExchangeMock;
    }
}
