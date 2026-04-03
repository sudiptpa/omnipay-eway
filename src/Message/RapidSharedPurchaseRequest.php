<?php

/**
 * eWAY Rapid Shared Page Purchase Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Shared Page Purchase Request
 *
 * Creates a payment URL using eWAY's Responsive Shared Page
 *
 * @link https://eway.io/api-v3/#responsive-shared-page
 */
class RapidSharedPurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = $this->getBaseData();
        $data['Method'] = 'ProcessPayment';
        $data['RedirectUrl'] = $this->getReturnUrl();
        $data['TransactionType'] = $this->getTransactionType();
        if ($this->getCapture() !== null) {
            $data['Capture'] = (bool) $this->getCapture();
        }
        if ($this->getSaveCustomer() !== null) {
            $data['SaveCustomer'] = (bool) $this->getSaveCustomer();
        }

        // Shared page parameters (optional)
        $data['CancelUrl'] = $this->getCancelUrl();
        $data['LogoUrl'] = $this->getLogoUrl();
        $data['HeaderText'] = $this->getHeaderText();
        $data['FooterText'] = $this->getFooterText();
        $data['Language'] = $this->getLanguage();
        $data['CustomerReadOnly'] = $this->getCustomerReadOnly();
        $data['CustomView'] = $this->getCustomView();
        $data['VerifyCustomerPhone'] = $this->getVerifyCustomerPhone();
        $data['VerifyCustomerEmail'] = $this->getVerifyCustomerEmail();

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

        return $this->response = new RapidSharedResponse($this, $this->decodeJsonResponse($httpResponse));
    }

    protected function getEndpoint()
    {
        return $this->getEndpointBase() . '/AccessCodesShared';
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancelUrl');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', $value);
    }

    public function getLogoUrl()
    {
        return $this->getParameter('logoUrl');
    }

    public function setLogoUrl($value)
    {
        return $this->setParameter('logoUrl', $value);
    }

    public function getHeaderText()
    {
        return $this->getParameter('headerText');
    }

    public function setHeaderText($value)
    {
        return $this->setParameter('headerText', $value);
    }

    public function getFooterText()
    {
        return $this->getParameter('footerText');
    }

    public function setFooterText($value)
    {
        return $this->setParameter('footerText', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getCustomerReadOnly()
    {
        return $this->getParameter('customerReadOnly');
    }

    public function setCustomerReadOnly($value)
    {
        return $this->setParameter('customerReadOnly', $value);
    }

    public function getCustomView()
    {
        return $this->getParameter('customView');
    }

    public function setCustomView($value)
    {
        return $this->setParameter('customView', $value);
    }

    public function getVerifyCustomerPhone()
    {
        return $this->getParameter('verifyCustomerPhone');
    }

    public function setVerifyCustomerPhone($value)
    {
        return $this->setParameter('verifyCustomerPhone', $value);
    }

    public function getVerifyCustomerEmail()
    {
        return $this->getParameter('verifyCustomerEmail');
    }

    public function setVerifyCustomerEmail($value)
    {
        return $this->setParameter('verifyCustomerEmail', $value);
    }
}
