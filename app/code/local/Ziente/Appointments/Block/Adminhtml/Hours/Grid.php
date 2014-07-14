<?php

class Ziente_Appointments_Block_Adminhtml_Hours_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("hoursGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("appointments/hours")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("appointments")->__("ID"),
				"align" =>"right",
				"width" => "50px",
				"index" => "id",
				));

				$DB = Mage::getSingleton('core/resource')->getConnection('core_write');	 
				//$query = "SELECT id , date, location_id FROM ziente_days ORDER BY id";
				$query = "SELECT h.id, h.time, h.day_id, d.location_id, d.date, l.name AS location, h.name, h.email, h.reservation_date
					FROM ziente_hours h 
					INNER JOIN ziente_days d ON d.id = h.day_id
					INNER JOIN ziente_locations l ON l.id = d.location_id
					ORDER BY h.id";
				$result = $DB->fetchAll($query); 

				for($i=0 ; $i<sizeof($result); $i++){
					$row = $result[$i];	
					$id = $row['id'];
					$date = $row['date'];
					$array_date[$id] = $date;
				}
				  
			    $this->addColumn('day_id', array(
			          'header'    => Mage::helper('appointments')->__('Date'),
			          'align'     =>'left',
			          "width" => "50px",
					  'type'  => 'options',
			          'index'     => 'day_id',
					  'options' => $array_date,
			    ));

				$this->addColumn("time", array(
					"header" => Mage::helper("appointments")->__("Hora"),
					"align" =>"right",
					"width" => "50px",
					"index" => "time",
				));

				$this->addColumn("location", array(
					"header" => Mage::helper("appointments")->__("Location"),
					"align" =>"right",
					"width" => "50px",
					"index" => "location",
				));

				$this->addColumn("name", array(
					"header" => Mage::helper("appointments")->__("Name"),
					"align" =>"right",
					"width" => "50px",
					"index" => "name",
				));

				$this->addColumn("email", array(
					"header" => Mage::helper("appointments")->__("Email"),
					"align" =>"right",
					"width" => "50px",
					"index" => "email",
				));

				$this->addColumn("reservation_date", array(
					"header" => Mage::helper("appointments")->__("Reservation Date"),
					"align" =>"right",
					"width" => "50px",
					"index" => "reservation_date",
				));
                
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_hours', array(
					 'label'=> Mage::helper('appointments')->__('Remove Hours'),
					 'url'  => $this->getUrl('*/adminhtml_hours/massRemove'),
					 'confirm' => Mage::helper('appointments')->__('Are you sure?')
				));
			return $this;
		}
			

}