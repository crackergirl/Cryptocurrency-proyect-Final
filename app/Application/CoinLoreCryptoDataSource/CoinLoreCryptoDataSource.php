<?php

namespace App\Application\CoinLoreCryptoDataSource;
use App\Domain\Coin;

Interface CoinLoreCryptoDataSource
{
    public function getCoin(string $coin): Coin;

    public function buyCoin(string $coid_id, string $wallet_id,float $amount_usd): int;
}
