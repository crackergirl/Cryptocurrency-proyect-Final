<?php

namespace App\Application\API;
use App\Infrastructure\Cache\WalletCache;
use Exception;

class OpenWalletService
{
    private WalletCache $walletCache;

    public function __construct(WalletCache $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(): string
    {
        try {
            return $this->walletCache->open();

        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
    }
}
