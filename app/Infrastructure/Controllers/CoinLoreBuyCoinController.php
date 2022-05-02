<?php

namespace App\Infrastructure\Controllers;
use App\Application\CoinLoreAPI\CoinLoreBuyCoinService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class CoinLoreBuyCoinController extends BaseController
{
    private CoinLoreBuyCoinService $coinLoreBuyCoinService;

    public function __construct(CoinLoreBuyCoinService $coinLoreBuyCoinService)
    {
        $this->coinLoreBuyCoinService = $coinLoreBuyCoinService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            if($request->exists('coin_id') and  $request->exists('wallet_id')
                and $request->exists('amount_usd')){
                $requestStatus = $this->coinLoreBuyCoinService->execute($request->input('coin_id'),
                    $request->input('wallet_id')
                    ,$request->input('amount_usd') );
            }

        }catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        return response()->json([$requestStatus
        ], Response::HTTP_OK);
    }
}
