<?php
namespace App\Domain\Tweet\Transform;

use App\Domain\Tweet\Tweet;

interface Transformer
{
    public function transform(Tweet $tweet): Tweet;
}
