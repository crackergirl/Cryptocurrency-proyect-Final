<?php

namespace App\Application\API;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use App\Infrastructure\Cache\WalletCache;
use Exception;

class BuyCoinService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    /***
     * @throws Exception
     */
    public function execute(string $coin_id,string $wallet_id,float $amount_usd): string
    {
        try
        {
            $coin = $this->coinLoreCryptoDataSource->getCoin($coin_id);

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

        $walletCache = new WalletCache();

        $wallet = $walletCache->get($wallet_id);

        $wallet->existCoin($coin_id)?
            $wallet->setCoins($coin,$wallet->getAmountCoinByID($coin_id) + $amount_usd):
            $wallet->setCoins($coin,$amount_usd);
        $wallet->setExpenses($wallet->getExpenses() + floatval($coin->getPriceUsd())*$amount_usd);

        $walletCache->set($wallet_id,$wallet);

    }
}
