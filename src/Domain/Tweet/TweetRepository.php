<?php
namespace App\Domain\Tweet;

use App\Domain\Tweet\Username\Username;

interface TweetRepository
{
    public function findLatestByUsername(Username $username, int $number = 0): TweetCollection;
}
