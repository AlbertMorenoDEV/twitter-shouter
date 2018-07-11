<?php
namespace App\Tests\Unit\Domain\Tweet;

use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\Username\Username;
use App\Domain\Tweet\Content\Content;
use PHPUnit\Framework\TestCase;

class TweetTest extends TestCase
{
    /** @var Tweet */
    private $tweet;

    /** @var Username */
    private $accountName;

    /** @var Content */
    private $content;

    /**
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->accountName = new Username(\Faker\Factory::create()->userName);
        $this->content = new Content(\Faker\Factory::create()->text(130));
        $this->tweet = new Tweet($this->accountName, $this->content);
    }

    /**
     * @test
     */
    public function shouldContainsCorrectAccountName(): void
    {
        $this->assertSame($this->accountName, $this->tweet->username());
    }

    /**
     * @test
     */
    public function shouldContainsCorrectContent(): void
    {
        $this->assertSame($this->content, $this->tweet->content());
    }
}
