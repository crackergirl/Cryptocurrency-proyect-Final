<?php

namespace App\Infrastructure\Controllers;
use App\Application\CoinLoreAPI\CoinLoreBuyCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class CoinLoreBuyCoinController extends BaseController
{
    private CoinLoreBuyCoinService $coinLoreBuyCoinService;

    public function __construct(CoinLoreBuyCoinService $coinLoreBuyCoinService)
    {
        $this->coinLoreBuyCoinService = $coinLoreBuyCoinService;
    }

    public function __invoke(): JsonResponse
    {
        try {
            $requestStatus = $this->coinLoreBuyCoinService->execute();

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([$requestStatus
        ], Response::HTTP_OK);
    }
}
