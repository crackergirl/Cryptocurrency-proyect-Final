<?php

namespace App\Application\CoinLoreCryptoDataSource;
use App\Domain\Coin;

Interface CoinLoreCryptoDataSource
{
    public function getCoin(string $coin): Coin;

    public function openWallet(): string;

    public function buyCoin(string $coin_id,string $wallet_id,float $amount_usd): string;

}
