<?php
namespace App\Domain\Tweet;

use App\Domain\Tweet\Content\Content;
use App\Domain\Tweet\Username\Username;

class Tweet
{
    private $username;
    private $content;

    public function __construct(Username $username, Content $content)
    {
        $this->username = $username;
        $this->content = $content;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function content(): Content
    {
        return $this->content;
    }
}
