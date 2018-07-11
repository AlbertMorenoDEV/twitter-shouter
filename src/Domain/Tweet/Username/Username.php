<?php
namespace App\Domain\Tweet\Username;

use App\Domain\Tweet\Username\InvalidUsernameException;

class Username
{
    private $value;

    /**
     * @throws InvalidUsernameException
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
     * @throws InvalidUsernameException
     */
    private function guard(string $name): void
    {
        if (trim($name) === '') {
            throw new InvalidUsernameException(InvalidUsernameException::EMPTY_MESSAGE);
        }
    }

    public function equals(self $username): bool
    {
        return $this->value === $username->value();
    }
}
