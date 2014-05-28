<?php

class Ves_TabsHome_Block_List extends Mage_Catalog_Block_Product_Abstract {

    protected $_config = '';
    protected $_categories;


    public function __construct($attributes = array()) {
        $helper = Mage::helper('ves_tabshome/data');
        $this->_config = $helper->get($attributes);

        /* End init meida files */
        $mediaHelper = Mage::helper('ves_tabshome/media');
        $config = $this->_config;
        
        $this->_config['list_cat'] = (empty($config['list_cat']) ? '': $config['list_cat']);
        
        parent::__construct();
    }


    /**
     * overrde the value of the extension's configuration
     *
     * @return string
     */
    function setConfig($key, $value) {
        $this->_config[$key] = $value;
        return $this;
    }

     /**
     * Rendering block content
     *
     * @return string
     */
    public function _toHtml() {

        $config = $this->_config;
		if( !$this->_config["show"] ){	return ;	}
        $my_template = $this->getTemplate();
        if(empty($my_template)) {
            $theme = (isset($config['theme'])) ? "default" : $config['theme'];
            $config['template'] = 'ves/tabshome/' . $theme . '/default.phtml';
        } else {
            $config['template'] = $this->getTemplate();
        }
        
	
		$news = $featured = $specical =	$bestseller = $mostview = array();
		
		
		if( $this->getConfig('enable_new',1) ){
			$news = Mage::getModel('ves_tabshome/product')->getListLatestProducts( $config );
		}
		
	
		if( $this->getConfig('enable_feature',1) ){
			$featured = Mage::getModel('ves_tabshome/product')->getListFeaturedProducts( $config );
		}
		if( $this->getConfig('enable_bestseller',1) ){
			$bestseller = Mage::getModel('ves_tabshome/product')->getListBestSellerProducts( $config );
		}
		if( $this->getConfig('enable_mostview',1) ){
			$mostview = Mage::getModel('ves_tabshome/product')->getListMostViewedProducts( $config );
		}	

		if( $this->getConfig('enable_special',1) ){  
			$specical = Mage::getModel('ves_tabshome/product')->getListSpecialProducts( $config );
			
		}			
		

		$currency = ''.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
		
		$cms = "";

 		$cms_block_id = $this->getConfig('cmsblock');
 		if($cms_block_id){
 			$cms = Mage::getSingleton('core/layout')->createBlock('cms/block')->setBlockId($cms_block_id)->toHtml();
 		}

 		$this->assign( 'cms', $cms );
		$this->assign( 'bestseller', $bestseller );
		$this->assign( 'mostview', $mostview );
		$this->assign( 'news', $news );
		$this->assign( 'featured', $featured );
		$this->assign( 'specical', $specical );
		
		$this->assign('currency', $currency);
		
        $this->assign('config', $config);
        $this->setTemplate($config['template']);
        return parent::_toHtml();
    }
 
    public function getPro()
    {
        $storeId    = Mage::app()->getStore()->getId();
        $products = Mage::getResourceModel('reports/product_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
            ->setStoreId($storeId)
            ->addStoreFilter($storeId);

		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);

        $products->setPageSize(6)->setCurPage(1);

        $this->setProductCollection($products);
    }
    
    function inArray($source, $target) {
		for($i = 0; $i < sizeof ( $source ); $i ++) {
			if (in_array ( $source [$i], $target )) {
			return true;
			}
		}
    }
    
    public function getConfig( $key, $val=0) 
    {
		return (isset($this->_config[$key])?$this->_config[$key]:$val);
    }

    public function subString( $text, $length = 100, $replacer ='...', $is_striped=true ){
    		$text = ($is_striped==true)?strip_tags($text):$text;
    		if(strlen($text) <= $length){
    			return $text;
    		}
    		$text = substr($text,0,$length);
    		$pos_space = strrpos($text,' ');
    		return substr($text,0,$pos_space).$replacer;
	}

}
