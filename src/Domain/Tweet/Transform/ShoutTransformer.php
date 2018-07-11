<?php
namespace App\Domain\Tweet\Transform;

use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\Content\Content;

class ShoutTransformer implements Transformer
{
    /** @var self */
    private static $instance;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getInstance(): self
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    public function transform(Tweet $tweet): Tweet
    {
        $accountName = clone $tweet->username();
        $content = new Content($this->transformText($tweet->content()->value()));
        return new Tweet($accountName, $content);
    }

    private function transformText(string $text): string
    {
        return mb_strtoupper($text) . '!';
    }
}
