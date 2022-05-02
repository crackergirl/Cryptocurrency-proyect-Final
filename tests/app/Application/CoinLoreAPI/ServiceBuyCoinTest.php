<?php


namespace Tests\Application\CoinLoreServiceTest;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use Illuminate\Http\Response;
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
            ->with('12345')
            ->once()
            ->andThrow('A coin with specified ID was not found.');

        $this->expectException(Exception::class);

        $this->buyCoinService->execute('12345','1',0);
    }



}
