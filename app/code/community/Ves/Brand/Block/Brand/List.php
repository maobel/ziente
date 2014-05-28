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

class Ves_Brand_Block_Brand_List extends Mage_Catalog_Block_Product_List {
	
  var $_show = true;
  /**
   * Contructor
   */
  public function __construct($attributes = array())
  {
    $this->_show = $this->getGeneralConfig("show");
    
    if(!$this->_show) return;
    parent::__construct( $attributes );
  }

	public function getGeneralConfig( $val ){ 
		return Mage::getStoreConfig( "ves_brand/general_setting/".$val );
	}
	
	public function getConfig( $val ){ 
		return Mage::getStoreConfig( "ves_brand/module_setting/".$val );
	}
	
    protected function _prepareLayout()
    {
        $title = $this->getConfig("brandnav_title");
        $this->getLayout()->getBlock('head')->setTitle($title);
        return parent::_prepareLayout();
    }
	
	public function getBrands(){

		return Mage::getModel('ves_brand/brand')->getCollection();
	}

 
  public function getBrand() {
      return Mage::registry('current_brand');
  }

}
?>