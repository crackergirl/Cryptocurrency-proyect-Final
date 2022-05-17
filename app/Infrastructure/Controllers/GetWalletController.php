<?php

namespace App\Infrastructure\Controllers;

use App\Application\API\GetWalletService;
use App\Infrastructure\Validator\ParametersValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetWalletController extends BaseController
{
    private GetWalletService $getWalletService;
    private ParametersValidator $parametersValidator;

    public function __construct(GetWalletService $getWalletService)
    {
        $this->getWalletService = $getWalletService;
        $this->parametersValidator = new ParametersValidator();
    }

    /**
     * @param string $walletId
     * @return JsonResponse
     */
    public function __invoke(string $walletId): JsonResponse
    {
        try {
            $this->parametersValidator->idNumberValidator($walletId);
            $wallet = $this->getWalletService->execute($walletId);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }

        return response()->json([$wallet->toJson()
        ], Response::HTTP_OK);
    }
}
