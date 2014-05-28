<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Ves * @package     Ves_Tempcp
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Theme controller
 *
 * @category   Ves
 * @package     Ves_Tempcp
 * @author      
 */
require("Mage/Checkout/controllers/CartController.php");

class Ves_Tempcp_CartController extends Mage_Checkout_CartController{

	public function addAction(){
		$cart = $this->_getCart();
		$params = $this->getRequest()->getParams();
		if(isset($params['isAjax']) && $params['isAjax'] == 1){
			 $response = array(); 
			 try {
			 	if (isset($params['qty'])) {
				 		$filter = new Zend_Filter_LocalizedToNormalized( array('locale' => Mage::app()->getLocale()->getLocaleCode()) ); 
				 		$params['qty'] = $filter->filter($params['qty']); 
				} 
			 	$product = $this->_initProduct(); 
			 	$related = $this->getRequest()->getParam('related_product'); 
			 	/** * Check product availability */ 
			 	if (!$product) { 
			 		$response['status'] = 'ERROR';
				 	$response['message'] = $this->__('Unable to find Product ID'); 
				}
				$cart->addProduct($product, $params); 
				if (!empty($related)) { 
				 	$cart->addProductsByIds(explode(',', $related)); 
				} 
				$cart->save(); 
				$this->_getSession()->setCartWasUpdated(true); /** * @todo remove wishlist observer processAddToCart */ 
				Mage::dispatchEvent('checkout_cart_add_product_complete', 
								 	array('product' => $product, 
								 		'request' => $this->getRequest(), 
								 		'response' => $this->getResponse()) );

				if (!$this->_getSession()->getNoCartRedirect(true)) {
				 	if (!$cart->getQuote()->getHasError()){ 
				 		$message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName())); 
				 		$response['status'] = 'SUCCESS'; 
				 		$response['message'] = $message; 
				 	} 
				 } 
			}
			catch (Mage_Core_Exception $e) {
				$msg = ""; 
				if ($this->_getSession()->getUseNotice(true)) {
					$msg = $e->getMessage(); 
				} else { 
					$messages = array_unique(explode("\n", $e->getMessage())); 
					foreach ($messages as $message) { 
						$msg .= $message.'<br/>'; 
					} 
				} 
				$response['status'] = 'ERROR'; 
				$response['message'] = $msg; 
			}
			catch (Exception $e) {
				$response['status'] = 'ERROR';
				$response['message'] = $this->__('Cannot add the item to shopping cart.');
				Mage::logException($e);
			}
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			
		} else {

			parent::addAction();

		}
		return;
	}

	public function deleteAction(){
		$params = $this->getRequest()->getParams();
		if($params['isAjax'] == 1){
			$id = (int) $this->getRequest()->getParam('id');
			$response = array(); 
	        if ($id) {
	            try {
	                $this->_getCart()->removeItem($id)
	                  ->save();
	                $response['status'] = 'SUCCESS';
	                $response['message'] = '';
	            } catch (Exception $e) {
	                $this->_getSession()->addError($this->__('Cannot remove the item.'));
	                $response['status'] = 'ERROR';
					$response['message'] = $this->__('Cannot remove the item.');
	                Mage::logException($e);
	            }
	        }
	        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    	}else {
    		parent::deleteAction();
    	}

	}
}