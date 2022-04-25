<?php

namespace App\Infrastructure;

use App\Application\CryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;

class CoinLoreCryptoDataManager implements CoinLoreCryptoDataSource
{

    public function makeRequest(): Coin
    {
        // TODO: Implement makeRequest() method.
    }
}
