<?php

namespace App\Application\DataSource;
use App\Domain\Coin;

Interface CryptoDataSource
{
    public function getCoin(string $coin): Coin;
}
