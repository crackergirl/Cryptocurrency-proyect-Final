<?php

namespace App\Application\API;
use App\Infrastructure\Cache\WalletCache;
use Exception;

class GetBalanceWalletService
{
    private WalletCache $walletCache;

    public function __construct(WalletCache $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(string $wallet_id): float
    {
        try {
            return ($this->walletCache->getBalance($wallet_id));
        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
    }
}
