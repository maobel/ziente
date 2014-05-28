<?php
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

class Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Block_Grid 
	extends Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Abstract_Grid
{

	protected $_isExport = true;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('staticBlocksGrid');
		$this->setDefaultSort('block_id');
		$this->setDefaultDir('desc');
	}

	/**
	 */
	protected function _prepareColumns()
	{

		$this->addColumn('block_id', array(
				'header'    => Mage::helper('ves_tempcp')->__('Block_Id'),
				'width'     =>'50px',
				'index'     => 'block_id'
		));

		$this->addColumn('title', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Title'),
				'width'     =>'50px',
				'index'     => 'title'
		));

		$this->addColumn('content', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Content'),
				'width'     =>'50px',
				'index'     => 'content',
            	'frame_callback' => array($this, 'entityDecode')
		));

		$this->addColumn('identifier', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Identifier'),
				'width'     =>'50px',
				'index'     => 'identifier'
		));

		$this->addColumn('creation_time', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Creation_Time'),
				'width'     =>'50px',
				'index'     => 'creation_time'
		));

		$this->addColumn('update_time', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Update_Time'),
				'width'     =>'50px',
				'index'     => 'update_time'
		));

		$this->addColumn('is_active', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Is_Active'),
				'width'     =>'50px',
				'index'     => 'is_active'
		));

		$this->addColumn('stores', array(
				'header'        => Mage::helper('cms')->__('Stores'),
				'index'         => 'stores',
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('ves_tempcp')->__('CSV'));

		return parent::_prepareColumns();


	}

	protected function _prepareCollection()
	{
		//$block_collection = Mage::getModel('cms/block')->getCollection();
		$block_collection = Mage::getResourceModel('cms/block_collection');

		// add the stores this block belongs to
		foreach ($block_collection as $key => $block) {
			$stores = $block->getResource()->lookupStoreIds($block->getBlockId());
			$stores = implode(';', $stores);
			$block->setStores($stores);
		} // end

		/* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
		$this->setCollection($block_collection);

		return parent::_prepareCollection();
	}


}
