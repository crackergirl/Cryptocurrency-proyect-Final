<?php

namespace App\Domain;

class Coin
{
    private string $coin_id;
    private string  $name;
    private string  $symbol;
    private string $name_id;
    private string $price_usd;
    private float $rank;

    public function __construct(string $coin_id, string $name, string $symbol, string $name_id, string $price_usd,float $rank)
    {
        $this->coin_id = $coin_id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->name_id = $name_id;
        $this->price_usd = $price_usd;
        $this->rank = $rank;
    }

    public function getCoinId(): string
    {
        return $this->coin_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getNameId(): string
    {
        return $this->name_id;
    }

    public function getPriceUsd(): string
    {
        return $this->price_usd;
    }

    public function getRank(): float
    {
        return $this->rank;
    }

    public function toJson(): string|false
    {
        return json_encode([
            'coin_id' => $this->getCoinId(),
            'name' => $this->getName(),
            'symbol' => $this->getSymbol(),
            'name_id' => $this->getNameId(),
            'rank' => $this->getRank(),
            'price_usd' => $this->getPriceUsd()
        ]);
    }
}
