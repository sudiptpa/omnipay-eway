<?php

/**
 * eWAY Rapid Complete Purchase Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Complete Purchase Request
 *
 * @link https://eway.io/api-v3/#step-3-request-the-results
 */
class RapidCompletePurchaseRequest extends RapidPurchaseRequest
{
    public function getData()
    {
        $accessCode = $this->getAccessCode();

        if (!$accessCode) {
            $accessCode = $this->httpRequest->query->get('AccessCode');
        }

        $this->setAccessCode($accessCode);
        $this->validate('accessCode');

        return ['AccessCode' => $accessCode];
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendJsonRequest('GET', $this->getEndpoint());

        return $this->response = new RapidResponse($this, $this->decodeJsonResponse($httpResponse));
    }

    protected function getEndpoint()
    {
        return $this->getEndpointBase() . '/AccessCode/' . rawurlencode($this->getAccessCode());
    }
}
