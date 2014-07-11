<?php

class Zientedev_Web_Block_Adminhtml_Web_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('webGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
     $collection = Mage::getModel('appointments/hours')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();

     /* $DB = Mage::getSingleton('core/resource')->getConnection('core_write');  
      $query = "SELECT ziente_days.date, ziente_hours.time, ziente_hours.name,reservation_date, ziente_locations.name FROM ziente_hours
LEFT JOIN ziente_days ON ziente_hours.day_id = ziente_days.id
LEFT JOIN ziente_locations ON ziente_days.location_id = ziente_locations.id";
      $result = $DB->fetchAll($query);

      $collection = $result;
      $this->setCollection($collection);
      return parent::_prepareCollection();
*/

      
  }

  protected function _prepareColumns()
  {
     $this->addColumn('id', array(
          'header'    => Mage::helper('web')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));

      $this->addColumn('day_id', array(
          'header'    => Mage::helper('web')->__('Day'),
          'align'     =>'left',
          'index'     => 'day_id',
      ));

   /* $DB = Mage::getSingleton('core/resource')->getConnection('core_write');  
    $query = "SELECT name FROM ziente_locations JOIN ziente_days ON ziente_locations.id = ziente_days.location_id";
    $result = $DB->fetchAll($query); 

    for($i=0 ; $i<sizeof($result); $i++){
      $row = $result[$i]; 
      
      $name = $row['name'];
      
      $array_sede[$name] = $name;
      
    }     
    
    $this->addColumn('sede', array(
          'header'    => Mage::helper('web')->__('Sede'),
          'align'     =>'left',
          'type'  => 'options',
          'index'     => "$name",
          'options' => $array_sede,
      
      ));
    */
      $this->addColumn('time', array(
          'header'    => Mage::helper('web')->__('Time'),
          'align'     =>'left',
          'index'     => 'time',
      ));

     /* $this->addColumn('customer_id', array(
          'header'    => Mage::helper('web')->__('Customer'),
          'align'     =>'left',
          'index'     => 'customer_id',
      ));

      $this->addColumn('notes', array(
          'header'    => Mage::helper('web')->__('Notes'),
          'align'     =>'left',
          'index'     => 'notes',
      ));
      
      */

      $this->addColumn('name', array(
          'header'    => Mage::helper('web')->__('Customer'),
          'align'     =>'left',
          'index'     => 'name',
      ));

      

      $this->addColumn('reservation_date', array(
          'header'    => Mage::helper('web')->__('Date'),
          'align'     =>'left',
          'index'     => 'reservation_date',
      ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('web')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  

      $this->addColumn('status', array(
          'header'    => Mage::helper('web')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  */
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('web')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('web')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('web')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('web')->__('XML'));
    
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('web_id');
        $this->getMassactionBlock()->setFormFieldName('web');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('web')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('web')->__('Are you sure?')
        ));

        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
