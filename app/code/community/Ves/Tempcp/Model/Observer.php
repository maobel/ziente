<?php

class Ves_Tempcp_Model_Observer {

    public function beforeRender(Varien_Event_Observer $observer) {

		$this->_initFramework();
    }

    function _initFramework() {
		
		Mage::getSingleton('core/session', array('name'=>'adminhtml'));
		if(Mage::getSingleton('admin/session')->isLoggedIn()){
		  //do stuff
		} else {
			$themeName =  Mage::getDesign()->getTheme('frontend');
			$themeConfig = Mage::helper('ves_tempcp/theme')->getCurrentTheme();
			$helper = Mage::helper("ves_tempcp/framework")->initFramework( $themeName, $themeConfig );
		}
    }

}
