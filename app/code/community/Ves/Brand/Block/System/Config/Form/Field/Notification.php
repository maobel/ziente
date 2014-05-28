<?php

class Ves_Brand_Block_System_Config_Form_Field_Notification extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$time = intval($element->getValue());
		$time = !empty($time)?$time:time();
		$url  = Mage::getBaseUrl('js');
		$jspath = $url.'ves_brand/form/script.js';
		$csspath = $url.'ves_brand/form/style.css';
		$output = '<link rel="stylesheet" type="text/css" href="'.$csspath.'" />';
		
		//$output .= '<script type="text/javascript" src="' . $url .'ves_brand/jquery.js"></script>'; 		
		$output .= '<script type="text/javascript" src="'.$jspath.'"></script>';
		$format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $timeUpdate = Mage::app()->getLocale()->date()->toString($format);
		
        return $timeUpdate.	$output;
    }
}
?>