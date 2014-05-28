<?php
/**
 * Tempcp for Magento
 *
 * @category   Ves
 * @package    Ves_Tempcp
 * @copyright  Copyright (c) 2009 Ves GmbH & Co. KG <magento@Ves.de>
 */

/**
 * Tempcp for Magento
 *
 * @category   Ves
 * @package    Ves_Tempcp
 * @author     Landofcoder <landofcoder@gmail.com>
 */
class Ves_Tempcp_Model_Theme extends Mage_Core_Model_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('ves_tempcp/theme');
    }
    public function loadThemeByGroup($group = "", $enable_cache = false){
        $store_id = Mage::app()->getStore()->getStoreId();
        $theme_id = 0;
        if($enable_cache){
            $cache = Mage::app()->getCache();
            if(!$theme_id = $cache->load( $group."_".$store_id )){
                $theme_id = $this->getResource()->loadThemeByGroup( $group, $store_id );
                $cache->save($theme_id, $group."_".$store_id, array($group,"theme_".$theme_id), 60*60);
            }
        }else{
            $theme_id = $this->getResource()->loadThemeByGroup( $group, $store_id );
        }
        
        if(!empty($theme_id)){
           return $this->load($theme_id);
        }

        return false;
    }
    public function checkExistsByGroup($group = ""){
    	if($group){
    		$data = $this->getResource()->loadThemeByGroup( $group );
	        if($data && count($data) > 0){
	        	return true;
	        }else{
	        	return false;
	        }
    	}
		
        return true;
	}

    
}
