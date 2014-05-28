<?php

class Ves_TabsHome_Model_System_Config_Source_ListOrderBy {

    public function toOptionArray() {
        return array(
            array('value' => "p.date_add", 'label' => Mage::helper('adminhtml')->__('Date Add')),
            array('value' => "p.date_add DESC", 'label' => Mage::helper('adminhtml')->__('Date Add DESC')),
            array('value' => "name", 'label' => Mage::helper('adminhtml')->__('Name')),
            array('value' => "name DESC", 'label' => Mage::helper('adminhtml')->__('Name DESC')),
            array('value' => "quantity", 'label' => Mage::helper('adminhtml')->__('Quantity')),
            array('value' => "quantity DESC", 'label' => Mage::helper('adminhtml')->__('Quantity DESC')),
            array('value' => "p.price", 'label' => Mage::helper('adminhtml')->__('Price')),
            array('value' => "p.price DESC", 'label' => Mage::helper('adminhtml')->__('Price DESC'))
        );
    }

}
