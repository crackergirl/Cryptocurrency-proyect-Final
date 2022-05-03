<?php

namespace App\Infrastructure\Controllers;
use App\Application\API\BuyCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class BuyCoinController extends BaseController
{
    private BuyCoinService $buyCoinService;

    public function __construct(BuyCoinService $buyCoinService)
    {
        $this->buyCoinService = $buyCoinService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if(!$request->exists('coin_id')){
            return response()->json(['coin_id mandatory'
            ], Response::HTTP_BAD_REQUEST);
        }
        if(!$request->exists('wallet_id')){
            return response()->json(['wallet_id mandatory'
            ], Response::HTTP_BAD_REQUEST);
        }
        if(!$request->exists('amount_usd')){
            return response()->json(['amount_usd mandatory'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
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
