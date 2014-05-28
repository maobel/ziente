<?php 
class Ves_Tempcp_Block_Googlefont extends Mage_Catalog_Block_Product_Abstract {
	
	public $modconfigs = array(); 
	
	public function _toHtml() {
		$this->assign( 'configs', $this->modconfigs );
		$this->setTemplate( 'venustheme/tempcp/googlefont.phtml' );
		return parent::_toHtml();
	}

	public function setConfig( $configs ){
		$this->modconfigs = $configs;
		return $this;
	}
}
?>