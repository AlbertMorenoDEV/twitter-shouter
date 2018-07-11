<?php
namespace App\Domain\Tweet\Content;

class Content
{
    public const MAXIMUM_CHARACTERS = 280;

    private $value;

    /**
     * @throws InvalidContentException
     */
    public function __construct(string $value)
    {
        $this->guard($value);
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @throws InvalidContentException
     */
    private function guard(string $text): void
    {
        if (trim($text) === '') {
            throw new InvalidContentException(InvalidContentException::EMPTY_MESSAGE);
        }

        if (\strlen($this->getOnlyText($text)) > self::MAXIMUM_CHARACTERS) {
            throw new InvalidContentException(InvalidContentException::TOO_LARGE_MESSAGE);
        }
    }

    private function getOnlyText(string $text)
    {
        $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";
        return preg_replace($regex, ' ', mb_strtolower($text));
    }
}
