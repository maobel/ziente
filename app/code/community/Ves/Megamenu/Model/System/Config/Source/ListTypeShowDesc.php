<?php

class Ves_Megamenu_Model_System_Config_Source_ListTypeShowDesc
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'', 'label'=>Mage::helper('ves_megamenu')->__('No Description')),
            array('value'=>'desc', 'label'=>Mage::helper('ves_megamenu')->__('Description only')),
            array('value'=>'desc-readmore', 'label'=>Mage::helper('ves_megamenu')->__('Description with Readmore'))
        );
    }    
}
