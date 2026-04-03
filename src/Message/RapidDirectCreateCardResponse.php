<?php
/**
 * eWAY Rapid Direct Create Card Response
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Direct Create Card Response
 *
 * This is the response class for Rapid Direct when creating
 * or updating a card
 *
 */
class RapidDirectCreateCardResponse extends RapidResponse
{
    /**
     * @var RapidResponse|null
     */
    protected $purchaseResponse;

    /**
     * @return RapidResponse|null
     */
    public function getPurchaseResponse()
    {
        return $this->purchaseResponse;
    }

    /**
     * @param RapidResponse $purchaseResponse
     */
    public function setPurchaseResponse($purchaseResponse)
    {
        $this->purchaseResponse = $purchaseResponse;
    }

    public function isSuccessful()
    {
        if (!$this->getPurchaseResponse()) {
            return isset($this->data['ResponseMessage']) && $this->data['ResponseMessage'] === 'A2000';
        }

        return isset($this->data['ResponseMessage'])
            && $this->data['ResponseMessage'] === 'A2000'
            && $this->purchaseResponse->isSuccessful();
    }
}
