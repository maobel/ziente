<?php

class Ves_TreeMenu_Model_Observer {

    public function beforeRender(Varien_Event_Observer $observer) {
        $this->_loadMedia();
    }

    function _loadMedia($config = array()) {
		 if( Mage::getStoreConfig("ves_treemenu/ves_treemenu/show") ) { 
			$mediaHelper = Mage::helper('ves_treemenu/media');
			$mediaHelper->addMediaFile( "js", "ves_treemenu/script.js" );
		}
    }

}
