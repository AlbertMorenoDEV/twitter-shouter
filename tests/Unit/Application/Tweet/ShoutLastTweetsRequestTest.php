<?php
namespace App\Tests\Unit\Application\Tweet;

use App\Application\InvalidRequestException;
use App\Application\Request;
use App\Application\Tweet\ShoutLastTweetsRequest;
use PHPUnit\Framework\TestCase;

class ShoutLastTweetsRequestTest extends TestCase
{
    private $username = 'someUsername';
    private $number = 5;

    /** @var ShoutLastTweetsRequest */
    private $request;

    protected function setUp()
    {
        parent::setUp();

        $this->request = new ShoutLastTweetsRequest($this->username, $this->number);
    }

    /**
     * @test
     */
    public function shouldReturnAccountName(): void
    {
        $this->assertEquals($this->username, $this->request->username());
    }

    /**
     * @test
     */
    public function shouldReturnNumber(): void
    {
        $this->assertEquals($this->number, $this->request->number());
    }

    /**
     * @test
     * @throws \Exception
     * @dataProvider invalidRequestsProvider
     */
    public function shouldThrowInvalidRequestException(?string $username, ?int $number, string $message): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage($message);

        new ShoutLastTweetsRequest($username, $number);
    }

    /**
     * @throws \Exception
     */
    public function invalidRequestsProvider(): array
    {
        return [
            [null, random_int(1, 20), ShoutLastTweetsRequest::INVALID_USERNAME_MESSAGE],
            ['someUsername', null, ShoutLastTweetsRequest::INVALID_NUMBER_MESSAGE],
            [null, null, ShoutLastTweetsRequest::INVALID_USERNAME_AND_NUMBER_MESSAGE],
            ['', random_int(1, 20), ShoutLastTweetsRequest::INVALID_USERNAME_MESSAGE],
        ];
    }
}
