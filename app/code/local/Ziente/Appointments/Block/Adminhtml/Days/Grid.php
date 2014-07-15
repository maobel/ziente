<?php

class Ziente_Appointments_Block_Adminhtml_Days_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct()
	{
		parent::__construct();
		$this->setId("daysGrid");
		$this->setDefaultSort("id");
		$this->setDefaultDir("DESC");
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel("appointments/days")->getCollection();
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
        
		$this->addColumn('date', array(
			'header'    => Mage::helper('appointments')->__('Date'),
			'index'     => 'date',
		));
		
		$this->addColumn('location_id', array(
			'header' => Mage::helper('appointments')->__('Location'),
			'index' => 'location_id',
			'type' => 'options',
			'options'=>Ziente_Appointments_Block_Adminhtml_Days_Grid::getOptionArray8(),				
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
		$this->getMassactionBlock()->addItem('remove_days', array(
				 'label'=> Mage::helper('appointments')->__('Remove Days'),
				 'url'  => $this->getUrl('*/adminhtml_days/massRemove'),
				 'confirm' => Mage::helper('appointments')->__('Are you sure?')
			));
		return $this;
	}
		
	static public function getOptionArray8()
	{
        $data_array=array(); 
		
		$DB = Mage::getSingleton('core/resource')->getConnection('core_write');	 

		$query = "SELECT id, name FROM ziente_locations ORDER BY name";
		$result = $DB->fetchAll($query); 

		//$array_region[0] = "Seleccione la sede";
		for($i=0 ; $i<sizeof($result); $i++){
			$row = $result[$i];	
			$id = $row['id'];
			$name = $row['name'];
			
			$array_region[$id] = $name;
		}		  
 
        return($array_region);
	}

	static public function getValueArray8()
	{
        $data_array=array();
		foreach(Ziente_Appointments_Block_Adminhtml_Days_Grid::getOptionArray8() as $k=>$v){
           $data_array[]=array('value'=>$k,'label'=>$v);		
		}
        return($data_array);
	}
}