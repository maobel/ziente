<?php
 
class Ziente_Appointments_Model_Mysql4_Appointments extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('appointments/appointments', 'id');
    }
}