<?php
/**
 * eWAY Rapid Void Request
 */

namespace Omnipay\Eway\Message;

class RapidDirectVoidRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        $data = [];
        $data['TransactionId'] = $this->getTransactionReference();

        return $data;
    }

    protected function getEndpoint()
    {
        return $this->getEndpointBase() . '/CancelAuthorisation';
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendJsonRequest('POST', $this->getEndpoint(), $data);

        return $this->response = new RapidResponse($this, $this->decodeJsonResponse($httpResponse));
    }
}
