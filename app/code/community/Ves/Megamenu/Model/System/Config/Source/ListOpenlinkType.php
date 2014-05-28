<?php

class Ves_Megamenu_Model_System_Config_Source_ListOpenlinkType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'_blank', 'label'=>Mage::helper('ves_megamenu')->__('New Window')),
            array('value'=>'_self', 'label'=>Mage::helper('ves_megamenu')->__('Same Window'))
        );
    }    
}
