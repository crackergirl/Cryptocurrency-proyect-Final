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

    /**
     * @param string $walletId
     * @return JsonResponse
     */
    public function __invoke(string $walletId): JsonResponse
    {
        try {
            $this->parametersValidator->idNumberValidator($walletId);
            $balance = $this->getBalanceWalletService->execute($walletId);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json(["balance_usd" => $balance
        ], Response::HTTP_OK);
    }
}
