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




}
