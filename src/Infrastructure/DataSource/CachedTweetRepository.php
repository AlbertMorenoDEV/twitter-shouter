<?php
namespace App\Infrastructure\DataSource;

use App\Domain\Tweet\TweetCollection;
use App\Domain\Tweet\TweetRepository;
use App\Domain\Tweet\Username\Username;

class CachedTweetRepository implements TweetRepository
{
    private $repository;
    private $cacheTime;

    public function __construct(TweetRepository $repository, int $cacheTime)
    {
        $this->repository = $repository;
        $this->cacheTime = $cacheTime;
    }

    public function findLatestByUsername(Username $username, int $number = 0): TweetCollection
    {
        $cacheResult = $this->getCache($username, $number);
        if (null !== $cacheResult) {
            return $cacheResult;
        }

        $tweetCollection = $this->repository->findLatestByUsername($username, $number);

        $this->addCache($username, $number, $tweetCollection);

        return $tweetCollection;
    }

    private function getCache(Username $username, int $number): ?TweetCollection
    {
        $cachefile = $this->getCacheFileName($username, $number);

        if (file_exists($cachefile) && time() - $this->cacheTime < filemtime($cachefile)) {
            return unserialize(file_get_contents($cachefile), ['allowed_classes' => true]);
        }

        return null;
    }

    private function addCache(Username $username, int $number, TweetCollection $tweetCollection): void
    {
        $cachefile = $this->getCacheFileName($username, $number);
        $fp = fopen($cachefile, 'wb');
        fwrite($fp, serialize($tweetCollection));
        fclose($fp);
    }

    private function getCacheFileName(Username $username, int $number): string
    {
        if (strpos(getcwd(), '/public') !== false) {
            return '../var/apiCache/' . $username->value() . '_' . $number . '_' . date('Y_m_d');
        }

        return 'var/apiCache/' . $username->value() . '_' . $number . '_' . date('Y_m_d');
    }
}
