<?php
class Ziente_Appointments_Block_Adminhtml_Days_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		
		$fieldset = $form->addFieldset("appointments_form", array("legend"=>Mage::helper("appointments")->__("Item information")));

		$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
					Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
				);

		$fieldset->addField('date', 'date', array(
			'label'        => Mage::helper('appointments')->__('Date'),
			'name'         => 'date',
			"class" => "required-entry",
			'type' => 'datetime',
			"required" => true,
			'image'        => $this->getSkinUrl('images/grid-cal.gif'),
			'format'    => $this->escDates(),
		));	

		$fieldset->addField('location_id', 'select', array(
			'label'     => Mage::helper('appointments')->__('Location'),
			'values'   => Ziente_Appointments_Block_Adminhtml_Days_Grid::getValueArray8(),
			'name' => 'location_id',					
			"class" => "required-entry",
			"required" => true,
		));
		

		if (Mage::getSingleton("adminhtml/session")->getDaysData())
		{
			$form->setValues(Mage::getSingleton("adminhtml/session")->getDaysData());
			Mage::getSingleton("adminhtml/session")->setDaysData(null);
		} 
		elseif(Mage::registry("days_data")) {
		    $form->setValues(Mage::registry("days_data")->getData());
		}
		return parent::_prepareForm();
	}

	private function escDates() {
         return 'yyyy-MM-dd';   
    }
}
