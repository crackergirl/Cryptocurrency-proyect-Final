<?php

namespace App\Infrastructure\Controllers;

use App\Application\CoinLoreAPI\CoinLoreService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class CryptoDataController extends BaseController
{
    private CoinLoreService $coinLoreService;

    public function __construct(CoinLoreService $coinLoreService)
    {
        $this->coinLoreService = $coinLoreService;
    }

    public function __invoke(string $coin): JsonResponse
    {
        try {
            $coin = $this->coinLoreService->execute($coin);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            "{id:".$coin.", email:'".$coin."'}"
        ], Response::HTTP_OK);

    }
}
