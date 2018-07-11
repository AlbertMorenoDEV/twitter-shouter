<?php
namespace App\Domain\Tweet\Transform;

class TransformerFactory
{
    /**
     * @throws TransformerNotFoundException
     */
    public function create(string $type): Transformer
    {
        if ($type === 'shout') {
            return ShoutTransformer::getInstance();
        }

        throw new TransformerNotFoundException(TransformerNotFoundException::MESSAGE);
    }
}
