<?php

class Ves_Megamenu_Model_System_Config_Source_ListType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'', 'label'=>Mage::helper('ves_megamenu')->__('-- Please select --')),
            array('value'=>'latest', 'label'=>Mage::helper('ves_megamenu')->__('Latest')),
            array('value'=>'best_buy', 'label'=>Mage::helper('ves_megamenu')->__('Best Buy')),
            array('value'=>'most_viewed', 'label'=>Mage::helper('ves_megamenu')->__('Most Viewed')),
            array('value'=>'most_reviewed', 'label'=>Mage::helper('ves_megamenu')->__('Most Reviewed')),
            array('value'=>'top_rated', 'label'=>Mage::helper('ves_megamenu')->__('Top Rated')),
            array('value'=>'attribute', 'label'=>Mage::helper('ves_megamenu')->__('Featured Product'))
        );
    }    
}
