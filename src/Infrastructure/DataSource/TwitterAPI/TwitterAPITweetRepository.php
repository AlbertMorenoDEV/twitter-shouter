<?php
namespace App\Infrastructure\DataSource\TwitterAPI;

use App\Domain\Tweet\Content\Content;
use App\Domain\Tweet\Tweet;
use App\Domain\Tweet\TweetCollection;
use App\Domain\Tweet\TweetRepository;
use App\Domain\Tweet\Username\Username;
use TwitterAPIExchange;

class TwitterAPITweetRepository implements TweetRepository
{
    private $twitterAPIExchange;

    public function __construct(TwitterAPIExchange $twitterAPIExchange) {
        $this->twitterAPIExchange = $twitterAPIExchange;
    }

    /**
     * @throws \Exception
     * @return array|Tweet[]
     */
    public function findLatestByUsername(Username $username, int $number = 0): TweetCollection
    {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$username->value().'&count='.$number;
        $requestMethod = 'GET';

        $apiResponse = $this->twitterAPIExchange->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return TweetCollection::fromArray($this->convertJSONResponseToTweetsArray($apiResponse));
    }

    /**
     * @throws \App\Domain\Tweet\Content\InvalidContentException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    private function convertJSONResponseToTweetsArray(string $apiResponse): array
    {
        $tweets = [];

        foreach (json_decode($apiResponse, true) as $item) {
            $username = new Username($item['user']['screen_name']);
            $content = new Content($item['text']);
            $tweets[] = new Tweet($username, $content);
        }

        return $tweets;
    }
}
