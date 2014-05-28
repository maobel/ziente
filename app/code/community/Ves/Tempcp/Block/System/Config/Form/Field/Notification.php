<?php

class Ves_Tempcp_Block_System_Config_Form_Field_Notification extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {

        $url = Mage::getBaseUrl('js');
        $jspath = $url . 'venustheme/ves_tempcp/form/script.js';
        $csspath = $url . 'venustheme/ves_tempcp/form/style.css';
        $output = '<link rel="stylesheet" type="text/css" href="' . $csspath . '" />';

        $output .= '<script type="text/javascript" src="' . $url .'venustheme/ves_tempcp/jquery.js'. '"></script>';
        $output .= '<script type="text/javascript" src="' . $jspath . '"></script>';
        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $timeUpdate = Mage::app()->getLocale()->date(intval($element->getValue()))->toString($format);

        return $timeUpdate . $output;
    }

}

?>