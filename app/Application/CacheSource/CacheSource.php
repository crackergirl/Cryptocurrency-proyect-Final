<?php

namespace App\Application\CacheSource;

use App\Domain\Wallet;

Interface CacheSource
{
    public function get(string $wallet_id): Wallet;

    public function set(string $wallet_id, Wallet $wallet): bool;

    public function create(string $wallet_id, Wallet $wallet): bool;

    public function exists(string $walletId): bool;
}
