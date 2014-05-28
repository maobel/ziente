<?php


class Ves_Megamenu_Model_System_Config_Source_ListThumbnailMode
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'none', 'label'=>Mage::helper('ves_megamenu')->__('No')),
            array('value'=>'crop', 'label'=>Mage::helper('ves_megamenu')->__('Use Resizing Image'))
        );
    }    
}
