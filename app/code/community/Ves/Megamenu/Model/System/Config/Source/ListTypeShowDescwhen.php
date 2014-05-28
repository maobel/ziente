<?php

class Ves_Megamenu_Model_System_Config_Source_ListTypeShowDescwhen
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'always', 'label'=>Mage::helper('ves_megamenu')->__('Always')),
            array('value'=>'mouseover', 'label'=>Mage::helper('ves_megamenu')->__('Mouse Over Image'))
        );
    }    
}
