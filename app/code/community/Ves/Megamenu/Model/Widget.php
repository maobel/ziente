<?php
/******************************************************
 * @package Ves Megamenu module for Magento 1.4.x.x and Magento 1.7.x.x
 * @version 1.0.0.1
 * @author http://landofcoder.com
 * @copyright	Copyright (C) December 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
?>
<?php
if(!class_exists("Ves_Megamenu_Model_Abtract")) {
	require_once("Abstract.php");
}

class Ves_Megamenu_Model_Widget extends Ves_Megamenu_Model_Abtract
{
	private $widgets = array();

    public function _construct()
    {
        parent::_construct();
        $this->_init('ves_megamenu/widget');
    }

	/**
	 *
	 */
	public function getWidgetContent( $type, $data, $widget_name = ""){
		$method = "renderWidget".ucfirst($type).'Content';

	 	$args = array();

		if( method_exists( $this, $method ) ){
			return $this->{$method}( $args, $data, $widget_name );
		}
		return ;
	}
	/**
	 *
	 */
	public function renderWidgetHtmlContent(  $args, $setting, $widget_name= "" ){
		
		$t  = array(
			'name'			=> '',
			'show_name'		=> 1,
			'html'   		=> ''
		);
		$setting = array_merge( $t, $setting );
		$html = '';

		if( isset($setting['html']) ){
			$processor = Mage::helper('cms')->getPageTemplateProcessor();
			$html = $processor->filter($setting['html']);
		}
		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
						"html" 			=> $html);
  		$output = $this->renderLayoutHtml( "html", $data );

  		return $output;
	}
	/**
	 *
	 */
	public function renderWidgetVideo_codeContent(  $args, $setting, $widget_name= "" ){

		$t  = array(
			'name'			=> '',
			'show_name'		=> 1,
			'video_code'   		=> ''
		);
		$setting = array_merge( $t, $setting );
		$html =  $setting['video_code'];

 		$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');

		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
						"html" 			=> $html);
  		$output = $this->renderLayoutHtml( "html", $data );


  		return $output;
	}
	/**
	 *
	 */
	public function renderWidgetFeedContent(  $args, $setting, $widget_name= "" ){

		$t = array(
			'limit' => 12,
			'show_name'		=> 1,
	 		'feed_url' => ''
		);
		$setting = array_merge( $t, $setting );

	 	$output = '';
	 	if( $setting['feed_url'] ) {
			$content = file_get_contents( $setting['feed_url']  );
			$x = new SimpleXmlElement($content);
			$items = $x->channel->item;

			$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
					   "limit"		=> $setting['limit'],
						"items" 			=> $items);

			$output = $this->renderLayoutHtml( "feed", $data );
		}


		return $output;
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

	/**********/

	public function renderWidgetProductContent( $args, $setting, $widget_name = "" ) {

		$output = '';
		$t = array(
			'show_name'=> '1',
			'product_id' => 0,
			'image_height' => '320',
			'image_width'	 =>  300
		);

		$setting = array_merge( $t, $setting );

		$setting['product_id'] = isset($setting['product_id'])?$setting['product_id']:0;

		if($setting['product_id']) {
			$collection = Mage::getModel('catalog/product')->getCollection()
													  ->addAttributeToSelect('*')
													  ->addAttributeToFilter('entity_id', $setting['product_id']);

			if(!$this->isAdmin()){
				$collection = $this->_addProductAttributesAndPrices( $collection );
			}
			
	        $result = $collection->getFirstItem();

	  		$data = array( "widget_name" 	=> $widget_name,
						   "show_name" 		=> $setting['show_name'],
						   "image_height"	=> $setting['image_height'],
						   "image_width"	=> $setting['image_width'],
						 	"product" 		=> $result);

			$output = $this->renderLayoutHtml( "product", $data );
		}

		return $output;
	}
	/**
	 *
	 */
	public function renderWidgetImageContent(  $args, $setting, $widget_name= "" ){

		$t  = array(
			'show_name'=> '1',
			'group_id'=> '',
			'image_width'   => 80,
			'image_height'	=> 80
		);

		$setting = array_merge( $t, $setting );
		$image = "";
        if ($setting['image_path']) {
            $image = Mage::helper("ves_megamenu")->resizeImage($setting['image_path'], (int)$setting['image_width'], (int)$setting['image_height']);
        }

		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
					   "image_height"	=> $setting['image_height'],
					   "image_width"	=> $setting['image_width'],
						"image" 		=> $image);

		$output = $this->renderLayoutHtml( "image", $data );

		return $output;

	}

	/**
	 *
	 */
	public function renderWidgetVes_blogContent(  $args, $setting, $widget_name= "" ){
		$t  = array(
			'show_name'=> '1',
			'limit'   => '5'
		);
		$setting = array_merge( $t, $setting );

		$collection = Mage::getModel( 'ves_blog/post' )
						->getCollection()
						->addCategoriesFilter(0);
		
		$collection ->setOrder( 'created', 'DESC' );
		
		$collection->setPageSize( (int)$setting['limit'] )->setCurPage( 1 );

		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
					   "blogs" 			=> $collection);

		$output = $this->renderLayoutHtml( "blogs", $data );

  		return $output;
	}

	/**
	 *
	 */
	public function renderWidgetProduct_categoryContent(  $args, $setting, $widget_name= "" ){
		$t  = array(
			'show_name'=> '1',
			'category_id'=> '',
			'limit'   => '5',
			'image_width'=>'200',
			'image_height' =>'200'
		);
		$setting = array_merge( $t, $setting );

		$category = Mage::getModel('catalog/category');
		$category->load((int)$setting['category_id']);

		$collection = $category->getProductCollection();

		$collection->addAttributeToSelect('*')
					->addMinimalPrice()
				    ->addUrlRewrite()
				    ->addTaxPercents()
				    ->addStoreFilter()
				    ->setOrder ("updated_at", "DESC")
				    ->setPage(1, (int)$setting['limit']);

		$collection->load();
				    
		$collection = $this->_addProductAttributesAndPrices( $collection );

		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
					   "image_width"	=> $setting['image_width'],
					   "image_height"	=> $setting['image_height'],
					   "products" 		=> $collection);

		$output = $this->renderLayoutHtml( "product_list", $data );

  		return $output;
	}

	/**
	 * category_list
	 */
	public function renderWidgetCategory_listContent(  $args, $setting, $widget_name= "" ){
		$t = array(
			'show_name'=> '1',
			'category_id'=>'0'
		);
		
		$categories = array();

		$setting = array_merge( $t, $setting );
		$storeId    = Mage::app()->getStore()->getId();
		$setting['category_id'] = (int)$setting['category_id'];

		if($setting['category_id']) {
			$category_model = Mage::getModel('catalog/category'); 
			$_category = $category_model->load($setting['category_id']); 
			$categories = $_category->getChildren();
		}

  		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
					   "categories" 		=> $categories);

		$output = $this->renderLayoutHtml( "category_list", $data );

  		return $output;
	}

	/**
	 *
	 */
	public function renderWidgetProduct_listContent(  $args, $setting, $widget_name= "" ){
		$t = array(
			'show_name'=> '1',
			'list_type'=> '',
			'limit' => 5,
			'image_width'=>'200',
			'image_height' =>'200'
		);
		
		$products = array();

		$setting = array_merge( $t, $setting );
		$storeId    = Mage::app()->getStore()->getId();
		if( $setting['list_type'] == 'bestseller' ) {
			 $products = Mage::getResourceModel('reports/product_collection')
							    ->addOrderedQty()
							    ->addAttributeToSelect('*')
							    ->addMinimalPrice()
							    ->addUrlRewrite()
							    ->addTaxPercents()
							    ->addAttributeToSelect(array('name', 'price', 'small_image')) //edit to suit tastes
							    ->setStoreId($storeId)
							    ->addStoreFilter($storeId)
							    ->setOrder ("ordered_qty", "DESC");

		} else if( $setting['list_type'] == 'special' ) {
			$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
			$products= Mage::getResourceModel('reports/product_collection')
							->addAttributeToSelect('*')
							->addAttributeToFilter('visibility', array('neq'=>1))
							->addAttributeToFilter('special_price', array('neq'=>''))
							->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
							->addAttributeToFilter('special_to_date', array('or'=> array(
								   0 => array('date' => true, 'from' => $todayDate),
								   1 => array('is' => new Zend_Db_Expr('null')))
								   ), 'left')
							->addAttributeToSort('special_from_date', 'DESC');

		} else {
			$products = Mage::getResourceModel('catalog/product_collection')
							    ->addAttributeToSelect('*')
							    ->addMinimalPrice()
							    ->addFinalPrice()
							    ->addStoreFilter()
							    ->addUrlRewrite()
							    ->addTaxPercents()
							    ->setOrder ("updated_at", "DESC");

    	}

		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);

        $products->setPage(1, (int)$setting['limit']);

        $products = $this->_addProductAttributesAndPrices( $products );

  		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
					   "image_width"	=> $setting['image_width'],
					   "image_height"	=> $setting['image_height'],
					   "products" 		=> $products);

		$output = $this->renderLayoutHtml( "product_list", $data );

  		return $output;
	}
	/**
	 *
	 */
	public function renderWidgetStatic_blockContent(  $args, $setting, $widget_name= "" ){

		$t  = array(
			'name'			=> '',
			'show_name'		=> 1,
			'static_id'   		=> ''
		);
		$setting = array_merge( $t, $setting );
		$html = '';

		if( isset($setting['static_id']) && $setting['static_id']){
			$html = Mage::getSingleton('core/layout')
						->createBlock('cms/block')
						->setData('area','frontend')
						->setBlockId($setting['static_id'])->toHtml();
		}

		$data = array("widget_name" 	=> $widget_name,
					   "show_name" 		=> $setting['show_name'],
						"html" 			=> $html);
  		$output = $this->renderLayoutHtml( "html", $data );

  		return $output;
	}

	/**
	 *
	 */
	public function renderContent( $id ){
		$output = '<div class="ves-widget" id="wid-'.$id.'">';
		if(empty($this->widgets)){
			$this->loadWidgets();
		}

		if( isset($this->widgets[$id]) ){
			$output .= $this->getWidgetContent( $this->widgets[$id]->getType(), unserialize(base64_decode($this->widgets[$id]->getParams())), $this->widgets[$id]->getName() );
		}
		$output .= '</div>';
		return $output;
	}
	/**
	 *
	 */
	public function loadWidgets(){
		if( empty($this->widgets) ){
			$widgets = $this->getCollection();
			foreach( $widgets as $widget ){
				$this->widgets[$widget->getId()] =$widget;
			}
		}
	}
	 /**
     * Add all attributes and apply pricing logic to products collection
     * to get correct values in different products lists.
     * E.g. crosssells, upsells, new products, recently viewed
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _addProductAttributesAndPrices(Mage_Catalog_Model_Resource_Product_Collection $collection)
    {
    	$test = Mage::getSingleton('catalog/config')->getProductAttributes();

        return $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addUrlRewrite();
    }
	/**
	 *
	 */
	protected function renderLayoutHtml( $layout, $data = "" ) {
		$templatePath = 'ves' . DS . 'megamenu'.DS.'widgets'.DS.$layout.'.phtml';

        $output = Mage::app()->getLayout()
            ->createBlock("core/template")
            ->setData('area','frontend')
            ->setData('widget', $data)
            ->setTemplate($templatePath)
            ->toHtml();
		return $output;
	}
}

