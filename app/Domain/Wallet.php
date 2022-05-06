<?php

namespace App\Domain;
use App\Domain\Coin;

class Wallet
{
    private string $wallet_id;
    private float $profit;
    private float $expenses;
    private $coins = array();

    /**
     * Wallet constructor.
     * @param string $wallet_id
     */
    public function __construct(string $wallet_id)
    {
        $this->wallet_id = $wallet_id;
        $this->profit = 0;
        $this->expenses = 0;
    }

    /**
     * @return string
     */
    public function getWalletId(): string
    {
        return $this->wallet_id;
    }

    /**
     * @return float
     */
    public function getExpenses(): float
    {
        return $this->expenses;
    }

    /**
     * @param float $expenses
     */
    public function setExpenses(float $expenses): void
    {
        $this->expenses = $expenses;
    }

    /**
     * @return float
     */
    public function getProfit(): float
    {
        return $this->profit;
    }

    /**
     * @param float
     */
    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    /**
     * @return array
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * @param Coin
     */
    public function setCoins(Coin $coin,float $amount_usd): void
    {
        $this->coins[$coin->getCoinId()] = array($coin,$amount_usd);
    }

}
