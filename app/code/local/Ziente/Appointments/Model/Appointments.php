<?php

class Ziente_Appointments_Model_Appointments extends Mage_Core_Model_Abstract
{
 	public function _construct()
    {
        parent::_construct();
        $this->_init('appointments/appointments');
    }

}
