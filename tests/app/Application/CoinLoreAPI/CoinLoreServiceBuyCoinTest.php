<?php


namespace Tests\Application\CoinLoreServiceTest;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Application\CoinLoreAPI\CoinLoreBuyCoinService;


class CoinLoreServiceBuyCoinTest extends TestCase
{
    private CoinLoreBuyCoinService $coinLoreBuyCoinService;
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->coinLoreBuyCoinService = new CoinLoreBuyCoinService($this->coinLoreCryptoDataSource);
    }



}
