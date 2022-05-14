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

    public function open():string
    {
        $wallet_id=$this->createWalletId();
        $this->insertWalletInCache($wallet_id);
        return strval($wallet_id);
    }

    public function createWalletId(): float
    {
        $wallet_id = 1;

        while($this->walletCache->exists($wallet_id)){
            $wallet_id+=1;
        }
        return $wallet_id;
    }

    public function insertWalletInCache(float $wallet_id): bool
    {
        $wallet = new Wallet($wallet_id);
        return $this->walletCache->create($wallet_id,$wallet);
    }
}
