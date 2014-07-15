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
		$collection = Mage::getModel('appointments/hours')->getCollection();
		$collection->getSelect()->join( array('days'=> ziente_days), 'days.id = main_table.day_id', array('days.location_id'));
		$collection->getSelect()->join( array('locations'=> ziente_locations), 'locations.id = days.location_id', array('alias' => 'locations.name'));
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn("id", array(
			"header" => Mage::helper("appointments")->__("ID"),
			"align" =>"right",
			"width" => "50px",
			"type" => "number",
			"index" => "id",
		));
		  
	    $this->addColumn('day_id', array(
	          'header'    => Mage::helper('appointments')->__('Date'),
	          'align'     =>'center',
	          "width" => "50px",
			  'type'  => 'options',
	          'index'     => 'day_id',
			  'options'=>Ziente_Appointments_Block_Adminhtml_Hours_Grid::getOptionArray9(),				
	    ));

		$this->addColumn("time", array(
			"header" => Mage::helper("appointments")->__("Hora"),
			"align" =>"center",
			"width" => "50px",
			"index" => "time",
		));

		$this->addColumn('alias', array(
			'header' => Mage::helper('appointments')->__('Location'),
			"align" =>"center",
			"width" => "50px",
			'index' => 'location_id',
			'type' => 'options',
			'options'=>Ziente_Appointments_Block_Adminhtml_Days_Grid::getOptionArray8(),				
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

		$this->addColumn("phone", array(
			"header" => Mage::helper("appointments")->__("Phone"),
			"align" =>"right",
			"width" => "50px",
			"index" => "phone",
		));

		$this->addColumn("notes", array(
			"header" => Mage::helper("appointments")->__("Notes"),
			"align" =>"right",
			"width" => "50px",
			"index" => "notes",
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

	static public function getOptionArray9()
	{
        $data_array=array(); 
		
		$DB = Mage::getSingleton('core/resource')->getConnection('core_write');	

		$query2 = "SELECT `id`, `date` FROM ziente_days";
		$result2 = $DB->fetchAll($query2); 

		for($i=0 ; $i<sizeof($result2); $i++){
			$row = $result2[$i];	
			$id = $row['id'];
			$date = $row['date'];

			$array_date[$id] = $date;
		}	  
 
        return($array_date);
	}

	static public function getValueArray9()
	{
        $data_array=array();
		foreach(Ziente_Appointments_Block_Adminhtml_Hours_Grid::getOptionArray9() as $k=>$v){
           $data_array[]=array('value'=>$k,'label'=>$v);		
		}
        return($data_array);
	}
}