<?php

namespace Tests\app\Application\Validation;
use Tests\TestCase;
use App\Infrastructure\Validation\BuyCoinParametersValidation;
use Illuminate\Http\Request;
use Exception;

class BuyCoinParametersValidationTest extends TestCase
{
    private BuyCoinParametersValidation $parametersValidation;
    private Request $request;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->parametersValidation = new BuyCoinParametersValidation();
        $this->request = new Request();
        $this->request->setMethod('POST');
    }

    /**
     * @test
     */
    public function validationError()
    {
        $this->request->request->add(['coin_id' => '12345','wallet_id'=>'1']);

        $this->expectException(Exception::class);

        $this->parametersValidation->execute($this->request);
    }

    /**
     * @test
     */
    public function validationOK()
    {
        $this->request->request->add(['coin_id' => '12345','wallet_id'=>'1', 'amount_usd'=> 0]);

        $response = $this->parametersValidation->execute($this->request);

        $this->assertTrue($response);
    }
}
