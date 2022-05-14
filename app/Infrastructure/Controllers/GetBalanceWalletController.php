<?php

namespace App\Infrastructure\Controllers;

use App\Application\API\GetBalanceWalletService;
use App\Infrastructure\Validator\ParametersValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetBalanceWalletController extends BaseController
{
    private GetBalanceWalletService $getBalanceWalletService;
    private ParametersValidator $parametersValidator;

    public function __construct(GetBalanceWalletService $getBalanceWalletService)
    {
        $this->getBalanceWalletService = $getBalanceWalletService;
        $this->parametersValidator = new ParametersValidator();
    }

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $this->parametersValidator->idNumberValidator($wallet_id);
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
