<?php

namespace Tests\app\Application\Validator;
use Tests\TestCase;
use App\Infrastructure\Validator\ParametersValidator;
use Illuminate\Http\Request;
use Exception;

class ParametersValidatorTest extends TestCase
{
    private ParametersValidator $parametersValidator;
    private Request $request;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->parametersValidator = new ParametersValidator();
        $this->request = new Request();
        $this->request->setMethod('POST');
    }

    /**
     * @test
     */
    public function errorValidator()
    {
        $this->request->request->add(['coin_id' => '12345','wallet_id'=>'1']);

        $this->expectException(Exception::class);

        $this->parametersValidator->validateCoinWalletAmount($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function OKValidator()
    {
        $this->request->request->add(['coin_id' => '12345','wallet_id'=>'1', 'amount_usd'=> 0]);

        $response = $this->parametersValidator->validateCoinWalletAmount($this->request);

        $this->assertTrue($response);
    }
}
