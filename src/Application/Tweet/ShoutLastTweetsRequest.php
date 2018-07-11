<?php
namespace App\Application\Tweet;

use App\Application\InvalidRequestException;

class ShoutLastTweetsRequest
{
    public const INVALID_USERNAME_MESSAGE = 'Invalid username';
    public const INVALID_NUMBER_MESSAGE = 'Invalid number';
    public const INVALID_USERNAME_AND_NUMBER_MESSAGE = 'Invalid username and number';

    private $username;
    private $number;

    /**
     * @throws InvalidRequestException
     */
    public function __construct(?string $username, ?int $number)
    {
        $this->guard($username, $number);
        $this->username = $username;
        $this->number = $number;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function number(): int
    {
        return $this->number;
    }

    /**
     * @throws InvalidRequestException
     */
    private function guard(?string $username, ?int $number): void
    {
        if ($this->usernameIsNotValid($username) && $this->numberIsNotValid($number)) {
            throw new InvalidRequestException(self::INVALID_USERNAME_AND_NUMBER_MESSAGE);
        }

        if ($this->usernameIsNotValid($username)) {
            throw new InvalidRequestException(self::INVALID_USERNAME_MESSAGE);
        }

        if ($this->numberIsNotValid($number)) {
            throw new InvalidRequestException(self::INVALID_NUMBER_MESSAGE);
        }
    }

    private function usernameIsNotValid(?string $username): bool
    {
        return $username === null || trim($username) === '';
    }

    private function numberIsNotValid(?int $number): bool
    {
        return $number === null;
    }
}
