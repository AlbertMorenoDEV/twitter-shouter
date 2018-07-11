<?php
namespace App\Tests\Unit\Domain\Tweet;

use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\Username\Username;
use App\Domain\Tweet\Content\Content;

class TweetFaker
{
    /**
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    public static function random(?string $username = null): Tweet
    {
        $faker = \Faker\Factory::create();

        return new Tweet(
            new Username($username ?? $faker->userName),
            new Content($faker->text(130))
        );
    }
}
