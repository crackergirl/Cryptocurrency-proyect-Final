<?php

namespace App\Infrastructure\Controllers;
use App\Application\API\OpenWalletService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class OpenWalletController extends BaseController
{
    private OpenWalletService $openWalletService;

    public function __construct(OpenWalletService $openWalletService)
    {
        $this->openWalletService = $openWalletService;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $walletId = $this->openWalletService->execute();
        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json(['wallet_id' => $walletId
        ], Response::HTTP_OK);
    }
}
