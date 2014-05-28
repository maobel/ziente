<?php


class Ves_Megamenu_Model_System_Config_Source_ListContainer
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'0', 'label'=>Mage::helper('ves_megamenu')->__('Fit to main image')),
            array('value'=>'1', 'label'=>Mage::helper('ves_megamenu')->__('Full size'))
        );
    }    
}
