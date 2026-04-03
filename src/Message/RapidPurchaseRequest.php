<?php

/**
 * eWAY Rapid Purchase Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Purchase Request
 *
 * Creates a payment URL using eWAY's Transparent Redirect
 *
 * @link https://eway.io/api-v3/#transparent-redirect
 */
class RapidPurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = $this->getBaseData();
        $data['Method'] = 'ProcessPayment';
        $data['TransactionType'] = $this->getTransactionType();
        $data['RedirectUrl'] = $this->getReturnUrl();
        if ($this->getCapture() !== null) {
            $data['Capture'] = (bool) $this->getCapture();
        }
        if ($this->getSaveCustomer() !== null) {
            $data['SaveCustomer'] = (bool) $this->getSaveCustomer();
        }

        $data['Payment'] = [];
        $data['Payment']['TotalAmount'] = $this->getAmountInteger();
        $data['Payment']['InvoiceNumber'] = $this->getTransactionId();
        $data['Payment']['InvoiceDescription'] = $this->getDescription();
        $data['Payment']['CurrencyCode'] = $this->getCurrency();
        $data['Payment']['InvoiceReference'] = $this->getInvoiceReference();

        if ($this->getItems()) {
            $data['Items'] = $this->getItemData();
        }

        $options = $this->getOptionsData();
        if ($options) {
            $data['Options'] = $options;
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendJsonRequest('POST', $this->getEndpoint(), $data);

        return $this->response = new RapidResponse($this, $this->decodeJsonResponse($httpResponse));
    }

    protected function getEndpoint()
    {
        return $this->getEndpointBase() . '/AccessCodes';
    }
}
