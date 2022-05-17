<?php

namespace App\Infrastructure\Controllers;

use App\Application\API\GetCoinService;
use App\Infrastructure\Validator\ParametersValidator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetCoinController extends BaseController
{
    private GetCoinService $getCoinService;
    private ParametersValidator $parametersValidator;

    public function __construct(GetCoinService $getCoinService)
    {
        $this->getCoinService = $getCoinService;
        $this->parametersValidator = new ParametersValidator();
    }

    /**
     * @param string $coinId
     * @return JsonResponse
     */
    public function __invoke(string $coinId): JsonResponse
    {
        try {
            $this->parametersValidator->idNumberValidator($coinId);
            $coin = $this->getCoinService->execute($coinId);

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }

        return response()->json([$coin->toJson()
        ], Response::HTTP_OK);
    }
}
