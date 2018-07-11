<?php
namespace App\Domain\Tweet\Content;

class InvalidContentException extends \Exception
{
    public const TOO_LARGE_MESSAGE = 'Tweet content too large';
    public const EMPTY_MESSAGE = 'Tweet content can not be empty';
}
