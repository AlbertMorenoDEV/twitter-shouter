<?php
namespace App\Tests\Unit\Domain\Tweet\Content;

use App\Domain\Tweet\Content\Content;
use App\Domain\Tweet\Content\InvalidContentException;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    /**
     * @test
     * @throws InvalidContentException
     */
    public function shouldReturnContent(): void
    {
        $text = \Faker\Factory::create()->text(Content::MAXIMUM_CHARACTERS);

        $tweet = new Content($text);

        $this->assertEquals($text, $tweet->value());
    }

    /**
     * @test
     * @throws InvalidContentException
     */
    public function shouldNotBeTooMuchCharacters(): void
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage(InvalidContentException::TOO_LARGE_MESSAGE);
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut mattis eros. Suspendisse aliquam ultricies lectus. Donec interdum sodales nisi, id convallis ante eleifend in. Suspendisse aliquam ultricies lectus. Donec interdum sodales nisi, id convallis ante eleifend in. Donec interdum sodales nisi, id convallis ante eleifend in.';

        new Content($text);
    }

    /**
     * @test
     * @throws InvalidContentException
     */
    public function shouldNotBeEmpty(): void
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage(InvalidContentException::EMPTY_MESSAGE);
        $text = '';

        new Content($text);
    }
}
