<?php

namespace App\Application\API;

use App\Application\CacheSource\CacheSource;
use App\Domain\Coin;

use Exception;

class BuyCoinService
{
    private CacheSource $walletCache;

    public function __construct(CacheSource $cacheSource)
    {
        $this->walletCache = $cacheSource;
    }

    /***
     * @throws Exception
     */
    public function execute(string $coin_id,string $wallet_id,float $amount_usd, Coin $coin): string
    {
        try
        {
            $this->buyCoin($coin_id,$wallet_id,$amount_usd,$coin);

        }catch (Exception $exception){
             throw new Exception($exception->getMessage(),$exception->getCode());
        }

        return "successful operation";
    }

    /***
     * @throws Exception
     */
    private function buyCoin(string $coin_id,string $wallet_id,float $amount_usd, Coin $coin):void{


        $wallet = $this->walletCache->get($wallet_id);

        $wallet->existCoin($coin_id)?
            $wallet->setCoins($coin,$wallet->getAmountCoinByID($coin_id) + $amount_usd):
            $wallet->setCoins($coin,$amount_usd);
        $wallet->setExpenses($wallet->getExpenses() + floatval($coin->getPriceUsd())*$amount_usd);

        $this->walletCache->set($wallet_id,$wallet);

    }
}
