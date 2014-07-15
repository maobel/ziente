<?php

class Ziente_Appointments_Block_Adminhtml_Hours extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{
		$this->_controller = "adminhtml_hours";
		$this->_blockGroup = "appointments";
		$this->_headerText = Mage::helper("appointments")->__("Hours Manager");
		$this->_addButtonLabel = Mage::helper("appointments")->__("Add New Item");
		parent::__construct();
	}
}