<?php

namespace App\Application\API;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Exception;

class BuyCoinService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    /***
     * @param string $coin_id
     * @return string
     * @throws \Exception
     */
    public function execute(string $coin_id,string $wallet_id,float $amount_usd): string
    {
        try {
            return $this->coinLoreCryptoDataSource->buyCoin($coin_id,$wallet_id,$amount_usd);
        }catch (Exception $exception){
             throw new \Exception($exception->getMessage(),$exception->getCode());
        }
    }
}
