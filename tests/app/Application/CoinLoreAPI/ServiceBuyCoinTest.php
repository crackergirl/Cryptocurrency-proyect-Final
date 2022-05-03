<?php


namespace Tests\Application\CoinLoreServiceTest;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Application\API\BuyCoinService;


class ServiceBuyCoinTest extends TestCase
{
    private BuyCoinService $buyCoinService;
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->buyCoinService = new BuyCoinService($this->coinLoreCryptoDataSource);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(12345)
            ->once()
            ->andThrow(new Exception('A coin with specified ID was not found.',404));

        $this->expectException(Exception::class);

        $this->buyCoinService->execute(12345);
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

        $response = $this->buyCoinService->execute(300);

        $this->assertEquals("Successful Operation",$response);
    }




}
