<?php

namespace App\Infrastructure\Controllers;
use App\Application\API\BuyCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Infrastructure\Validation\ParametersValidation;

class BuyCoinController extends BaseController
{
    private BuyCoinService $buyCoinService;
    private ParametersValidation $parametersValidation;

    public function __construct(BuyCoinService $buyCoinService)
    {
        $this->buyCoinService = $buyCoinService;
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
            $requestStatus = $this->buyCoinService->execute($request->input('coin_id'));

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([$requestStatus
        ], Response::HTTP_OK);
    }
}
