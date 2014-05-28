<?php

class Ves_TabsHome_Helper_Data extends Mage_Core_Helper_Abstract {

    
    public function checkAvaiable($controller_name = "") {
        $arr_controller = array("Mage_Cms",
            "Mage_Catalog",
            "Mage_Tag",
            "Mage_Checkout",
            "Mage_Customer",
            "Mage_Wishlist",
            "Mage_CatalogSearch");
        if (!empty($controller_name)) {
            if (in_array($controller_name, $arr_controller)) {
                return true;
            }
        }
        return false;
    }

    public function checkMenuItem( $menu_name = "", $config = array())
	{
		if(!empty( $menu_name) && !empty( $config)){
			$menus = isset($config["menuAssignment"])?$config["menuAssignment"]:"all";
			$menus = explode(",", $menus);
			if( in_array("all", $menus) || in_array( $menu_name, $menus) ){
				return true;
			}
		}
		return false;
	}

    public function getListMenu() {
        $arrayParams = array(
            'all' => Mage::helper('adminhtml')->__("All"),
            'Mage_Cms_index' => Mage::helper('adminhtml')->__("Mage Cms Index"),
            'Mage_Cms_page' => Mage::helper('adminhtml')->__("Mage Cms Page"),
            'Mage_Catalog_category' => Mage::helper('adminhtml')->__("Mage Catalog Category"),
            'Mage_Catalog_product' => Mage::helper('adminhtml')->__("Mage Catalog Product"),
            'Mage_Customer_account' => Mage::helper('adminhtml')->__("Mage Customer Account"),
            'Mage_Wishlist_index' => Mage::helper('adminhtml')->__("Mage Wishlist Index"),
            'Mage_Customer_address' => Mage::helper('adminhtml')->__("Mage Customer Address"),
            'Mage_Checkout_cart' => Mage::helper('adminhtml')->__("Mage Checkout Cart"),
            'Mage_Checkout_onepage' => Mage::helper('adminhtml')->__("Mage Checkout"),
            'Mage_CatalogSearch_result' => Mage::helper('adminhtml')->__("Mage Catalog Search"),
            'Mage_Tag_product' => Mage::helper('adminhtml')->__("Mage Tag Product")
        );
        return $arrayParams;
    }

    public function get($attributes = NULL) {
        $data = array();
        $arrayParams = array(
			'show',
			'customBlockPosition',
			'blockPosition',
            'name',
            'theme',
            'title',
            'cmsblock',
            'desc_maxchar',
            'pretext',
            'prefix',
            'itemspage',
			'itemsrow',
            'image_width',
            'image_height',
            'swap_image_number',
            'popup_quickview_width',
            'popup_quickview_height',
            'show_pzoom',
            'enable_quickview',
            'enable_swap',
            'menuAssignment',
            'enablejquery',
            'enable_cat',
            'list_cat',
            'enable_feature',
            'enable_bestseller',
            'enable_new',
            'enable_special',
            'enable_mostview',
            'review',
            'enable_wc',
            'enable_sale_icon',
            'enable_new_icon',
            'limit_item',
            'set_time',
            'module_width',
            'module_height',
            'slider_speed',
            'auto_play',
            'blockDisplay',
            'open_link',
            'max_char',
            'cre_main_size',
            'main_width',
            'main_height',
            'tooltip_style',
            'main_imgsize',
            'limit_col',
            'limit_row',
            'show_des',
            'show_price',
            'show_price_without',
            'show_title',
            'show_img',
            'show_public_date',
            'show_btn',
            'online_icon',
            'feature_icon',
            'new_icon',
            'sale_icon',
            'blockDisplay'
        );

        foreach ($arrayParams as $var) {
            $tags = array('ves_tabshome', 'main_slider'); //group config
            foreach ($tags as $tag) {
                if (Mage::getStoreConfig("ves_tabshome_info/$tag/$var") != "") {
                    $data[$var] = Mage::getStoreConfig("ves_tabshome_info/$tag/$var"); //item config
                }
            }
            if (isset($attributes[$var])) {
               $data[$var] = $attributes[$var];
            }
        }
        return $data;
    }

    public function getImageUrl($url = null) {
        return Mage::getSingleton('ves_tabshome/config')->getBaseMediaUrl() . $url;
    }

    /**
     * Encode the mixed $valueToEncode into the JSON format
     *
     * @param mixed $valueToEncode
     * @param  boolean $cycleCheck Optional; whether or not to check for object recursion; off by default
     * @param  array $options Additional options used during encoding
     * @return string
     */
    public function jsonEncode($valueToEncode, $cycleCheck = false, $options = array()) {
        $json = Zend_Json::encode($valueToEncode, $cycleCheck, $options);
        /* @var $inline Mage_Core_Model_Translate_Inline */
        $inline = Mage::getSingleton('core/translate_inline');
        if ($inline->isAllowed()) {
            $inline->setIsJson(true);
            $inline->processResponseBody($json);
            $inline->setIsJson(false);
        }

        return $json;
    }
    
}

?>