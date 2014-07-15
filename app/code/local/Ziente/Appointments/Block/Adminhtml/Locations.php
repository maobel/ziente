<?php

class Ziente_Appointments_Block_Adminhtml_Locations extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{
		$this->_controller = "adminhtml_locations";
		$this->_blockGroup = "appointments";
		$this->_headerText = Mage::helper("appointments")->__("Locations Manager");
		$this->_addButtonLabel = Mage::helper("appointments")->__("Add New Item");
		parent::__construct();
	}
}