<?php

namespace App\Infrastructure\Controllers;

use App\Application\API\BuyCoinService;
use App\Application\API\GetCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Infrastructure\Validator\ParametersValidator;

class BuyCoinController extends BaseController
{
    private BuyCoinService $buyCoinService;
    private GetCoinService $getCoinService;
    private ParametersValidator $parametersValidator;

    public function __construct(BuyCoinService $buyCoinService,GetCoinService $getCoinService)
    {
        $this->buyCoinService = $buyCoinService;
        $this->parametersValidator = new ParametersValidator();
        $this->getCoinService = $getCoinService;
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
            $requestStatus = $this->buyCoinService->execute($request->input('coin_id'),
                $request->input('wallet_id'),$request->input('amount_usd'),$coin );

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }

        return response()->json([$requestStatus
        ], Response::HTTP_OK);
    }
}
