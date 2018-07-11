<?php
namespace App\Tests\Unit\Infrastructure\Symfony\Controller;

use App\Application\InvalidRequestException;
use App\Application\Tweet\ShoutLastTweetsRequest;
use App\Application\Tweet\ShoutLastTweetsResponse;
use App\Application\Tweet\ShoutLastTweetsUseCase;
use App\Infrastructure\Symfony\Controller\ShoutController;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoutControllerTest extends TestCase
{
    /** @var ShoutLastTweetsUseCase|MockInterface */
    private $useCaseMock;

    /** @var ShoutController */
    private $controller;

    /** @var ShoutLastTweetsResponse|MockInterface */
    private $responseMock;

    protected function setUp()
    {
        parent::setUp();

        $data = ['LOREM IPSUM DOLOR SIT AMET!', 'LOREM IPSUM DOLOR SIT!'];
        $this->responseMock = $this->createResponseMock($data);
        $this->useCaseMock = $this->createUseCaseMock($this->responseMock);
        $this->controller = new ShoutController($this->useCaseMock);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldCallUseCase(): void
    {
        $request = $this->createRequestMock('someUsername', random_int(1, 20));

        $this->controller->action($request);

        $this->useCaseMock->mockery_verify();
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldGetDataFromResponse(): void
    {
        $request = $this->createRequestMock('someUsername', random_int(1, 20));

        $this->controller->action($request);

        $this->responseMock->mockery_verify();
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldCreateValidResponse(): void
    {
        $expectedResponse = '{"data":["LOREM IPSUM DOLOR SIT AMET!","LOREM IPSUM DOLOR SIT!"]}';
        $request = $this->createRequestMock('someUsername', random_int(1, 20));

        $response = $this->controller->action($request);

        $this->assertJsonStringEqualsJsonString($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function shouldReturnBadRequestResponseIfUseCaseThrowInvalidRequestException(): void
    {
        $expectedResponse = '{"errors":[{"status":"400","title":"Testing error"}]}';
        $request = $this->createRequestMock('someUsername', random_int(1, 20));
        $useCaseMock = \Mockery::mock(ShoutLastTweetsUseCase::class);
        $useCaseMock->shouldReceive('execute')
            ->with(ShoutLastTweetsRequest::class)
            ->andThrow(InvalidRequestException::class, 'Testing error');
        $controller = new ShoutController($useCaseMock);

        $response = $controller->action($request);

        $this->assertJsonStringEqualsJsonString($expectedResponse, $response->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @return \Mockery\MockInterface|ShoutLastTweetsUseCase
     */
    protected function createUseCaseMock($responseMock): \Mockery\MockInterface
    {
        $useCaseMock = \Mockery::mock(ShoutLastTweetsUseCase::class);
        $useCaseMock->shouldReceive('execute')
            ->with(ShoutLastTweetsRequest::class)
            ->andReturn($responseMock)
            ->once();
        return $useCaseMock;
    }

    /**
     * @return \Mockery\MockInterface|ShoutLastTweetsResponse
     */
    protected function createResponseMock(array $data): \Mockery\MockInterface
    {
        $responseMock = \Mockery::mock(ShoutLastTweetsResponse::class);
        $responseMock->shouldReceive('data')->withNoArgs()->andReturn($data)->once();
        return $responseMock;
    }

    /**
     * @return \Mockery\MockInterface|Request
     * @throws \Exception
     */
    protected function createRequestMock(?string $username, ?int $number): \Mockery\MockInterface
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('get')->with('username')->andReturn($username);
        $request->shouldReceive('get')->with('number')->andReturn($number);
        return $request;
    }
}
