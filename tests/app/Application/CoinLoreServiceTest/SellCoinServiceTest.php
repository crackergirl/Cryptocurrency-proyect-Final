<?php

namespace Tests\Application\CoinLoreServiceTest;

use App\Application\API\SellCoinService;
use App\Application\CacheSource\CacheSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Tests\TestCase;
use Exception;
use Mockery;

class SellCoinServiceTest extends TestCase
{

    private SellCoinService $sellCoinService;
    private CacheSource $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);

        $this->sellCoinService = new SellCoinService($this->walletCache);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $coin = new Coin('1','1','1','1','1',1);
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectExceptionMessage('Service unavailable');

        $this->sellCoinService->execute('1','1',3,$coin);
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $coin = new Coin('1','1','1','1','1',1);
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andThrow(new Exception('a wallet with the specified ID was not found.'));

        $this->expectExceptionMessage('a wallet with the specified ID was not found.');

        $this->sellCoinService->execute('1','1',3,$coin);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $coin = new Coin('1','1','1','1','1',1);
        $wallet = new Wallet('1');
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);

        $this->expectExceptionMessage('A coin with specified ID was not found.');

        $this->sellCoinService->execute('1','1',3,$coin);
    }

    /**
     * @test
     */
    public function notEnoughCoins()
    {
        $coin = new Coin('1','1','1','1','1',1);
        $wallet = new Wallet('1');
        $wallet->setCoins($coin,3);
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);

        $this->expectExceptionMessage('the quantity has been exceeded, you have 3.');

        $this->sellCoinService->execute('1','1',4,$coin);
    }

    /**
     * @test
     */
    public function sellCoinSuccessful()
    {
        $coin = new Coin('1','1','1','1','1',1);
        $wallet = new Wallet('1');
        $wallet->setCoins($coin,3);
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);
        $this->walletCache
            ->expects('set')
            ->with('1',Mockery::on(function($wallet){
                return $wallet->getWalletId() === '1';
            }))
            ->once()
            ->andReturn(true);

        $response = $this->sellCoinService->execute('1','1',3,$coin);

        $this->assertEquals("successful operation",$response);
    }

}
