<?php

namespace App\Application\CoinLoreCryptoDataSource;
use App\Domain\Coin;

Interface CoinLoreCryptoDataSource//cambiar nombre
{
    public function getCoin(string $coin): Coin;
}
