<?php

namespace App\Infrastructure\Controllers;
use App\Application\API\GetWalletService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetWalletController extends BaseController
{
    private GetWalletService $getWalletService;

    public function __construct(GetWalletService $getWalletService)
    {
        $this->getWalletService = $getWalletService;
    }

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $wallet = $this->getWalletService->execute($wallet_id);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([$wallet->toJson()
        ], Response::HTTP_OK);
    }
}
