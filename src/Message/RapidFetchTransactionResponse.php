<?php

/**
 * eWAY Rapid Fetch Transaction Response
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Fetch Transaction Response
 *
 * Wraps the Transaction Query API response.
 *
 * @link https://www.eway.com.au/api-v3/
 */
class RapidFetchTransactionResponse extends AbstractResponse
{
    protected function getPrimaryTransaction()
    {
        if (!empty($this->data['Transactions'][0]) && is_array($this->data['Transactions'][0])) {
            return $this->data['Transactions'][0];
        }

        return null;
    }

    public function isSuccessful()
    {
        return $this->getPrimaryTransaction() !== null;
    }

    public function getTransactionReference()
    {
        $transaction = $this->getPrimaryTransaction();

        return isset($transaction['TransactionID']) ? (string) $transaction['TransactionID'] : null;
    }

    public function getTransactionId()
    {
        $transaction = $this->getPrimaryTransaction();

        return isset($transaction['InvoiceNumber']) ? $transaction['InvoiceNumber'] : null;
    }

    public function getCardReference()
    {
        $transaction = $this->getPrimaryTransaction();

        if (isset($transaction['Customer']['TokenCustomerID'])) {
            return $transaction['Customer']['TokenCustomerID'];
        }

        if (isset($transaction['TokenCustomerID'])) {
            return $transaction['TokenCustomerID'];
        }

        return null;
    }

    public function getCode()
    {
        if (!empty($this->data['Errors'])) {
            return $this->data['Errors'];
        }

        $transaction = $this->getPrimaryTransaction();

        if (!empty($transaction['ResponseMessage'])) {
            return $transaction['ResponseMessage'];
        }

        return null;
    }
}
