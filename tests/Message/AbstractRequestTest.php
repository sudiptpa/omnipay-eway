<?php

namespace Omnipay\Eway\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = m::mock('\Omnipay\Eway\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testApiKey()
    {
        $this->assertSame($this->request, $this->request->setApiKey('API KEY'));
        $this->assertSame('API KEY', $this->request->getApiKey());
    }

    public function testPassword()
    {
        $this->assertSame($this->request, $this->request->setPassword('secret'));
        $this->assertSame('secret', $this->request->getPassword());
    }

    public function testApiVersion()
    {
        $this->assertSame($this->request, $this->request->setApiVersion('47'));
        $this->assertSame('47', $this->request->getApiVersion());
    }

    public function testPartnerId()
    {
        $this->assertSame($this->request, $this->request->setPartnerId('1234'));
        $this->assertSame('1234', $this->request->getPartnerId());
    }

    public function testTransactionType()
    {
        $this->assertSame($this->request, $this->request->setTransactionType('Purchase'));
        $this->assertSame('Purchase', $this->request->getTransactionType());
    }

    public function testShippingMethod()
    {
        $this->assertSame($this->request, $this->request->setShippingMethod('NextDay'));
        $this->assertSame('NextDay', $this->request->getShippingMethod());
    }

    public function testInvoiceReference()
    {
        $this->assertSame($this->request, $this->request->setInvoiceReference('INV-123'));
        $this->assertSame('INV-123', $this->request->getInvoiceReference());
    }

    public function testAccessCode()
    {
        $this->assertSame($this->request, $this->request->setAccessCode('ABC123'));
        $this->assertSame('ABC123', $this->request->getAccessCode());
    }

    public function testDeviceId()
    {
        $this->assertSame($this->request, $this->request->setDeviceId('device-123'));
        $this->assertSame('device-123', $this->request->getDeviceId());
    }

    public function testCapture()
    {
        $this->assertSame($this->request, $this->request->setCapture(true));
        $this->assertTrue($this->request->getCapture());
    }

    public function testSaveCustomer()
    {
        $this->assertSame($this->request, $this->request->setSaveCustomer(true));
        $this->assertTrue($this->request->getSaveCustomer());
    }

    public function testOptions()
    {
        $options = ['Option1', ['Value' => 'Option2']];
        $this->assertSame($this->request, $this->request->setOptions($options));
        $this->assertSame($options, $this->request->getOptions());
    }

    public function testPaymentInstrument()
    {
        $instrument = ['PaymentType' => 'ApplePay'];
        $this->assertSame($this->request, $this->request->setPaymentInstrument($instrument));
        $this->assertSame($instrument, $this->request->getPaymentInstrument());
    }

    public function testGetItemData()
    {
        $this->request->setItems([
            ['name' => 'Floppy Disk', 'description' => 'MS-DOS', 'quantity' => 2, 'price' => 10],
            ['name' => 'CD-ROM', 'description' => 'Windows 95', 'quantity' => 1, 'price' => 40],
        ]);

        $data = $this->request->getItemData();
        $this->assertSame('Floppy Disk', $data[0]['SKU']);
        $this->assertSame('MS-DOS', $data[0]['Description']);
        $this->assertSame('2', $data[0]['Quantity']);
        $this->assertSame('1000', $data[0]['UnitCost']);

        $this->assertSame('CD-ROM', $data[1]['SKU']);
        $this->assertSame('Windows 95', $data[1]['Description']);
        $this->assertSame('1', $data[1]['Quantity']);
        $this->assertSame('4000', $data[1]['UnitCost']);
    }
}
