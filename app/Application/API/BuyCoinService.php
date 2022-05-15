<?php

namespace App\Application\API;
use App\Application\CacheSource\CacheSource;
use App\Domain\Coin;
use Exception;

class BuyCoinService
{
    private CacheSource $walletCache;

    public function __construct(CacheSource $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(string $coinId,string $walletId,float $amountUsd, Coin $coin): string
    {
        try
        {
            $this->buyCoin($coinId,$walletId,$amountUsd,$coin);
        }catch (Exception $exception){
             throw new Exception($exception->getMessage(),$exception->getCode());
        }
        return "successful operation";
    }

    /***
     * @throws Exception
     */
    private function buyCoin(string $coinId,string $walletId,float $amountUsd, Coin $coin): void
    {
        $wallet = $this->walletCache->get($walletId);
        $wallet->existCoin($coinId)?
            $wallet->setCoins($coin,$wallet->getAmountCoinByID($coinId) + $amountUsd):
            $wallet->setCoins($coin,$amountUsd);
        $wallet->setExpenses($wallet->getExpenses() + floatval($coin->getPriceUsd())*$amountUsd);
        $this->walletCache->set($walletId,$wallet);
    }
}
