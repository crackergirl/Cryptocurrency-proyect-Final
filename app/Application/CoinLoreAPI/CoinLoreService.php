<?php

namespace App\Application\CoinLoreAPI;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;


class CoinLoreService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    public function execute(string $coin): Coin
    {
        return $this->coinLoreCryptoDataSource->getCoin($coin);
    }
}
