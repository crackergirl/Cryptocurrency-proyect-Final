<?php

namespace Tests\Application\CoinLoreServiceTest;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use App\Infrastructure\Cache\WalletCache;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Application\API\SellCoinService;

class ServiceSellCoinTest extends TestCase
{
    private SellCoinService $sellCoinService;
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;
    private WalletCache $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->sellCoinService = new SellCoinService($this->coinLoreCryptoDataSource);

        $this->walletCache = new WalletCache();
        $this->walletCache->open();
    }

    /**
     * @test
     */
    public function SellCoin()
    {

        $wallet = $this->walletCache->get('1');
        $coin = new Coin('300','1','1','1','1',1);
        $wallet->setCoins($coin,'2');
        $this->walletCache->set('1',$wallet);

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(300)
            ->once()
            ->andReturn($coin);

        $response = $this->sellCoinService->execute('300','1',2);

        $this->assertEquals('successful operation',$response);
    }

    /**
     * @test
     */
    public function NumberOfCoinsExceeded()
    {
        $wallet = $this->walletCache->get('1');
        $coin = new Coin('300','1','1','1','1',1);
        $wallet->setCoins($coin,'2');
        $this->walletCache->set('1',$wallet);

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(300)
            ->once()
            ->andReturn($coin);

        $this->expectException(Exception::class);

        $this->sellCoinService->execute('300','1',3);

    }

    /**
     * @test
     */
    public function coinNotFoundInWallet()
    {
        $coin = new Coin('1','1','1','1','1',1);

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(1)
            ->once()
            ->andReturn($coin);

        $this->expectException(Exception::class);

        $this->sellCoinService->execute('1','1',0);
    }

}
