<?php

namespace App\Infrastructure\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class ParametersValidator
{
    /***
     * @throws Exception
     */
    public function coinParametersValidator(Request $request): bool
    {
        if(!$request->exists('coin_id') || !(is_numeric($request->input('coin_id')))){
            throw new Exception('coin_id mandatory',Response::HTTP_BAD_REQUEST);
        }
        if(!$request->exists('wallet_id') || !(is_numeric($request->input('wallet_id')))){
            throw new Exception('wallet_id mandatory',Response::HTTP_BAD_REQUEST);
        }
        if(!$request->exists('amount_usd') || !(is_numeric($request->input('amount_usd')))){
            throw new Exception('amount_usd mandatory',Response::HTTP_BAD_REQUEST);
        }
        elseif (floatval($request->get('amount_usd')<=0)){
            throw new Exception('amount_usd must be over 0',Response::HTTP_BAD_REQUEST);
        }

        return true;
    }

    /***
     * @throws Exception
     */
    public function idNumberValidator(string $objectId): bool
    {
        if(!(is_numeric($objectId))){
            throw new Exception('Invalid parameter format',Response::HTTP_BAD_REQUEST);
        }

        return true;
    }
}
