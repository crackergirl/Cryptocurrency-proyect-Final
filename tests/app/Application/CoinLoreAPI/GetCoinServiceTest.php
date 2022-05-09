<?php

namespace Tests\Application\CoinLoreServiceTest;
use App\Application\DataSource\CryptoDataSource;
use App\Domain\Coin;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Application\API\GetCoinService;

class GetCoinServiceTest extends TestCase
{
    private GetCoinService $getCoinService;
    private CryptoDataSource $coinLoreCryptoDataSource;

    /**
    * @setUp
    */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CryptoDataSource::class);

        $this->getCoinService = new GetCoinService($this->coinLoreCryptoDataSource);
    }

    /**
    * @test
    */
    public function coinNotFound()
    {
        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(-1)
            ->once()
            ->andThrow(new Exception('A coin with the specified ID was not found'));

        $this->expectException(Exception::class);

        $this->getCoinService->execute(-1);
    }

    /**
     * @test
     */
    public function coinFound()
    {
        $coin = new Coin('1','1','1','1','1',1);

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(300)
            ->once()
            ->andReturn($coin);

        $response = $this->getCoinService->execute(300);

        $this->assertEquals($coin,$response);
    }

}
