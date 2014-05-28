<?php
 
class Ziente_Appointments_Model_Mysql4_Appointments_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('appointments/appointments');
    }
}