<?php
/******************************************************
 * @package Venustheme Megamenu module for Magento 1.4.x.x and Magento 1.7.x.x
 * @version 1.0.0.1
 * @author http://venustheme.com
 * @copyright	Copyright (C) December 2010 venustheme.com <@emai:venustheme@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
if(!class_exists("Ves_Tabshome_Model_Abtract")) {
	require_once "Abtract.php";
}
class Ves_TabsHome_Model_Product extends Ves_Tabshome_Model_Abtract{

	protected $_config = '';

	public function getConfig( $key, $val=0) 
    {
		return (isset($this->_config[$key])?$this->_config[$key]:$val);
    }

	public function getListSpecialProducts( $config = array() ){
		$this->_config = $config;
		$storeId    = Mage::app()->getStore()->getId();
		$cateids = $this->getConfig('catsid');
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
        $products->setPageSize($this->getConfig('limit_item',6))->setCurPage(1);
        $this->setProductCollection($products);

		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
	}
    
    public function getListLatestProducts(  $config = array())
    {
    	$fieldorder = 'updated_at';
    	$order = 'desc';
    	$this->_config = $config;
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid');
    	if($cateids) {
    	    $productIds = $this->getProductByCategory();
			$products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter()
		    ->addIdFilter($productIds)
		    ->setOrder ($fieldorder,$order);
    	} else {
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addFinalPrice()
		    ->addStoreFilter()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->setOrder ($fieldorder,$order);
    	}		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item',6))->setCurPage(1);
        $this->setProductCollection($products);
		
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListBestSellerProducts(  $config = array())
    {
    	$this->_config = $config;
    	$fieldorder = 'ordered_qty';
    	$order = 'desc';
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid');
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
        $products->setPageSize($this->getConfig('limit_item',6))->setCurPage(1);
        $this->setProductCollection($products);
		
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListMostViewedProducts(  $config = array())
    {
    	$this->_config = $config;
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid');
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
        $products->setPageSize($this->getConfig('limit_item',6))->setCurPage(1);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListFeaturedProducts(  $config = array())
    { 
    	$this->_config = $config;
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid');
    	if($cateids) {
	    $productIds = $this->getProductByCategory();
	    $products = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    ->addMinimalPrice()
		    ->addUrlRewrite()
		    ->addTaxPercents()
		    ->addStoreFilter()
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
		    ->addStoreFilter()
		    ->addAttributeToFilter("featured", 1);
	    Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
	    Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($products);		
    	}
    	
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item',6))->setCurPage(1);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {
			       
			$list = $products;
		}
		
		return $list;
    }
    
    public function getListNewProducts(  $config = array())
    {
    	$this->_config = $config;
    	$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    	$storeId    = Mage::app()->getStore()->getId();
    	$cateids = $this->getConfig('catsid');
    	if($cateids) {
	    $productIds = $this->getProductByCategory();
		    $products = Mage::getResourceModel('catalog/product_collection')
			    ->addAttributeToSelect('*')
			    ->addMinimalPrice()
			    ->addUrlRewrite()
			    ->addTaxPercents()
			    ->addStoreFilter()
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
		    ->addStoreFilter()
		    ->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
		    ->addAttributeToFilter(array(array('attribute'=>'news_to_date', 'date'=>true, 'from'=>$todayDate), array('attribute'=>'news_to_date', 'is' => new Zend_Db_Expr('null'))),'','left')
		    ->addAttributeToSort('news_from_date','desc');
    	}
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        $products->setPageSize($this->getConfig('limit_item',6))->setCurPage(1);
        $this->setProductCollection($products);
		$this->_addProductAttributesAndPrices($products);
        $list = array();                  
		if (($_products = $this->getProductCollection ()) && $_products->getSize ()) {            
			$list = $products;
		}
		
		return $list;
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
        $catsid=$this->getConfig('catsid');
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


?>