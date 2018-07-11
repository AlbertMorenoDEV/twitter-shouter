<?php
namespace App\Application\Tweet;

use App\Domain\Tweet\Transform\TransformerFactory;
use App\Domain\Tweet\TweetRepository;
use App\Domain\Tweet\Username\Username;

class ShoutLastTweetsUseCase
{
    private $repository;
    private $transformerFactory;

    public function __construct(TweetRepository $repository, TransformerFactory $transformerFactory)
    {
        $this->repository = $repository;
        $this->transformerFactory = $transformerFactory;
    }

    /**
     * @throws \App\Domain\Tweet\Transform\TransformerNotFoundException
     * @throws \App\Domain\Tweet\Username\InvalidUsernameException
     */
    public function execute(ShoutLastTweetsRequest $request): ShoutLastTweetsResponse
    {
        $username = new Username($request->username());
        $tweets = $this->repository->findLatestByUsername($username, $request->number());
        $transformedTweets = [];
        $transformer = $this->transformerFactory->create('shout');

        foreach($tweets as $tweet) {
            $transformedTweets[] = $transformer->transform($tweet);
        }

        return ShoutLastTweetsResponse::fromTweetsArray($transformedTweets);
    }
}
