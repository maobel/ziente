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
class Ves_Brand_Block_Brandnav extends Ves_Brand_Block_List 
{

	var $_show = true;
	/**
	 * Contructor
	 */
	public function __construct($attributes = array()){
		$this->_show = $this->getGeneralConfig("show");
 		$enable_scroll = $this->getModuleConfig("enable_scrollmodule");
 		
		if(!$this->_show || !$enable_scroll) return;
		parent::__construct( $attributes );
	}
	
	public function getGeneralConfig( $val, $default = "" ){ 
		return Mage::getStoreConfig( "ves_brand/general_setting/".$val );
	}

	public function _toHtml(){
		$this->setTemplate( "ves/brand/block/brandnav.phtml" );
		
		$collection = Mage::getModel( "ves_brand/brand" )->getCollection();
		
		$this->assign( "brands", $collection );
		return parent::_toHtml();	
	}

	public function getModuleConfig( $val, $default = "" ){ 
		return Mage::getStoreConfig( "ves_brand/module_setting/".$val );
	}
	 
}
?>