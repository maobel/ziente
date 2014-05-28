<?php
/******************************************************
 * @package Venustheme Megamenu module for Magento 1.4.x.x and Magento 1.7.x.x
 * @version 1.0.0.1
 * @author http://venustheme.com
 * @copyright	Copyright (C) December 2010 venustheme.com <@emai:venustheme@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

class Ves_TabsHome_Model_Observer {

    public function beforeRender(Varien_Event_Observer $observer) {
		$action = Mage::app()->getRequest()->getActionName();
		if($action == 'noRoute'){ return ;	}
        
		if( !$this->isAdmin() ){
			$this->_loadMedia();
		}
    }
	public function isAdmin()
    {
        if(Mage::app()->getStore()->isAdmin()) {
            return true;
        }

        if(Mage::getDesign()->getArea() == 'adminhtml') {
            return true;
        }
        return false;
    }
    function _loadMedia($config = array()) {
      
    }

}
