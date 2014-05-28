<?php
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
class Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Theme_Grid 
	extends Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Abstract_Grid
{

	protected $_isExport = true;

	public function __construct()
	{
		parent::__construct();
		$this->setId('staticThemesGrid');
		$this->setDefaultSort('theme_id');
		$this->setDefaultDir('desc');
	}

	protected function _prepareColumns()
	{
		$this->addColumn('theme_id', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Theme_Id'),
				'width'     =>'50px',
				'index'     => 'theme_id'
		));

		$this->addColumn('group', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Group'),
				'width'     =>'50px',
				'index'     => 'group'
		));

		$this->addColumn('params', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Params'),
				'width'     =>'50px',
				'index'     => 'params'
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

		$this->addColumn('is_default', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Is_Default'),
				'width'     =>'50px',
				'index'     => 'is_default'
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
		$collection = Mage::getModel('ves_tempcp/theme')->getCollection();

		// add the stores this block belongs to
		foreach ($collection as $key => $theme) {
			$stores = $theme->getResource()->lookupStoreIds($theme->getThemeId());
			$stores = implode(';', $stores);
			$theme->setStores($stores);
		} // end

		/* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
		$this->setCollection($collection);

		return parent::_prepareCollection();
	}


}
