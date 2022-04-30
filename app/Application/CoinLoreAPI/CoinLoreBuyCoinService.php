<?php

namespace App\Application\CoinLoreAPI;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;

class CoinLoreBuyCoinService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    public function execute(): int
    {
        return $this->coinLoreCryptoDataSource->buyCoin();
    }
}
