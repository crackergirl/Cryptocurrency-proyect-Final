<?php

namespace App\Infrastructure;
use App\Application\DataSource\CryptoDataSource;
use App\Domain\Coin;
use Illuminate\Http\Response;
use Exception;

class APIClient
{
    private CryptoDataSource $cryptoDataManager;

    public function __construct(CryptoDataSource $cryptoDataManager)
    {
        $this->cryptoDataManager = $cryptoDataManager;
    }

    /***
     * @throws Exception
     */
    public function getCoin(string $coinId): Coin
    {
        try {
            $coin = json_decode($this->cryptoDataManager->getCoin($coinId));
            if(empty($coin)){
                throw new Exception('A coin with specified ID was not found.',Response::HTTP_NOT_FOUND);
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
        return new Coin($coin[0]->id, $coin[0]->name, $coin[0]->symbol, $coin[0]->nameid, $coin[0]->price_usd,$coin[0]->rank);
    }
}
