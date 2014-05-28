<?php

class Ves_Megamenu_Model_System_Config_Backend_Megamenu_Checkvalue extends Mage_Core_Model_Config_Data
{

    protected function _beforeSave(){
        $value=$this->getValue();
        	if ((!is_numeric($value) && !empty($value)) || $value < 0) {				
        	    throw new Exception(Mage::helper('ves_megamenu')->__($this->getField_config()->label . ': Value must be numeric.'));
        	}
        return $this;
    }

}
