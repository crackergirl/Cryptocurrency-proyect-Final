<?php

namespace App\Infrastructure\Validation;
use Exception;
use Illuminate\Http\Response;

class GetBalanceWalletParametersValidation
{
    /***
     * @throws Exception
     */
    public function execute(string $wallet_id): bool
    {
        if(empty($wallet_id)){
            throw new Exception('wallet_id mandatory',Response::HTTP_BAD_REQUEST);
        }
        return true;
    }
}
