<?php

namespace App\Application\API;
use App\Domain\Coin;
use App\Infrastructure\APIClient;
use Exception;

class GetCoinService
{
    private APIClient $apiClient;

    public function __construct(APIClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /***
     * @throws Exception
     */
    public function execute(string $coinId): Coin
    {
        try {
            $coin = $this->apiClient->getCoin($coinId);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
        return $coin;
    }
}
