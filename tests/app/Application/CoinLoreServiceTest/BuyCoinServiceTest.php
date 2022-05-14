<?php

namespace Tests\Application\CoinLoreServiceTest;

use App\Application\API\BuyCoinService;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Application\CacheSource\CacheSource;
use Tests\TestCase;
use Exception;
use Mockery;

class BuyCoinServiceTest extends TestCase
{
    private BuyCoinService $buyCoinService;
    private CacheSource $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);

        $this->buyCoinService = new BuyCoinService($this->walletCache);
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

        $this->buyCoinService->execute('1','1',3,$coin);
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

        $this->buyCoinService->execute('1','1',3,$coin);
    }

    /**
     * @test
     */
    public function buyNewCoinSuccessful()
    {
        $wallet = new Wallet('1');
        $coin = new Coin('1','1','1','1','1',1);
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


        $response = $this->buyCoinService->execute('1','1',3,$coin);

        $this->assertEquals("successful operation",$response);
    }

    /**
     * @test
     */
    public function buyOldCoinSuccessful()
    {
        $wallet = new Wallet('1');
        $coin = new Coin('1','1','1','1','1',1);
        $wallet->setCoins($coin,2);
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


        $response = $this->buyCoinService->execute('1','1',3,$coin);

        $this->assertEquals("successful operation",$response);
    }

}
