<?php

namespace App\Infrastructure\Controllers;

use App\Application\API\SellCoinService;
use App\Infrastructure\Validation\ParametersValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class SellCoinController
{
    private SellCoinService $sellCoinService;
    private ParametersValidation $parametersValidation;

    public function __construct(SellCoinService $sellCoinService)
    {
        $this->sellCoinService = $sellCoinService;
        $this->parametersValidation = new ParametersValidation();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->parametersValidation->execute($request);
            $requestStatus = $this->sellCoinService->execute($request->input('coin_id'),
                $request->input('wallet_id'),$request->input('amount_usd'));

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([$requestStatus
        ], Response::HTTP_OK);
    }

}
