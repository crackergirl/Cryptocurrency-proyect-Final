<?php

namespace App\Application\CoinLoreCryptoDataSource;
use App\Domain\Coin;

Interface CoinLoreCryptoDataSource
{
    public function getCoin(string $coin): Coin;

}
