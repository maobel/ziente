<?php

class Ves_TreeMenu_Block_System_Config_Form_Field_Notification extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $time = intval($element->getValue());
        $time = !empty($time) ? $time : time();
        $url = Mage::getBaseUrl('js');
        $csspath = $url . 'ves_treemenu/form/style.css';
        $output = '<link rel="stylesheet" type="text/css" href="' . $csspath . '" />';
        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $timeUpdate = Mage::app()->getLocale()->date()->toString($format);

        return $timeUpdate .$output;
    }

}

?>