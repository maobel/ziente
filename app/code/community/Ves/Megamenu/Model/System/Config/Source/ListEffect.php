<?php


class Ves_Megamenu_Model_System_Config_Source_ListEffect
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'linear', 'label'=>Mage::helper('ves_megamenu')->__('Linear')),
            array('value'=>'quadOut', 'label'=>Mage::helper('ves_megamenu')->__('Medium to Slow')),
            array('value'=>'cubicOut', 'label'=>Mage::helper('ves_megamenu')->__('Fast to Slow')),
            array('value'=>'quartOut', 'label'=>Mage::helper('ves_megamenu')->__('Very Fast to Slow')),
            array('value'=>'quintOut', 'label'=>Mage::helper('ves_megamenu')->__('Uber Fast to Slow')),
            array('value'=>'expoOut', 'label'=>Mage::helper('ves_megamenu')->__('Exponential Speed')),
            array('value'=>'elasticOut', 'label'=>Mage::helper('ves_megamenu')->__('Elastic')),
            array('value'=>'backIn', 'label'=>Mage::helper('ves_megamenu')->__('Back In')),
            array('value'=>'backOut', 'label'=>Mage::helper('ves_megamenu')->__('Back Out')),
            array('value'=>'backInOut', 'label'=>Mage::helper('ves_megamenu')->__('Back In and Out')),
            array('value'=>'bounceOut', 'label'=>Mage::helper('ves_megamenu')->__('Bouncing')),
        );
    }    
}
