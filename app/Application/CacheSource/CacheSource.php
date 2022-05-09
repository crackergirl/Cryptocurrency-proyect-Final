<?php

namespace App\Application\CacheSource;

use App\Domain\Wallet;

Interface CacheSource
{
    public function get(string $wallet_id): Wallet;

    public function set(string $wallet_id, Wallet $wallet): void;
}
