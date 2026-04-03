<?php

/**
 * eWAY Rapid Shared Page Create Card Request
 */

namespace Omnipay\Eway\Message;

/**
 * eWAY Rapid Shared Page Create Card Request
 *
 * Creates a payment URL using eWAY's Responsive Shared Page
 *
 * @link https://eway.io/api-v3/#responsive-shared-page
 */
class RapidSharedCreateCardRequest extends RapidSharedPurchaseRequest
{
    public function getData()
    {
        $this->validate('returnUrl');

        $data = $this->getBaseData();

        $data['TransactionType'] = 'Purchase';
        $data['RedirectUrl'] = $this->getReturnUrl();

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

        if ($this->getAction() === 'Purchase') {
            $data['Payment'] = [];
            $data['Payment']['TotalAmount'] = (int) $this->getAmountInteger();
            $data['Payment']['InvoiceNumber'] = $this->getTransactionId();
            $data['Payment']['InvoiceDescription'] = $this->getDescription();
            $data['Payment']['CurrencyCode'] = $this->getCurrency();
            $data['Payment']['InvoiceReference'] = $this->getInvoiceReference();
            $data['Method'] = 'TokenPayment';
        } else {
            $data['Method'] = 'CreateTokenCustomer';
        }

        return $data;
    }
}
