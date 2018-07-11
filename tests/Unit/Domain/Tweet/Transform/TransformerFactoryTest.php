<?php
namespace App\Tests\Unit\Domain\Tweet\Transform;

use App\Domain\Tweet\Transform\ShoutTransformer;
use App\Domain\Tweet\Transform\TransformerFactory;
use App\Domain\Tweet\Transform\TransformerNotFoundException;
use PHPUnit\Framework\TestCase;

class TransformerFactoryTest extends TestCase
{
    /**
     * @test
     * @throws TransformerNotFoundException
     */
    public function shouldThrowExceptionIfTransformerDoesNotExist(): void
    {
        $this->expectException(TransformerNotFoundException::class);
        $this->expectExceptionMessage(TransformerNotFoundException::MESSAGE);

        $factory = new TransformerFactory();

        $factory->create('non_existent_transformer');
    }

    /**
     * @test
     * @throws TransformerNotFoundException
     */
    public function shouldReturnShoutInstance(): void
    {
        $factory = new TransformerFactory();

        $transformer = $factory->create('shout');

        $this->assertInstanceOf(ShoutTransformer::class, $transformer);
    }
}
