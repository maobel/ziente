<?php

class Ves_ProductCarousel_Model_System_Config_Source_ListType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'', 'label'=>Mage::helper('ves_productcarousel')->__('-- Please select --')),
            array('value'=>'latest', 'label'=>Mage::helper('ves_productcarousel')->__('Latest')),
            array('value'=>'sale', 'label'=>Mage::helper('ves_productcarousel')->__('On Sales')),
            array('value'=>'best_buy', 'label'=>Mage::helper('ves_productcarousel')->__('Best Buy')),
            array('value'=>'most_viewed', 'label'=>Mage::helper('ves_productcarousel')->__('Most Viewed')),
            array('value'=>'top_rated', 'label'=>Mage::helper('ves_productcarousel')->__('Top Rated')),
            array('value'=>'featured', 'label'=>Mage::helper('ves_productcarousel')->__('Featured Product'))
        );
    }    
}
