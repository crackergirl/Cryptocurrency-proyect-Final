<?php

namespace Tests\app\Infrastructure\Cache;

use App\Domain\Wallet;
use App\Infrastructure\Cache\WalletCache;
use Tests\TestCase;

class WalletCacheTest extends TestCase
{

    private WalletCache $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = new WalletCache();
    }

    /**
     * @test
     */
    public function createWallet()
    {
        $wallet = new Wallet("1");

        $this->walletCache->create("1",$wallet);
        $response = $this->walletCache->exists("1");

        $this->assertTrue($response);
    }

    /**
     * @test
     */
    public function getWallet()
    {
        $wallet = new Wallet("1");

        $this->walletCache->create("1",$wallet);
        $response = $this->walletCache->get("1");

        $this->assertEquals($wallet,$response);
    }


    /**
     * @test
     */
    public function notFoundIDWallet()
    {
        $this->expectExceptionMessage('A wallet with specified ID was not found.');

        $this->walletCache->get("1");
    }

}
