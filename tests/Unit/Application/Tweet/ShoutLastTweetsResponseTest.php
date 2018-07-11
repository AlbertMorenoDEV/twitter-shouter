<?php
namespace App\Tests\Unit\Application\Tweet;

use App\Application\Tweet\ShoutLastTweetsResponse;
use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\Username\Username;
use App\Domain\Tweet\Content\Content;
use PHPUnit\Framework\TestCase;

class ShoutLastTweetsResponseTest extends TestCase
{
    /**
     * @test
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     */
    public function shouldReturnValidData(): void
    {
        $faker = \Faker\Factory::create();
        $accountName = $faker->userName;
        $accountContent1 = $faker->text(130);
        $accountContent2 = $faker->text(130);
        $accountContent3 = $faker->text(130);

        $tweets = [
            new Tweet(new Username($accountName), new Content($accountContent1)),
            new Tweet(new Username($accountName), new Content($accountContent2)),
            new Tweet(new Username($accountName), new Content($accountContent3)),
        ];
        $response = ShoutLastTweetsResponse::fromTweetsArray($tweets);

        $this->assertSame([$accountContent1, $accountContent2, $accountContent3], $response->data());
    }
}
