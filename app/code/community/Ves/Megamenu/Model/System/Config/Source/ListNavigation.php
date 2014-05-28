<?php


class Ves_Megamenu_Model_System_Config_Source_ListNavigation
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'', 'label'=>Mage::helper('ves_megamenu')->__('No')),
            array('value'=>'number', 'label'=>Mage::helper('ves_megamenu')->__('Number')),
            array('value'=>'thumbs', 'label'=>Mage::helper('ves_megamenu')->__('Thumbnails'))
        );
    }    
}
