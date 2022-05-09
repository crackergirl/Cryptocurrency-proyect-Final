<?php

namespace App\Application\API;

use App\Application\CacheSource\CacheSource;
use Exception;

class GetBalanceWalletService
{
    private CacheSource $walletCache;

    public function __construct(CacheSource $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(string $wallet_id): float
    {
        try {
            $wallet = $this->walletCache->get($wallet_id);
            return $wallet->getProfit() - $wallet->getExpenses();

        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
    }
}
