<?php

namespace App\Infrastructure\Controllers;

use App\Application\CoinLoreAPI\CoinLoreGetCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class CoinLoreGetCoinController extends BaseController
{
    private CoinLoreGetCoinService $coinLoreService;

    public function __construct(CoinLoreGetCoinService $coinLoreService)
    {
        $this->coinLoreService = $coinLoreService;
    }

    public function __invoke(string $id_coin): JsonResponse
    {
        try {
            $coin = $this->coinLoreService->execute($id_coin);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([$coin->toJson()
        ], Response::HTTP_OK);
    }
}
