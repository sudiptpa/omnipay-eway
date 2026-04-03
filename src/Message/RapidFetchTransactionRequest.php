<?php

/**
 * eWAY Rapid Fetch Transaction Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Fetch Transaction Request
 *
 * Fetches a transaction using eWAY's Transaction Query API.
 *
 * The REST API supports querying by transaction reference (transaction ID or
 * access code), invoice number, or invoice reference.
 *
 * @link https://www.eway.com.au/api-v3/
 */
class RapidFetchTransactionRequest extends AbstractRequest
{
    public function getData()
    {
        if (
            !$this->getTransactionReference()
            && !$this->getAccessCode()
            && !$this->getTransactionId()
            && !$this->getInvoiceReference()
        ) {
            $this->validate('transactionReference');
        }

        return [];
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendJsonRequest('GET', $this->getEndpoint());

        return $this->response = new RapidFetchTransactionResponse($this, $this->decodeJsonResponse($httpResponse));
    }

    protected function getEndpoint()
    {
        if ($this->getTransactionId()) {
            return $this->getEndpointBase() . '/Transaction/InvoiceNumber/' . rawurlencode($this->getTransactionId());
        }

        if ($this->getInvoiceReference()) {
            return $this->getEndpointBase() . '/Transaction/InvoiceRef/' . rawurlencode($this->getInvoiceReference());
        }

        $reference = $this->getTransactionReference() ?: $this->getAccessCode();

        return $this->getEndpointBase() . '/Transaction/' . rawurlencode($reference);
    }
}
