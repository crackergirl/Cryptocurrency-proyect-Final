<?php

namespace Tests\Application\CoinLoreServiceTest;
use App\Application\DataSource\CryptoDataSource;
use App\Domain\Coin;
use App\Infrastructure\APIClient;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Application\API\GetCoinService;

class GetCoinServiceTest extends TestCase
{
    private GetCoinService $getCoinService;
    private APIClient $apiClient;

    /**
    * @setUp
    */
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient = Mockery::mock(APIClient::class);
        $this->getCoinService = new GetCoinService($this->apiClient);
    }

    /**
    * @test
    */
    public function coinNotFound()
    {
        $this->apiClient
            ->expects('getCoin')
            ->with(-1)
            ->once()
            ->andThrow(new Exception('A coin with the specified ID was not found'));

        $this->expectExceptionMessage('A coin with the specified ID was not found');

        $this->getCoinService->execute(-1);
    }

    /**
     * @test
     */
    public function coinFound()
    {
        $coin = new Coin('1','1','1','1','1',1);

        $this->apiClient
            ->expects('getCoin')
            ->with(300)
            ->once()
            ->andReturn($coin);

        $response = $this->getCoinService->execute(300);

        $this->assertEquals($coin,$response);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $this->apiClient
            ->expects('getCoin')
            ->with(20)
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectExceptionMessage('Service unavailable');

        $this->getCoinService->execute(20);
    }

}
