<?php
class Ziente_Appointments_Model_Mysql4_Hours extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("appointments/hours", "id");
    }
}