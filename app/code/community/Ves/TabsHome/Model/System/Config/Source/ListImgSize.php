<?php

class Ves_TabsHome_Model_System_Config_Source_ListImgSize {

    public function toOptionArray() {
        return array(
            array('value' => "home", 'label' => Mage::helper('adminhtml')->__('home(131x147)')),
            array('value' => "large", 'label' => Mage::helper('adminhtml')->__('large(244x274)')),
            array('value' => "vs_slide", 'label' => Mage::helper('adminhtml')->__('vs_slide(925x408)')),
            array('value' => "medium", 'label' => Mage::helper('adminhtml')->__('medium(70x80)')),
            array('value' => "small", 'label' => Mage::helper('adminhtml')->__('small(60x67)')),
            array('value' => "thickbox", 'label' => Mage::helper('adminhtml')->__('thickbox(300x337)')),
        );
    }

}
