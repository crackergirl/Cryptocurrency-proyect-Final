<?php

namespace Tests\App\Application\CoinLoreServiceTest;

use App\Application\DataSource\CryptoDataSource;
use App\Domain\Coin;
use App\Infrastructure\APIClient;
use Tests\TestCase;
use Exception;
use Mockery;

class APIClientTest extends TestCase
{
    private CryptoDataSource $cryptoDataManager;
    private APIClient $apiClient;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataManager = Mockery::mock(CryptoDataSource::class);
        $this->apiClient = new APIClient($this->cryptoDataManager);
    }

    /**
     * @test
     * @throws Exception
     */
    public function curlError()
    {
        $this->cryptoDataManager
            ->expects('getCoin')
            ->with('-1')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectExceptionMessage('Service unavailable');

        $this->apiClient->getCoin('-1');
    }

    /**
     * @test
     * @throws Exception
     */
    public function coinFound()
    {
        $coin = new Coin('1','1','1','1','1',1);

        $this->cryptoDataManager
            ->expects('getCoin')
            ->with('1')
            ->once()
            ->andReturn(json_encode(array(['id' => '1',
                'name' => '1', 'symbol' => '1',
                'nameid' => '1', 'price_usd' => '1',
                'rank' => 1])));

        $response = $this->apiClient->getCoin('1');

        $this->assertEquals($coin,$response);
    }
}
