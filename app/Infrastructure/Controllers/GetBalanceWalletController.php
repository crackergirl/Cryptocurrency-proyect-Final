<?php

namespace App\Infrastructure\Controllers;

use App\Application\API\GetBalanceWalletService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetBalanceWalletController extends BaseController
{
    private GetBalanceWalletService $getBalanceWalletService;

    public function __construct(GetBalanceWalletService $getBalanceWalletService)
    {
        $this->getBalanceWalletService = $getBalanceWalletService;
    }

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $response = $this->getBalanceWalletService->execute($wallet_id);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json(["balance_usd" => $response
        ], Response::HTTP_OK);
    }
}
