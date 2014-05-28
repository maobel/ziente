<?php

class Ves_Blog_Model_System_Config_Source_ListSourceType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'latest', 'label'=>Mage::helper('ves_blog')->__('Latest Blogs') ),
            array('value'=>'hit', 'label'=>Mage::helper('ves_blog')->__('Most Read') ),

        );
    }    
}
