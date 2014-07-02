<?php
class Gfgrisales_Payu_PaymentController extends Mage_Core_Controller_Front_Action {

	public function redirectAction()
	{
        $payu = Mage::getModel('payu/payu');
        
        $fields = $payu->getFormFields();

        $form = new Varien_Data_Form();
        $form->setAction( $payu->getGatewayUrl() )
            ->setId('payu_checkout')
            ->setName('payu_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
			
		foreach ($fields as $field=>$value) {
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }
        $html = '<html><body>';
        $html.= $this->__('Usted ser&aacute; redireccionado al punto de pago de PayU en unos segundos.');
        $html.= $form->toHtml();
		//$html.= '<img src="http://www.payulatam.com/logos/img/payu_solo_107x51.png" alt="PayU" width="107" height="51" border="0">';
        $html.= '<script type="text/javascript">document.getElementById("payu_checkout").submit();</script>';
        $html.= '</body></html>';

        echo $html;
	}
	
	public function successAction()
	{
		$merchant_id = Mage::getStoreConfig( 'payment/payu/merchant_id' );
		$secure_key = Mage::getStoreConfig( 'payment/payu/secure_key' );
		
		$session = Mage::getSingleton('checkout/session');
		
		$referenceCode = $_GET['referenceCode'];
		$transactionState = $_GET['transactionState'];
		$transaction_id = $_GET['transaction_id'];
		$signature = $_GET['signature'];
		$currency = $_GET['currency'];
		$TX_VALUE = $_GET['TX_VALUE'];
		$value = number_format($TX_VALUE, 1, '.', '');
		
		$hash_signature = md5($secure_key . '~' . $merchant_id . '~' . $referenceCode . '~' . $value . '~' . $currency . '~' . $transactionState );
		
		if(($transactionState == 4 || $transactionState == 7) && $hash_signature == $signature){
				$this->_redirect('checkout/onepage/success');
		} else {
				$this->_redirect('checkout/onepage/failure');
		}

	}
	
	public function notifyAction()
	{
		$merchant_id = Mage::getStoreConfig( 'payment/payu/merchant_id' );
		$secure_key = Mage::getStoreConfig( 'payment/payu/secure_key' );

		
		$reference_sale = $_POST['reference_sale'];
		$state_pol = $_POST['state_pol'];
		$transaction_id = $_POST['transaction_id'];
		$signature = $_POST['sign'];
		$currency = $_POST['currency'];
		$value = $_POST['value'];
		
		$split = explode('.', $value);
		$decimals = $split[1];
		if ($decimals % 10 == 0) {
			$value = number_format($value, 1, '.', '');
		}
		
		$return_message = $_POST['response_message_pol'];
		
		$hash_signature = md5($secure_key . '~' . $merchant_id . '~' . $reference_sale . '~' . $value . '~' . $currency . '~' . $state_pol );
		if(($state_pol == 4 || $state_pol == 7) && $hash_signature == $signature){
				$order = Mage::getModel('sales/order')->loadByIncrementId($reference_sale);
				
				$order->getPayment()->setTransactionId($transaction_id);
				
				$order_comment = $return_message;
				foreach($_POST as $key=>$value){
					$order_comment .= "<br/>$key: $value";
				}
				
				if($state_pol == 4){
					$order->getPayment()->registerCaptureNotification( $_POST['value'] );
					$order->addStatusToHistory($order->getStatus(), $order_comment);
				}elseif($state_pol == 7){
					$order->addStatusToHistory('pending', $order_comment);
				}
				
				//$order->addStatusToHistory($order->getStatus(), $order_comment);
					
				$order->save();
				
		} else {
				$order = Mage::getModel('sales/order')->loadByIncrementId($reference_sale);
				$order->cancel();
        		$order->addStatusToHistory($order->getStatus(), $return_message);
				$order->save();
		}
		
		exit;

	}
	
}