<?php


namespace App\Application\CryptoDataSource;

use App\Domain\Coin;

Interface CoinLoreCryptoDataSource
{
    public function makeRequest(): Coin;

}
