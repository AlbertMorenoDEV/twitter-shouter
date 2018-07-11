<?php
namespace App\Tests\Unit\Domain\Tweet\Username;

use App\Domain\Tweet\Username\InvalidUsernameException;
use App\Domain\Tweet\Username\Username;
use PHPUnit\Framework\TestCase;

class UsernameTest extends TestCase
{
    /**
     * @test
     * @throws InvalidUsernameException
     */
    public function shouldReturnContent(): void
    {
        $name = \Faker\Factory::create()->userName;

        $username = new Username($name);

        $this->assertEquals($name, $username->value());
    }

    /**
     * @test
     * @throws InvalidUsernameException
     */
    public function shouldNotBeEmpty(): void
    {
        $this->expectException(InvalidUsernameException::class);
        $this->expectExceptionMessage(InvalidUsernameException::EMPTY_MESSAGE);
        $name = '';

        new Username($name);
    }

    /**
     * @test
     * @throws InvalidUsernameException
     */
    public function shouldBeEquals(): void
    {
        $name = \Faker\Factory::create()->userName;

        $username1 = new Username($name);
        $username2 = new Username($name);

        $this->assertTrue($username1->equals($username2));
    }

    /**
     * @test
     * @throws InvalidUsernameException
     */
    public function shouldNotBeEquals(): void
    {
        $username1 = new Username('user1');
        $username2 = new Username('user2');

        $this->assertFalse($username1->equals($username2));
    }
}
