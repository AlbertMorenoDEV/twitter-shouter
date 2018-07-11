# twitterShouter
 
## Introduction

The application is based on Symfony 4, but trying to decouple as much as possible to do DDD and Hexagonal Arquitecture. You'll see that all the Symfony required code in /src is inside the Infrastructure folder.
The API is following jsonapi.org specification, a one that I believe that works pretty fine.
I've developed almost all the application doing TDD, the cache decorator no because I ran out of time and I did something pretty fast and basic, as you'll see :)
Even that I tried to do DDD it's a pretty basic approach, since there is just one bounded context and on module.

For the tests I used Mockery to do mocks and Faker to generate random data.
To communicate with Twitter API I used j7mbo/twitter-api-php, first time that I've used and looks pretty simple.

Also I've used some design patterns:
* Factory -> \App\Domain\Tweet\Transform\TransformerFactory
* Named constructor -> \App\Application\Tweet\ShoutLastTweetsResponse, \App\Domain\Tweet\TweetCollection
* Strategy -> \App\Domain\Tweet\Transform\*
* Decorator -> \App\Infrastructure\DataSource\CachedTweetRepository
* Adapter -> \App\Infrastructure\DataSource\TwitterAPI\TwitterAPITweetRepository
* Singleton -> \App\Domain\Tweet\Transform\ShoutTransformer

### How To Run the application

#### Native PHP
* php -S 127.0.0.1:8000 -t public
* http://127.0.0.1:8000/tweets/shouted?username=martinfowler&number=4

#### Docker
* docker-compose build
* docker-compose up -d
* Optional, just if you want to have symfony.local alias: sudo echo $(docker network inspect bridge | grep Gateway | grep -o -E '[0-9\.]+') "symfony.local" >> /etc/hosts
* http://symfony.local/tweets/shouted?username=martinfowler&number=6

### How To Run Integration and Unit tests
* vendor/bin/simple-phpunit


## Problems

The first problem that I found was that I wasn't used to Symfony 4, I've working mostly in 2 and 3 but I wanted to give a try to the new version, so I struggled a bit to be ablet to have all the Symfony code in /src inside the Infrastructure folder.
Also I found some problems declaring services in service.yaml, so I couldn't keep the main file like it was and having a new one inside Infrastructure with the specific declarations.