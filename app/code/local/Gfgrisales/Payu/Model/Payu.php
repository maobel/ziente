<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Gfgrisales
 * @package    Gfgrisales_Payu
 * @copyright  Copyright (c) 2013 gfranco.info [modified from vnphpexpert.com]
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Gfgrisales_Payu_Model_Payu extends Mage_Payment_Model_Method_Abstract
{
    /**
    * unique internal payment method identifier
    *
    * @var string [a-z0-9_]
    */
    protected $_code = 'payu';
    protected $_canUseForMultishipping  = false;
	
	protected $_formBlockType = 'payu/form'; 
 	protected $_infoBlockType = 'payu/info';
	
    /**
     * Return Order place direct url
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('payu/payment/redirect', array('_secure' => true));
    }
	
	public function getGatewayUrl()
    {
        return Mage::getStoreConfig( 'payment/payu/gateway_url' );
    }
    
    public function getFormFields()
    {
    	$merchant_id = Mage::getStoreConfig( 'payment/payu/merchant_id' );
		$secure_key = Mage::getStoreConfig( 'payment/payu/secure_key' );
		$account_id = Mage::getStoreConfig( 'payment/payu/account_id' );
		$gateway_url = Mage::getStoreConfig( 'payment/payu/gateway_url' );
		$transaction_mode = Mage::getStoreConfig( 'payment/payu/transaction_mode' );

        $checkout = Mage::getSingleton('checkout/session');
        $orderIncrementId = $checkout->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        
        $currency   = $order->getOrderCurrencyCode();
        
        $BAddress = $order->getBillingAddress();
        
        //$paymentAmount = $order->getBaseGrandTotal();
        $paymentAmount = number_format($order->getGrandTotal(),2,'.','');
		$tax = number_format($order->getTaxAmount(),2,'.','');
		
		$taxReturnBase = number_format(($paymentAmount - $tax),2,'.','');
		if($tax == 0) $taxReturnBase = 0;
		//$taxReturnBase = $tax = 0;
		
	    $ProductName = '';
	    $items = $order->getAllItems();
		if ($items)
        {
            foreach($items as $item)
            {
            	if ($item->getParentItem()) continue;
				$ProductName .= $item->getName() . '; ';
            }
        }
		$ProductName = rtrim($ProductName, '; ');
		
		$signature = md5($secure_key . '~' . $merchant_id . '~' . $orderIncrementId . '~' . $paymentAmount . '~' . $currency );
		
		$test = 0;
		if($transaction_mode == 'test') $test = 1;
		
		/*
		$CustomerName = $BAddress->getFirstname() . ' ' . $BAddress->getLastname();
		$CustomerID = $orderIncrementId;
		$CustomerEmail = $order->getCustomerEmail();
		$Total = $paymentAmount;
		$DescriptionBuy = $DescriptionBuy;
		$TaxAmount1 = 0;
		$address1 = implode(' ', $BAddress->getStreet());
		$address2 = '';
		$city = $BAddress->getCity();
		$zipcode = $BAddress->getPostcode();
		$telephone = $BAddress->getTelephone();
		$fax = $BAddress->getFax();
		*/
		
		$params = 	array(
	    				'merchantId'		=>	$merchant_id,
	    				'referenceCode'		=>	$orderIncrementId,
	    				'description'		=>	$ProductName,
	    				'amount'			=>	$paymentAmount,
						'tax'				=>	$tax,
						'taxReturnBase'		=>	$taxReturnBase,
						'signature'			=>	$signature,
						'accountId'			=>	$account_id,
						'currency'			=>	$currency,
						'buyerEmail'		=>	$order->getCustomerEmail(),
						'test'				=>	$test,
						'confirmationUrl'	=>	Mage::getUrl('payu/payment/notify'),
						'responseUrl'		=>	Mage::getUrl('payu/payment/success'),
						//'gateway_url'		=>	$gateway_url,
    				);
        return $params;
    }

    /**
     * Return true if the method can be used at this time
     *
     * @return bool
     */
    public function isAvailable($quote=null)
    {
        return true;
    }
}
