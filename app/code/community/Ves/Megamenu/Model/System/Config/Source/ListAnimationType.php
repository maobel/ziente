<?php
class Lof_SlidingCaption_Model_System_Config_Source_ListAnimationType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'fade', 'label'=>Mage::helper('lof_slidingcaption')->__('fade')),
            array('value'=>'cover', 'label'=>Mage::helper('lof_slidingcaption')->__('cover')),
            array('value'=>'uncover', 'label'=>Mage::helper('lof_slidingcaption')->__('uncover')),
            array('value'=>'scrollUp', 'label'=>Mage::helper('lof_slidingcaption')->__('scrollUp')),
            array('value'=>'scrollDown', 'label'=>Mage::helper('lof_slidingcaption')->__('scrollDown')),
            array('value'=>'scrollLeft', 'label'=>Mage::helper('lof_slidingcaption')->__('scrollLeft')),
            array('value'=>'scrollRight', 'label'=>Mage::helper('lof_slidingcaption')->__('scrollRight')),
            array('value'=>'slideX', 'label'=>Mage::helper('lof_slidingcaption')->__('slideX')),
            array('value'=>'slideY', 'label'=>Mage::helper('lof_slidingcaption')->__('slideY')),
            array('value'=>'shuffle', 'label'=>Mage::helper('lof_slidingcaption')->__('shuffle')),
            array('value'=>'turnUp', 'label'=>Mage::helper('lof_slidingcaption')->__('turnUp')),
            array('value'=>'turnDown', 'label'=>Mage::helper('lof_slidingcaption')->__('turnDown')),
            array('value'=>'turnLeft', 'label'=>Mage::helper('lof_slidingcaption')->__('turnLeft')),
            array('value'=>'turnRight', 'label'=>Mage::helper('lof_slidingcaption')->__('turnRight')),
            array('value'=>'zoom', 'label'=>Mage::helper('lof_slidingcaption')->__('zoom')),
            array('value'=>'fadeZoom', 'label'=>Mage::helper('lof_slidingcaption')->__('fadeZoom')),
            array('value'=>'blindX', 'label'=>Mage::helper('lof_slidingcaption')->__('blindX')),
            array('value'=>'blindY', 'label'=>Mage::helper('lof_slidingcaption')->__('blindY')),
            array('value'=>'blindZ', 'label'=>Mage::helper('lof_slidingcaption')->__('blindZ')),
            array('value'=>'growX', 'label'=>Mage::helper('lof_slidingcaption')->__('growX')),
            array('value'=>'growY', 'label'=>Mage::helper('lof_slidingcaption')->__('growY')),
            array('value'=>'curtainX', 'label'=>Mage::helper('lof_slidingcaption')->__('curtainX')),
            array('value'=>'curtainY', 'label'=>Mage::helper('lof_slidingcaption')->__('curtainY')),
            array('value'=>'toss', 'label'=>Mage::helper('lof_slidingcaption')->__('toss')),
            array('value'=>'wipe', 'label'=>Mage::helper('lof_slidingcaption')->__('wipe'))
        );
    }    
}
