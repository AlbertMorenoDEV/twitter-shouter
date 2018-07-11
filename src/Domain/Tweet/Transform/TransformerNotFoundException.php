<?php
namespace App\Domain\Tweet\Transform;

class TransformerNotFoundException extends \Exception
{
    public const MESSAGE = 'The requested transformer does not exist.';
}
