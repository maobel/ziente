<?php
class Ziente_Appointments_Model_Mysql4_Locations extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("appointments/locations", "id");
    }
}