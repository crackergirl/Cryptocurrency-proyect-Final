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

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $this->parametersValidator->idNumberValidator($wallet_id);
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
