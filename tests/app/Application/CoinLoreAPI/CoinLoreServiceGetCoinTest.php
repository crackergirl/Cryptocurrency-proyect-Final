<?php

namespace Tests\Application\CoinLoreServiceTest;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Application\CoinLoreAPI\CoinLoreGetCoinService;




class CoinLoreServiceGetCoinTest extends TestCase
{
    private CoinLoreGetCoinService $coinLoreService;
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    /**
    * @setUp
    */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->coinLoreService = new CoinLoreGetCoinService($this->coinLoreCryptoDataSource);
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

        $this->coinLoreService->execute(-1);
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

        $response = $this->coinLoreService->execute(300);

        $this->assertEquals($coin,$response);
    }

}
