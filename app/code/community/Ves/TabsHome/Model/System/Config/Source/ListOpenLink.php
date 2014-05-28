<?php

class Ves_TabsHome_Model_System_Config_Source_ListOpenLink {

    public function toOptionArray() {
        return array(
            array('value' => "_blank", 'label' => Mage::helper('adminhtml')->__('New Window')),
            array('value' => "_self", 'label' => Mage::helper('adminhtml')->__('Same Window')),
        );
    }

}
