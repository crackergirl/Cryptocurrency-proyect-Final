<?php

namespace App\Infrastructure\Controllers;
use App\Application\API\GetCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetCoinController extends BaseController
{
    private GetCoinService $getCoinService;

    public function __construct(GetCoinService $getCoinService)
    {
        $this->getCoinService = $getCoinService;
    }

    public function __invoke(string $id_coin): JsonResponse
    {
        try {
            $coin = $this->getCoinService->execute($id_coin);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([$coin->toJson()
        ], Response::HTTP_OK);
    }
}
