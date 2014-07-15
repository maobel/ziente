<?php

class Ziente_Appointments_Block_Adminhtml_Locations_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
			parent::__construct();
			$this->setId("locationsGrid");
			$this->setDefaultSort("id");
			$this->setDefaultDir("DESC");
			$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
			$collection = Mage::getModel("appointments/locations")->getCollection();
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

		$this->addColumn("name", array(
		"header" => Mage::helper("appointments")->__("Name"),
		"index" => "name",
		));
        
		$this->addColumn("address", array(
		"header" => Mage::helper("appointments")->__("Address"),
		"index" => "address",
		));

		$this->addColumn('city', array(
		'header' => Mage::helper('appointments')->__('City'),
		'index' => 'city',
		'type' => 'options',
		'options'=>Ziente_Appointments_Block_Adminhtml_Locations_Grid::getOptionArray2(),				
		));
		
		$this->addColumn('country', array(
		'header' => Mage::helper('appointments')->__('Country'),
		'index' => 'country',
		'type' => 'options',
		'options'=>Ziente_Appointments_Block_Adminhtml_Locations_Grid::getOptionArray3(),				
		));
				
		$this->addColumn("phone", array(
		"header" => Mage::helper("appointments")->__("Phone"),
		"index" => "phone",
		));
		$this->addColumn("email", array(
		"header" => Mage::helper("appointments")->__("Email"),
		"index" => "email",
		));

		$this->addColumn('active', array(
		'header' => Mage::helper('appointments')->__('Active'),
		'index' => 'active',
		'type' => 'options',
		'options'=>Ziente_Appointments_Block_Adminhtml_Locations_Grid::getOptionArray6(),				
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
		$this->getMassactionBlock()->addItem('remove_locations', array(
				 'label'=> Mage::helper('appointments')->__('Remove Locations'),
				 'url'  => $this->getUrl('*/adminhtml_locations/massRemove'),
				 'confirm' => Mage::helper('appointments')->__('Are you sure?')
			));
		return $this;
	}
		
	static public function getOptionArray2()
	{
		$data_array=array(); 
		$data_array['Bogotá']='Bogotá';
		return($data_array);
	}

	static public function getValueArray2()
	{
		$data_array=array();
		foreach(Ziente_Appointments_Block_Adminhtml_Locations_Grid::getOptionArray2() as $k=>$v){
   			$data_array[]=array('value'=>$k,'label'=>$v);		
		}
		return($data_array);
	}
	
	static public function getOptionArray3()
	{
	    $data_array=array(); 
		$data_array['CO']='CO';
	    return($data_array);
	}

	static public function getValueArray3()
	{
	    $data_array=array();
		foreach(Ziente_Appointments_Block_Adminhtml_Locations_Grid::getOptionArray3() as $k=>$v){
			$data_array[]=array('value'=>$k,'label'=>$v);		
		}
	    return($data_array);
	}
	
	static public function getOptionArray6()
	{
        $data_array=array(); 
		$data_array[1]='Habilitado';
		$data_array[0]='Deshabilitado';
        return($data_array);
	}

	static public function getValueArray6()
	{
        $data_array=array();
		foreach(Ziente_Appointments_Block_Adminhtml_Locations_Grid::getOptionArray6() as $k=>$v){
           $data_array[]=array('value'=>$k,'label'=>$v);		
		}
        return($data_array);
	}
}
