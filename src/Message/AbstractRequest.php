<?php

/**
 * eWAY Rapid Abstract Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Abstract Request
 *
 * This class forms the base class for eWAY Rapid requests
 *
 * @link https://eway.io/api-v3/#api-reference
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://api.ewaypayments.com';
    protected $testEndpoint = 'https://api.sandbox.ewaypayments.com';
    protected $action;

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getApiVersion()
    {
        return $this->getParameter('apiVersion');
    }

    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }

    public function getPartnerId()
    {
        return $this->getParameter('partnerId');
    }

    public function setPartnerId($value)
    {
        return $this->setParameter('partnerId', $value);
    }

    public function getTransactionType()
    {
        if ($this->getParameter('transactionType')) {
            return $this->getParameter('transactionType');
        }
        return 'Purchase';
    }

    /**
     * Sets the transaction type
     * One of "Purchase" (default), "MOTO" or "Recurring"
     */
    public function setTransactionType($value)
    {
        return $this->setParameter('transactionType', $value);
    }

    public function getShippingMethod()
    {
        return $this->getParameter('shippingMethod');
    }

    public function setShippingMethod($value)
    {
        return $this->setParameter('shippingMethod', $value);
    }

    public function getInvoiceReference()
    {
        return $this->getParameter('invoiceReference');
    }

    public function setInvoiceReference($value)
    {
        return $this->setParameter('invoiceReference', $value);
    }

    /**
     * @return string|NULL
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAccessCode()
    {
        return $this->getParameter('accessCode');
    }

    public function setAccessCode($value)
    {
        return $this->setParameter('accessCode', $value);
    }

    public function getDeviceId()
    {
        return $this->getParameter('deviceId');
    }

    public function setDeviceId($value)
    {
        return $this->setParameter('deviceId', $value);
    }

    public function getCapture()
    {
        return $this->getParameter('capture');
    }

    public function setCapture($value)
    {
        return $this->setParameter('capture', $value);
    }

    public function getSaveCustomer()
    {
        return $this->getParameter('saveCustomer');
    }

    public function setSaveCustomer($value)
    {
        return $this->setParameter('saveCustomer', $value);
    }

    public function getOptions()
    {
        return $this->getParameter('options');
    }

    public function setOptions($value)
    {
        return $this->setParameter('options', $value);
    }

    public function getCustomerData()
    {
        return $this->getParameter('customerData');
    }

    public function setCustomerData($value)
    {
        return $this->setParameter('customerData', $value);
    }

    public function getShippingAddressData()
    {
        return $this->getParameter('shippingAddressData');
    }

    public function setShippingAddressData($value)
    {
        return $this->setParameter('shippingAddressData', $value);
    }

    public function getPaymentInstrument()
    {
        return $this->getParameter('paymentInstrument');
    }

    public function setPaymentInstrument($value)
    {
        return $this->setParameter('paymentInstrument', $value);
    }

    protected function getBaseData()
    {
        $data = array();
        $data['DeviceID'] = $this->getDeviceId() ?: 'omnipay/eway';
        $data['CustomerIP'] = $this->getClientIp();
        $data['PartnerID'] = $this->getPartnerId();
        $data['ShippingMethod'] = $this->getShippingMethod();

        $data['Customer'] = array();
        $card = $this->getCard();
        if ($card) {
            $data['Customer']['Title'] = $card->getTitle();
            $data['Customer']['FirstName'] = $card->getFirstName();
            $data['Customer']['LastName'] = $card->getLastName();
            $data['Customer']['CompanyName'] = $card->getCompany();
            $data['Customer']['Street1'] = $card->getAddress1();
            $data['Customer']['Street2'] = $card->getAddress2();
            $data['Customer']['City'] = $card->getCity();
            $data['Customer']['State'] = $card->getState();
            $data['Customer']['PostalCode'] = $card->getPostCode();
            $data['Customer']['Country'] = $this->normalizeCountryCode($card->getCountry());
            $data['Customer']['Email'] = $card->getEmail();
            $data['Customer']['Phone'] = $card->getPhone();

            $data['ShippingAddress']['FirstName'] = $card->getShippingFirstName();
            $data['ShippingAddress']['LastName'] = $card->getShippingLastName();
            $data['ShippingAddress']['Street1'] = $card->getShippingAddress1();
            $data['ShippingAddress']['Street2'] = $card->getShippingAddress2();
            $data['ShippingAddress']['City'] = $card->getShippingCity();
            $data['ShippingAddress']['State'] = $card->getShippingState();
            $data['ShippingAddress']['Country'] = $this->normalizeCountryCode($card->getShippingCountry());
            $data['ShippingAddress']['PostalCode'] = $card->getShippingPostcode();
            $data['ShippingAddress']['Phone'] = $card->getShippingPhone();
        }

        if (is_array($this->getCustomerData())) {
            $data['Customer'] = array_replace(isset($data['Customer']) ? $data['Customer'] : [], $this->getCustomerData());
        }

        if (isset($data['ShippingAddress']) && is_array($this->getShippingAddressData())) {
            $data['ShippingAddress'] = array_replace($data['ShippingAddress'], $this->getShippingAddressData());
        } elseif (is_array($this->getShippingAddressData())) {
            $data['ShippingAddress'] = $this->getShippingAddressData();
        }

        if ($this->getPaymentInstrument()) {
            $data['PaymentInstrument'] = $this->getPaymentInstrument();
        }

        return $data;
    }

    protected function getItemData()
    {
        $itemArray = array();
        $items = $this->getItems();
        if ($items) {
            foreach ($items as $item) {
                $data = array();
                $data['SKU'] = strval($item->getName());
                $data['Description'] = strval($item->getDescription());
                $data['Quantity'] = strval($item->getQuantity());
                $cost = $this->formatCurrency($item->getPrice());
                $data['UnitCost'] = strval($this->getCostInteger($cost));
                $tax = $this->getItemParameter($item, 'tax');
                if ($tax !== null) {
                    $data['Tax'] = strval($this->getCostInteger($this->formatCurrency($tax)));
                }
                $total = $this->getItemParameter($item, 'total');
                if ($total !== null) {
                    $data['Total'] = strval($this->getCostInteger($this->formatCurrency($total)));
                }
                $itemArray[] = $data;
            }
        }

        return $itemArray;
    }

    protected function getCostInteger($amount)
    {
        return (int) round($amount * pow(10, $this->getCurrencyDecimalPlaces()));
    }

    public function getEndpointBase()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function createJsonHeaders()
    {
        $headers = [
            'Authorization' => 'Basic ' . base64_encode($this->getApiKey() . ':' . $this->getPassword()),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($this->getApiVersion() !== null && $this->getApiVersion() !== '') {
            $headers['X-EWAY-APIVERSION'] = (string) $this->getApiVersion();
        }

        return $headers;
    }

    protected function sendJsonRequest($method, $endpoint, array $data = [])
    {
        $body = $data ? json_encode($data) : null;

        return $this->httpClient->request($method, $endpoint, $this->createJsonHeaders(), $body);
    }

    protected function decodeJsonResponse($httpResponse)
    {
        $data = json_decode((string) $httpResponse->getBody(), true);

        return is_array($data) ? $data : [];
    }

    protected function normalizeCountryCode($country)
    {
        if ($country === null || $country === '') {
            return null;
        }

        return strtolower((string) $country);
    }

    protected function getOptionsData()
    {
        $options = $this->getOptions();
        if (!is_array($options)) {
            return [];
        }

        return array_values(array_map(function ($option) {
            if (is_array($option)) {
                return $option;
            }

            return ['Value' => (string) $option];
        }, $options));
    }

    protected function getItemParameter($item, $key)
    {
        if (method_exists($item, 'getParameters')) {
            $parameters = $item->getParameters();

            return array_key_exists($key, $parameters) ? $parameters[$key] : null;
        }

        if ($item instanceof \ArrayAccess && isset($item[$key])) {
            return $item[$key];
        }

        return null;
    }
}
