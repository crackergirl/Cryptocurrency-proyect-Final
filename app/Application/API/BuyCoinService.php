<?php

namespace App\Application\API;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;

class BuyCoinService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    public function execute(string $coid_id, string $wallet_id,float $amount_usd): int
    {
        return $this->coinLoreCryptoDataSource->buyCoin($coid_id,$wallet_id,$amount_usd);
    }
}
