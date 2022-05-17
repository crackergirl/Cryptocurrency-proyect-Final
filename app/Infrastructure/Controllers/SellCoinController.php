<?php

namespace App\Infrastructure\Controllers;

use App\Application\API\GetCoinService;
use App\Application\API\SellCoinService;
use App\Infrastructure\Validator\ParametersValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class SellCoinController
{
    private SellCoinService $sellCoinService;
    private GetCoinService $getCoinService;
    private ParametersValidator $parametersValidator;

    public function __construct(SellCoinService $sellCoinService,GetCoinService $getCoinService)
    {
        $this->sellCoinService = $sellCoinService;
        $this->getCoinService = $getCoinService;
        $this->parametersValidator = new ParametersValidator();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->parametersValidator->coinParametersValidator($request);
            $coin = $this->getCoinService->execute($request->input('coin_id'));
            $requestStatus = $this->sellCoinService->execute($request->input('coin_id'),
                $request->input('wallet_id'),$request->input('amount_usd'),$coin);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }

        return response()->json([$requestStatus
        ], Response::HTTP_OK);
    }
}
