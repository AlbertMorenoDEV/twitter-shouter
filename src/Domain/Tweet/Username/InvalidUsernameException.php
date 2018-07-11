<?php
namespace App\Domain\Tweet\Username;

class InvalidUsernameException extends \Exception
{
    public const EMPTY_MESSAGE = 'Username can not be empty';
}
