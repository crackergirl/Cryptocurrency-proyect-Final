<?php

namespace App\Domain;

class Wallet
{
    private string $wallet_id;

    /**
     * Wallet constructor.
     * @param string $wallet_id
     */
    public function __construct(string $wallet_id)
    {
        $this->wallet_id = $wallet_id;
    }

    /**
     * @return string
     */
    public function getWalletId(): string
    {
        return $this->wallet_id;
    }



}
