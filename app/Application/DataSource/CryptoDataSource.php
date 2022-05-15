<?php

namespace App\Application\DataSource;

Interface CryptoDataSource
{
    public function getCoin(string $coin): bool|string;
}
