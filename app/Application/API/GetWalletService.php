<?php

namespace App\Application\API;

use App\Domain\Wallet;
use App\Application\CacheSource\CacheSource;
use Exception;

class GetWalletService
{
    private CacheSource $walletCache;

    public function __construct(CacheSource $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(string $wallet_id): Wallet
    {
        try {
            $wallet = $this->walletCache->get($wallet_id);

        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
        return $wallet;
    }

}
