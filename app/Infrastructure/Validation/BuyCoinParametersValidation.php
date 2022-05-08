<?php
namespace App\Infrastructure\Validation;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class BuyCoinParametersValidation
{
    /***
     * @throws Exception
     */
    public function execute(Request $request): bool
    {
        if(!$request->exists('coin_id')){
            throw new Exception('coin_id mandatory',Response::HTTP_BAD_REQUEST);
        }
        if(!$request->exists('wallet_id')){
            throw new Exception('wallet_id mandatory',Response::HTTP_BAD_REQUEST);
        }
        if(!$request->exists('amount_usd')){
            throw new Exception('amount_usd mandatory',Response::HTTP_BAD_REQUEST);
        }
        elseif (floatval($request->get('amount_usd')<0)){
            throw new Exception('amount_usd must be over 0',Response::HTTP_BAD_REQUEST);
        }
        return true;
    }
}
