<?php

class Ves_ProductCarousel_Block_List extends Mage_Catalog_Block_Product_Abstract 
{
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_config = '';
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_listDesc = array();
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_show = 0;

	protected $_theme = "";
	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{

		$helper =  Mage::helper('ves_productcarousel/data');
	 
		$this->_show = $this->getConfig("show");
 
		if(!$this->_show) return;
		/*End init meida files*/
		$mediaHelper =  Mage::helper('ves_productcarousel/media');
		$mediaHelper->addMediaFile("skin_css", "ves_productcarousel/style.css" );
 
		
		parent::__construct();		
	}
	 
	function _toHtml() { 		 
		if( !$this->_show || !$this->getConfig('show') ) return;
		$theme = ($this->getConfig('theme')!="") ? $this->getConfig('theme') : "default";
		
 		$cms = "";

 		$cms_block_id = $this->getConfig('cmsblock');
 		if($cms_block_id){
 			$cms = Mage::getSingleton('core/layout')->createBlock('cms/block')->setBlockId($cms_block_id)->toHtml();
 		}

		$items = $this->getListProducts();

		$this->assign( "items", $items );
		
		$this->assign( "cms", $cms );
		$my_template = $this->getTemplate();
		if(empty($my_template)) {
			$template = 'ves/productcarousel/default.phtml';
			if( $this->getConfig( "template" ) ){
				$template = $this->getConfig( "template" );
			}
			$this->setTemplate( $template );
		}
			
        return parent::_toHtml();
	}
	
	public function getEffectConfig( $key ){
		return $this->getConfig( $key, "effect_setting" );
	}
	/**
	 * get value of the extension's configuration
	 *
	 * @return string
	 */
	function getConfig( $key, $panel='ves_productcarousel' ){
		if(isset($this->_config[$key])) {
			return $this->_config[$key];
		} else {
			return Mage::getStoreConfig("ves_productcarousel/$panel/$key");
		}
	}
	
	/**
	 * overrde the value of the extension's configuration
	 *
	 * @return string
	 */
	function setConfig( $key, $value ){
		$this->_config[$key] = $value;
		return $this;
	}	
 	
  	 
	 
	function set($params){
	 
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
	public function getListProducts()
    {
    	$products = null;
    	$mode = $this->getConfig('sourceProductsMode', "catalog_source_setting" );
	
		switch ($mode) {
			case 'latest' :
				$products = $this->getListLatestProducts();
				break;
			case 'sale' :
				$products = $this->getListSaleProducts();
				break;
			case 'best_buy' : 
				$products = $this->getListBestSellerProducts();
				break;
			case 'most_viewed' :
				$products = $this->getListMostViewedProducts();
				break;
			case 'featured' :
				$products = $this->getListFeaturedProducts();
				break;
			case 'top_rated' :
				$products = $this->getListTopRatedProducts();
				break;
			default   :
				$products = $this->getListNewProducts();
				break;
			 
		}
		
		return $products;
    }
	public function getListTopRatedProducts() {
		$limit = $this->getConfig('limit_item', 'catalog_source_setting');
		$limit = empty($limit)?5:(int)$limit;
		$storeId    = Mage::app()->getStore()->getId();
		$cateids = $this->getConfig('catsid', 'catalog_source_setting');
		if($cateids) {
			$cateids = explode(",", $cateids);
			$productIds = $this->getProductByCategory();
			$products = Mage::getResourceModel('reports/product_collection')
                   ->addAttributeToSelect('*')
                   ->addAttributeToFilter('visibility', array('neq'=>1))
                   ->addIdFilter($productIds)
                   ->setPageSize( $this->getConfig('limit_item', 'catalog_source_setting') );

			$products->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');

			$products->setOrder('rating_summary', 'desc');
			
			$products->load();

		} else {
			$products = Mage::getResourceModel('reports/product_collection')
                   ->addAttributeToSelect('*')
                   ->addAttributeToFilter('visibility', array('neq'=>1))
                   ->setPageSize( $this->getConfig('limit_item', 'catalog_source_setting') ); // Only return 4 products
			$products->joinField('rating_summary', 'review/review_aggregate', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()), 'left');                
			$products->setOrder('rating_summary', 'desc');
			
			$products->load();
		}
		

		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $this->setProductCollection($products);

		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
	}

	public function getListSaleProducts(){
		$storeId    = Mage::app()->getStore()->getId();
		$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

		$cateids = $this->getConfig('catsid', 'catalog_source_setting');
		if($cateids) {
			$productIds = $this->getProductByCategory();
			$products = Mage::getModel('catalog/product')->getCollection();
			$products->addAttributeToSelect('*')
			                   ->addFieldToFilter('visibility', array(
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
			                   )) //showing just products visible in catalog or both search and catalog
			                   ->addMinimalPrice()
							   ->addUrlRewrite()
							   ->addTaxPercents()
							   ->addStoreFilter($storeId)
							   ->addIdFilter($productIds)
			                   ->addFinalPrice()
			                   ->getSelect()
			                   ->where('price_index.final_price < price_index.price')
			                   ;
    	} else {
		    $products = Mage::getModel('catalog/product')->getCollection();
			$products->addAttributeToSelect('*')
			                   ->addFieldToFilter('visibility', array(
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
			                               Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
			                   )) //showing just products visible in catalog or both search and catalog
			                   ->addMinimalPrice()
							   ->addUrlRewrite()
							   ->addTaxPercents()
							   ->addStoreFilter($storeId)
			                   ->addFinalPrice()
			                   ->getSelect()
			                   ->where('price_index.final_price < price_index.price')
			                   ;
    	}

    	Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item','catalog_source_setting'))->setCurPage(1);
        $this->setProductCollection($products);

		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
	}
    
    public function getListLatestProducts($fieldorder = 'updated_at', $order = 'desc')
    {
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
    	    $productIds = $this->getProductByCategory();
			$products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter($storeId)
		    ->addIdFilter($productIds)
		    ->setOrder ($fieldorder,$order);
    	} else {
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addFinalPrice()
		    ->addStoreFilter($storeId)
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->setOrder ($fieldorder,$order);
    	}		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item','catalog_source_setting'))->setCurPage(1);
        $this->setProductCollection($products);
		
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListBestSellerProducts($fieldorder = 'ordered_qty', $order = 'desc')
    {
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
    	    $productIds = $this->getProductByCategory();
    	    $products = Mage::getResourceModel('reports/product_collection')
			->addOrderedQty()
			->addAttributeToSelect('*')
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()
			->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
			->addIdFilter($productIds)// id product
			->setStoreId($storeId)
			->addStoreFilter($storeId)
			->setOrder ($fieldorder,$order);
    	} else {
		    $products = Mage::getResourceModel('reports/product_collection')
			    ->addOrderedQty()
			    ->addAttributeToSelect('*')
			    ->addMinimalPrice()
			    ->addUrlRewrite()
			    ->addTaxPercents()
			    ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
			    ->setStoreId($storeId)
			    ->addStoreFilter($storeId)
			    ->setOrder ($fieldorder,$order);
    	}
 
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item','catalog_source_setting'))->setCurPage(1);
        $this->setProductCollection($products);
		
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListMostViewedProducts()
    {
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
	    $productIds = $this->getProductByCategory();
	    $products = Mage::getResourceModel('reports/product_collection')
			->addAttributeToSelect('*')
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()
			->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes            
			->setStoreId($storeId)
			->addStoreFilter($storeId)
			->addViewsCount()
			->addIdFilter($productIds);
    	} else {  
    	    $products = Mage::getResourceModel('reports/product_collection')
			->addAttributeToSelect('*')
			->addMinimalPrice()
			->addUrlRewrite()
			->addTaxPercents()
			->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
			->setStoreId($storeId)
			->addStoreFilter($storeId)
			->addViewsCount();
    	}
    	
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize(8)->setCurPage(1);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		
		return $list;
    }
    
    public function getListFeaturedProducts()
    {
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
	    $productIds = $this->getProductByCategory();
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter($storeId)
		    ->addIdFilter($productIds)
		    ->addAttributeToFilter("featured", 1);
	    Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
	    Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($products);		
    	} else {
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter($storeId)
		    ->addAttributeToFilter("featured", 1);
	    Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
	    Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($products);		
    	}
    	
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item','catalog_source_setting'))->setCurPage(1);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListNewProducts()
    {
    	$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid', 'catalog_source_setting');
    	if($cateids) {
	    $productIds = $this->getProductByCategory();
		    $products = Mage::getResourceModel('catalog/product_collection')
			    ->addAttributeToSelect('*')
			    ->addMinimalPrice()
			    ->addUrlRewrite()
			    ->addTaxPercents()
			    ->addStoreFilter($storeId)
			    ->addIdFilter($productIds)
			    ->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
			    ->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
			    ->addAttributeToSort('news_from_date','desc');
    	} else {
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter($storeId)
		    ->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
		    ->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
		    ->addAttributeToSort('news_from_date','desc');
    	}
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item','catalog_source_setting'))->setCurPage(1);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
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
	
    function getProductByCategory(){
        $return = array(); 
        $pids = array();
        $catsid = $this->getConfig('catsid', 'catalog_source_setting');
        $products = Mage::getResourceModel ( 'catalog/product_collection' );
         
        foreach ($products->getItems() as $key => $_product){
            $arr_categoryids[$key] = $_product->getCategoryIds();
            
            if($catsid){    
                if(stristr($catsid, ',') === FALSE) {
                    $arr_catsid[$key] =  array(0 => $catsid);
                }else{
                    $arr_catsid[$key] = explode(",", $catsid);
                }
                
                $return[$key] = $this->inArray($arr_catsid[$key], $arr_categoryids[$key]);
            }
        }
        
        foreach ($return as $k => $v){ 
            if($v==1) $pids[] = $k;
        }    
        
        return $pids;   
    }
}
