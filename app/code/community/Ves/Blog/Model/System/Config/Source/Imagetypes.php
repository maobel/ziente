<?php

class Ves_Blog_Model_System_Config_Source_Imagetypes
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'l', 'label'=>Mage::helper('ves_blog')->__('Large')." (".Mage::getStoreConfig('ves_blog/general_setting/large_imagesize') .")" ),
            array('value'=>'m', 'label'=>Mage::helper('ves_blog')->__('Medium')." (".Mage::getStoreConfig('ves_blog/general_setting/medium_imagesize') .")"),
            array('value'=>'s', 'label'=>Mage::helper('ves_blog')->__('Small')." (".Mage::getStoreConfig('ves_blog/general_setting/small_imagesize') .")"),

        );
    }    
}
