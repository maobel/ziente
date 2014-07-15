<?php
	
class Ziente_Appointments_Block_Adminhtml_Hours_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = "id";
		$this->_blockGroup = "appointments";
		$this->_controller = "adminhtml_hours";
		$this->_updateButton("save", "label", Mage::helper("appointments")->__("Save Item"));
		$this->_updateButton("delete", "label", Mage::helper("appointments")->__("Delete Item"));

		$this->_addButton("saveandcontinue", array(
			"label"     => Mage::helper("appointments")->__("Save And Continue Edit"),
			"onclick"   => "saveAndContinueEdit()",
			"class"     => "save",
		), -100);


		$this->_formScripts[] = "

					function saveAndContinueEdit(){
						editForm.submit($('edit_form').action+'back/edit/');
					}
				";
	}

	public function getHeaderText()
	{
		if( Mage::registry("hours_data") && Mage::registry("hours_data")->getId() ){
		    return Mage::helper("appointments")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("hours_data")->getId()));
		} 
		else{
		     return Mage::helper("appointments")->__("Add Item");
		}
	}
}