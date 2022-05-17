<?php

namespace Tests\App\Application\Validator;

use Exception;
use Tests\TestCase;
use App\Infrastructure\Validator\ParametersValidator;
use Illuminate\Http\Request;

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
     * @throws Exception
     */
    public function errorCoinIdValidatorNotFound()
    {
        $this->request->request->add(['amount_usd' => 3,'wallet_id'=>'1']);

        $this->expectExceptionMessage('coin_id mandatory');

        $this->parametersValidator->coinParametersValidator($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function errorWalletIdValidatorNotFound()
    {
        $this->request->request->add(['amount_usd' => 3,'coin_id'=>'90']);

        $this->expectExceptionMessage('wallet_id mandatory');

        $this->parametersValidator->coinParametersValidator($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function errorAmountValidatorNotFound()
    {
        $this->request->request->add(['wallet_id' => '3','coin_id'=>'90']);

        $this->expectExceptionMessage('amount_usd mandatory');

        $this->parametersValidator->coinParametersValidator($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function errorAmountValidatorNotNumber()
    {
        $this->request->request->add(['wallet_id' => '1','coin_id'=>'90','amount_usd' => 'amount']);

        $this->expectExceptionMessage('amount_usd mandatory');

        $this->parametersValidator->coinParametersValidator($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function errorWalletIdValidatorNotNumber()
    {
        $this->request->request->add(['wallet_id' => '','coin_id'=>'90','amount_usd' => 3]);

        $this->expectExceptionMessage('wallet_id mandatory');

        $this->parametersValidator->coinParametersValidator($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function errorCoinIdValidatorNotNumber()
    {
        $this->request->request->add(['wallet_id' => '1','coin_id'=>'et4','amount_usd' => 3]);

        $this->expectExceptionMessage('coin_id mandatory');

        $this->parametersValidator->coinParametersValidator($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function errorAmountValidatorInvalidNumber()
    {
        $this->request->request->add(['wallet_id' => '1','coin_id'=>'90','amount_usd' => 0]);

        $this->expectExceptionMessage('amount_usd must be over 0');

        $this->parametersValidator->coinParametersValidator($this->request);
    }

    /**
     * @test
     * @throws Exception
     */
    public function coinParameterValidatorOk()
    {
        $this->request->request->add(['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 4]);

        $response = $this->parametersValidator->coinParametersValidator($this->request);

        $this->assertTrue($response);
    }

    /**
     * @test
     * @throws Exception
     */
    public function idValidatorOk()
    {
        $coinId = '3';

        $response = $this->parametersValidator->idNumberValidator($coinId);

        $this->assertTrue($response);
    }

    /**
     * @test
     * @throws Exception
     */
    public function idValidatorError()
    {
        $coinId = 'wallet_id';

        $this->expectExceptionMessage('Invalid parameter format');

        $this->parametersValidator->idNumberValidator($coinId);
    }
}
