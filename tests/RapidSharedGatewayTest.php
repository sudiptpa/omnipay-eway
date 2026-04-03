<?php

namespace Omnipay\Eway;

use Omnipay\Tests\GatewayTestCase;

class RapidSharedGatewayTest extends GatewayTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new RapidSharedGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Eway\Message\RapidSharedPurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Eway\Message\RapidCompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(array('transactionReference' => '30430780'));

        $this->assertInstanceOf('\Omnipay\Eway\Message\RapidFetchTransactionRequest', $request);
        $this->assertSame('30430780', $request->getTransactionReference());
    }

    public function testCreateCard()
    {
        $request = $this->gateway->createCard(array('amount' => '10.00'));

        $this->assertInstanceOf('\Omnipay\Eway\Message\RapidSharedCreateCardRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testUpdateCard()
    {
        $request = $this->gateway->updateCard(array('amount' => '10.00'));

        $this->assertInstanceOf('\Omnipay\Eway\Message\RapidSharedUpdateCardRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }
}
