<?php

class Ves_TabsHome_Model_System_Config_Source_ListTooltip {

    public function toOptionArray() {
        return array(
            array('value' => "vs-none", 'label' => Mage::helper('adminhtml')->__('None')),
            array('value' => "vs-tooltip", 'label' => Mage::helper('adminhtml')->__('Tooltip')),
            array('value' => "vs-tipbox", 'label' => Mage::helper('adminhtml')->__('Tipbox'))
        );
    }

}
