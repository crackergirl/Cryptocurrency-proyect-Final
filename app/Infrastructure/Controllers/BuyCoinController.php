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
        try {
            if($request->exists('coin_id') and  $request->exists('wallet_id')
                and $request->exists('amount_usd')){
                $requestStatus = $this->buyCoinService->execute($request->input('coin_id'),
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
