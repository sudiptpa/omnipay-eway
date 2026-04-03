<?php

namespace Omnipay\Eway\Message;

use Omnipay\Tests\TestCase;

class RapidFetchTransactionRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new RapidFetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'apiKey' => 'my api key',
            'password' => 'secret',
            'transactionReference' => '30430780',
        ]);
    }

    public function testGetData()
    {
        $this->assertSame([], $this->request->getData());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RapidFetchTransactionRequestSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('30430780', $response->getTransactionReference());
        $this->assertSame('Inv 12345', $response->getTransactionId());
        $this->assertSame('Transaction Approved', $response->getMessage());
        $this->assertSame('A2000', $response->getCode());
    }

    public function testSendFailure()
    {
        $this->request->initialize([
            'apiKey' => 'my api key',
            'password' => 'secret',
            'invoiceReference' => 'missing-ref',
        ]);
        $this->setMockHttpResponse('RapidFetchTransactionRequestFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame(
            'Invalid TransactionSearch,no TransactionID or AccessCode specified',
            $response->getMessage()
        );
        $this->assertSame('V6123', $response->getCode());
    }
}
