<?php
 /*------------------------------------------------------------------------
  # VenusTheme Brand Module 
  # ------------------------------------------------------------------------
  # author:    VenusTheme.Com
  # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
  # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.venustheme.com
  # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/
class Ves_Contentslider_Model_Observer  extends Varien_Object
{
	/**
	 *
	 */
	public function beforeRender( Varien_Event_Observer $observer ){

		$helper =  Mage::helper('ves_contentslider/data');
		
		$config = $helper->get();
		$this->_loadMedia( $config );

   }
   
   public function getModuleConfig( $val ){
		return Mage::getStoreConfig( "ves_contentslider/module_setting/".$val );
   }


   function _loadMedia( $config = array()){
	
		$mediaHelper =  Mage::helper('ves_contentslider/media');
		$mediaHelper->addMediaFile("skin_css", "ves_contentslider/style.css" );
	}
}
