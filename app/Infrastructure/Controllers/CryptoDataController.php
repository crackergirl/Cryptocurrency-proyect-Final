<?php

namespace App\Infrastructure\Controllers;

use App\Application\CoinLoreAPI\CoinLoreService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use App\Domain\Coin;

class CryptoDataController extends BaseController
{
    private CoinLoreService $coinLoreService;

    public function __construct(CoinLoreService $coinLoreService)
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
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([ json_encode($coin)
        ], Response::HTTP_OK);
    }
}
