<?php

namespace App\Application\API;

use App\Domain\Wallet;
use App\Application\CacheSource\CacheSource;
use Exception;

class OpenWalletService
{
    private CacheSource $walletCache;

    public function __construct(CacheSource $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(): string
    {
        try {
            return $this->open();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
    }

    public function open(): string
    {
        $walletId=$this->createWalletId();
        $this->insertWalletInCache($walletId);

        return strval($walletId);
    }

    public function createWalletId(): float
    {
        $walletId = 1;

        while($this->walletCache->exists($walletId)){
            $walletId+=1;
        }

        return $walletId;
    }

    public function insertWalletInCache(float $walletId): bool
    {
        $wallet = new Wallet($walletId);

        return $this->walletCache->create($walletId,$wallet);
    }
}
