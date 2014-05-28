<?php
class Ves_Megamenu_Model_System_Config_Source_ListDurationPositionType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'left', 'label'=>Mage::helper('ves_megamenu')->__('left')),
            array('value'=>'right', 'label'=>Mage::helper('ves_megamenu')->__('right')),
            array('value'=>'top', 'label'=>Mage::helper('ves_megamenu')->__('top')),
            array('value'=>'bottom', 'label'=>Mage::helper('ves_megamenu')->__('bottom')),
            array('value'=>'left-right', 'label'=>Mage::helper('ves_megamenu')->__('left-right')),
            array('value'=>'left-top', 'label'=>Mage::helper('ves_megamenu')->__('left-top')),
            array('value'=>'left-bottom', 'label'=>Mage::helper('ves_megamenu')->__('left-bottom')),
            array('value'=>'right-top', 'label'=>Mage::helper('ves_megamenu')->__('right-top')),
            array('value'=>'right-bottom', 'label'=>Mage::helper('ves_megamenu')->__('right-bottom'))
        );
    }    
}
