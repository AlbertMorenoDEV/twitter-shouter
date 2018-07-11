<?php
namespace App\Tests\Unit\Domain\Tweet\Transform;

use App\Domain\Tweet\Transform\ShoutTransformer;
use App\Domain\Tweet\Transform\Transformer;
use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\Username\Username;
use App\Domain\Tweet\Content\Content;
use PHPUnit\Framework\TestCase;

class ShoutTransformerTest extends TestCase
{
    /** @var Tweet */
    private $tweet;

    /** @var Transformer */
    private $transformer;

    /**
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    protected function setUp()
    {
        parent::setUp();

        $text = 'Lorem ipsum dolor sit amet';
        $this->tweet = new Tweet(new Username('someAccount'), new Content($text));
        $this->transformer = ShoutTransformer::getInstance();
    }

    /**
     * @test
     */
    public function shouldReturnNewTweetInstance(): void
    {
        $newTweet = $this->transformer->transform($this->tweet);

        $this->assertNotSame($this->tweet, $newTweet);
    }

    /**
     * @test
     */
    public function shouldTransformTweet(): void
    {
        $expectedText = 'LOREM IPSUM DOLOR SIT AMET!';

        $newTweet = $this->transformer->transform($this->tweet);

        $this->assertEquals($expectedText, $newTweet->content()->value());
    }
}
