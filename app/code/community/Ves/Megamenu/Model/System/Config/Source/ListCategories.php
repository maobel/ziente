<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Lof * @package     lof_slidingcaption
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Position config model
 *
 * @category   Lof
 * @package     lof_slidingcaption
 * @author    
 */
class Ves_Megamenu_Model_System_Config_Source_ListCategories
{
	public function toOptionArray(){
	
		$_model = Mage::getModel('ves_megamenu/megamenu');
		$params = $_model->getMegaMenus( null, false  );
 
    	$option = array();
    	if(!empty($params)){
    		foreach($params as $key=>$label){
    			$tmp = array();
    			$tmp["value"] = $label['megamenu_id'];
			
				// $repeat = ( $label['level'] - 1 >= 0 ) ? $label['level'] - 1 : 0;
    			$tmp["label"] = str_repeat("--",$label['level'] ).$label['title']." (ID: ".$label['megamenu_id'].")";
				
				 
    			$option[] = $tmp;
    		}
    	}
		return $option;
	}
}