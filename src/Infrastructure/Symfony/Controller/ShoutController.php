<?php
namespace App\Infrastructure\Symfony\Controller;

use App\Application\InvalidRequestException;
use App\Application\Tweet\ShoutLastTweetsRequest;
use App\Application\Tweet\ShoutLastTweetsUseCase;
use App\Domain\Tweet\Transform\TransformerNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoutController
{
    private $useCase;

    public function __construct(ShoutLastTweetsUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    /**
     * @throws \App\Domain\Tweet\Transform\TransformerNotFoundException
     */
    public function action(Request $request): JsonResponse
    {
        try {
            $response = $this->useCase->execute(new ShoutLastTweetsRequest(
                $request->get('username'),
                $request->get('number')
            ));
        } catch (InvalidRequestException $e) {
            $error = [
                'status' => (string)Response::HTTP_BAD_REQUEST,
                'title' => $e->getMessage(),
            ];
            return new JsonResponse(['errors' => [$error]], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['data' => $response->data()]);
    }
}
