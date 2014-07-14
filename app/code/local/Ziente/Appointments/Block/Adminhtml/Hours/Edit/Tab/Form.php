<?php
class Ziente_Appointments_Block_Adminhtml_Hours_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("appointments_form", array("legend"=>Mage::helper("appointments")->__("Item information")));

			
				$DB = Mage::getSingleton('core/resource')->getConnection('core_write');	 
				$query = "SELECT id, name FROM ziente_locations WHERE active = 1 ORDER BY id";
				$result = $DB->fetchAll($query); 


				//$array_location[0] = "Seleccione una Sede";
				for($i=0 ; $i<sizeof($result); $i++){
					$row = $result[$i];	
					$id = $row['id'];
					$location = $row ['name'];
					
					$array_location[$id] = $location;
				}		 

				$fieldset->addField('location_id', 'select', array(
			          'label'     => Mage::helper('appointments')->__('Sede'),
			          'class'     => 'required-entry',
			          'required'  => true,
			          'onchange' => "checkSelectedItem(this.value)",
			          'name'      => 'location_id',
					  'options'   => $array_location ,		  
			      ))->setAfterElementHtml("<script type=\"text/javascript\">
					    function checkSelectedItem(selectElement){
					        var sede = selectElement;
					        //alert(sede);
					    }
					</script>");
				
				
				$value = 1 ;
				//$day = "SELECT id, date FROM ziente_days WHERE location_id = ".$value." ORDER BY id";
				$day = "SELECT id, date FROM ziente_days ORDER BY id";
				$resultday = $DB->fetchAll($day);

				//$array_day[0] = "Seleccione una fecha";
				for($i=0 ; $i<sizeof($resultday); $i++){
					$row = $resultday[$i];	
					$id = $row['id'];
					$date = $row ['date'];
					
					$array_day[$id] = $date;
				}

				$fieldset->addField('day_id', 'select', array(
			          'label'     => Mage::helper('appointments')->__('Day'),
			          'class'     => 'required-entry',
			          'required'  => true,
			          'name'      => 'day_id',
			          'options'   => $array_day ,
			    ));

			    
				//$hour = "SELECT id, time FROM ziente_hours WHERE name is null ORDER BY id";
				$hour = "SELECT id, time FROM ziente_available_hours";
				$result = $DB->fetchAll($hour);

				//$array_hour[0] = "Seleccione una hora";
				for($i=0 ; $i<sizeof($result); $i++){
					$row = $result[$i];	
					$id = $row['id'];
					$hours = $row ['time'];
					
					$array_hour[$id] = $hours;
					
													
				}

			    $fieldset->addField('time', 'select', array(
			          'label'     => Mage::helper('appointments')->__('Time'),
			          'class'     => 'required-entry',
			          'required'  => true,
			          'name'      => 'time',
			          'options'   => $array_hour ,
			    ));

			    
			    /*$customerCollection = Mage::getModel('customer/customer')->getCollection();
				
				$customerCollection->addNameToSelect();
				$customerCollection->addAttributeToSelect(array(
					'entity_id', 'firstname', 'lastname', 'email'
				));

				$array_customer[0] = "Seleccione el cliente";
				foreach ($customerCollection as $customer) {
					$id = $customer->getId();
				    $customer =  $customer->getFirstname() . ' ' . $customer->getLastname() . PHP_EOL;
				    $array_customer[$id] = $customer;
				}*/


				/*
				$collection = Mage::getModel('customer/customer')->getCollection()
				   ->addAttributeToSelect('firstname')
				   ->addAttributeToSelect('lastname')
				   ->addAttributeToSelect('email');

				$array_customer[0] = "Seleccione el cliente";
				foreach ($collection as $item){
					$id = $item["entity_id"];
					$customer =  $item["firstname"] . ' ' . $item["lastname"];
					$array_customer[$id] = $customer;
					//Mage::log($item->getData(),null,'customer.log');   
				}

				
			    $fieldset->addField('name', 'select', array(
			          'label'     => Mage::helper('appointments')->__('Name Customer'),
			          'class'     => 'required-entry',
			          'required'  => true,
			          'name'      => 'name',
			          'options'   => $array_customer,
     		    ));

			    $fieldset->addField('customer_id', 'hidden', array(
			          'label'     => Mage::helper('appointments')->__('customer_id'),
			          'class'     => 'required-entry',
			          'required'  => false,
			          'name'      => 'customer_id',
			    ));

			    $fieldset->addField('email', 'text', array(
			          'label'     => Mage::helper('appointments')->__('Email'),
			          'class'     => 'required-entry',
			          'required'  => true,
			          'name'      => 'email',
			    ));

				$fieldset->addField('reservation_date', 'date', array(
				    'label'     => Mage::helper('appointments')->__('Reservation Date'),                   
				    'name'      => 'reservation_date',
				    'image'     => $this->getSkinUrl('images/grid-cal.gif'),
				    'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
				));

			    $fieldset->addField('notes', 'editor', array(
			          'name'      => 'notes',
			          'label'     => Mage::helper('appointments')->__('Content'),
			          'title'     => Mage::helper('appointments')->__('Content'),
			          'style'     => 'width:400px; height:300px;',
			          'wysiwyg'   => false,
			          'required'  => true,
			    ));
			    */

				if (Mage::getSingleton("adminhtml/session")->getHoursData()){
					$form->setValues(Mage::getSingleton("adminhtml/session")->getHoursData());
					Mage::getSingleton("adminhtml/session")->setHoursData(null);
				} 
				elseif(Mage::registry("hours_data")) {
				    $form->setValues(Mage::registry("hours_data")->getData());
				}
				return parent::_prepareForm();
		}
}
