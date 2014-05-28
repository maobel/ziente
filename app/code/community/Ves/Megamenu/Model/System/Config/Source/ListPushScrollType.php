<?php

class Ves_Megamenu_Model_System_Config_Source_ListPushScrollType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'push', 'label'=>Mage::helper('ves_megamenu')->__('Push')),
            array('value'=>'scroll', 'label'=>Mage::helper('ves_megamenu')->__('Scroll'))
        );
    }    
}
