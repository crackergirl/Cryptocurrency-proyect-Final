<?php

namespace App\Domain;

class Wallet
{
    private string $wallet_id;
    private float $profit;
    private float $expenses;
    private array $coins;

    /**
     * Wallet constructor.
     * @param string $wallet_id
     */
    public function __construct(string $wallet_id)
    {
        $this->wallet_id = $wallet_id;
        $this->profit = 0;
        $this->expenses = 0;
        $this->coins = array();
    }

    public function getWalletId(): string
    {
        return $this->wallet_id;
    }

    public function getExpenses(): float
    {
        return $this->expenses;
    }

    public function setExpenses(float $expenses): void
    {
        $this->expenses = $expenses;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }

    public function setProfit(float $profit): void
    {
        $this->profit = $profit;
    }

    public function setCoins(Coin $coin,float $amount_usd): void
    {
        $this->coins[$coin->getCoinId()] = array($coin,$amount_usd);
        if ($amount_usd <= 0)
        {
            unset($this->coins[$coin->getCoinId()] );
        }
    }

    public function getCoinByID(string $coin_id): Coin
    {
        return $this->coins[$coin_id][0];
    }
    public function getAmountCoinByID(string $coin_id): float
    {
        return $this->coins[$coin_id][1];
    }

    public function existCoin(string $coin_id):bool
    {
        return array_key_exists($coin_id, $this->coins);
    }

    public function toJson(): string|false
    {
        $coinArray_toJson = array();
        foreach ($this->coins as list ($coin, $amount_usd)){
            $coinArray_toJson[] = [
                /** @var Coin $coin */
                'coin_id' => $coin->getCoinId(),
                'name' => $coin->getName(),
                'symbol' => $coin->getSymbol(),
                'amount' => $amount_usd,
                'value_usd' => floatval($coin->getPriceUsd())
            ];
        }
        return json_encode($coinArray_toJson);
    }

}
