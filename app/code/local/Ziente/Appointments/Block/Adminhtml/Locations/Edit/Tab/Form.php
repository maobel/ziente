<?php
class Ziente_Appointments_Block_Adminhtml_Locations_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("appointments_form", array("legend"=>Mage::helper("appointments")->__("Item information")));
$fieldset->addField("name", "text", array(
						"label" => Mage::helper("appointments")->__("Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "name",
						));
				
						$fieldset->addField("address", "text", array(
						"label" => Mage::helper("appointments")->__("Address"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "address",
						));

						
									
						 $fieldset->addField('city', 'select', array(
						'label'     => Mage::helper('appointments')->__('City'),
						'values'   => Ziente_Appointments_Block_Adminhtml_Locations_Grid::getValueArray2(),
						'name' => 'city',					
						"class" => "required-entry",
						"required" => true,
						));				
						 $fieldset->addField('country', 'select', array(
						'label'     => Mage::helper('appointments')->__('Country'),
						'values'   => Ziente_Appointments_Block_Adminhtml_Locations_Grid::getValueArray3(),
						'name' => 'country',					
						"class" => "required-entry",
						"required" => true,
						));
						$fieldset->addField("phone", "text", array(
						"label" => Mage::helper("appointments")->__("Phone"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "phone",
						));
					
						$fieldset->addField("email", "text", array(
						"label" => Mage::helper("appointments")->__("Email"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "email",
						));
									
						 $fieldset->addField('active', 'select', array(
						'label'     => Mage::helper('appointments')->__('Active'),
						'values'   => Ziente_Appointments_Block_Adminhtml_Locations_Grid::getValueArray6(),
						'name' => 'active',
						));

				if (Mage::getSingleton("adminhtml/session")->getLocationsData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getLocationsData());
					Mage::getSingleton("adminhtml/session")->setLocationsData(null);
				} 
				elseif(Mage::registry("locations_data")) {
				    $form->setValues(Mage::registry("locations_data")->getData());
				}
				return parent::_prepareForm();
		}
}
