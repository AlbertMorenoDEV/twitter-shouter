<?php
namespace App\Tests\Integration\Infrastructure\Symfony\Controller;

use App\Application\Tweet\ShoutLastTweetsUseCase;
use App\Domain\Tweet\Content\Content;
use App\Domain\Tweet\Transform\TransformerFactory;
use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\Username\Username;
use App\Infrastructure\DataSource\InMemory\InMemoryTweetRepository;
use App\Infrastructure\Symfony\Controller\ShoutController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ShoutControllerTest extends TestCase
{
    /** @var ShoutController */
    private $controller;

    /**
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    protected function setUp()
    {
        parent::setUp();

        $repository = new InMemoryTweetRepository();
        $repository->addTweet(new Tweet(new Username('UsernameA'), new Content('Content A')));
        $repository->addTweet(new Tweet(new Username('UsernameB'), new Content('Content B')));
        $repository->addTweet(new Tweet(new Username('UsernameA'), new Content('Content C')));
        $repository->addTweet(new Tweet(new Username('UsernameA'), new Content('Content D')));
        $repository->addTweet(new Tweet(new Username('UsernameC'), new Content('Content E')));
        $repository->addTweet(new Tweet(new Username('UsernameB'), new Content('Content F')));
        $repository->addTweet(new Tweet(new Username('UsernameB'), new Content('Content G')));
        $transformerFactory = new TransformerFactory();
        $useCase = new ShoutLastTweetsUseCase($repository, $transformerFactory);
        $this->controller = new ShoutController($useCase);
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Transform\TransformerNotFoundException
     */
    public function shouldGetTwoTweets(): void
    {
        $request = new Request(['username' => 'UsernameA', 'number' => 2]);

        $response = $this->controller->action($request);

        $this->assertJsonStringEqualsJsonString('{"data":["CONTENT A!","CONTENT C!"]}', $response->getContent());
    }

    /**
     * @test
     * @throws \App\Domain\Tweet\Transform\TransformerNotFoundException
     */
    public function shouldGetThreeTweets(): void
    {
        $request = new Request(['username' => 'UsernameB', 'number' => 4]);

        $response = $this->controller->action($request);

        $this->assertJsonStringEqualsJsonString('{"data":["CONTENT B!","CONTENT F!","CONTENT G!"]}', $response->getContent());
    }
}
