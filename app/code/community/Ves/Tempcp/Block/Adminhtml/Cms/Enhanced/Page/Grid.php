<?php
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
class Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Page_Grid 
	extends Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Abstract_Grid
{

	protected $_isExport = true;

	public function __construct()
	{
		parent::__construct();
		$this->setId('staticBlocksGrid');
		$this->setDefaultSort('block_id');
		$this->setDefaultDir('desc');
	}

	protected function _prepareColumns()
	{
		$this->addColumn('page_id', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Page_Id'),
				'width'     =>'50px',
				'index'     => 'page_id'
		));

		$this->addColumn('title', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Title'),
				'width'     =>'50px',
				'index'     => 'title'
		));

		$this->addColumn('root_template', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Root_Template'),
				'width'     =>'50px',
				'index'     => 'root_template'
		));

		$this->addColumn('meta_keywords', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Meta_Keywords'),
				'width'     =>'50px',
				'index'     => 'meta_keywords'
		));

		$this->addColumn('meta_description', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Meta_Description'),
				'width'     =>'50px',
				'index'     => 'meta_description'
		));

		$this->addColumn('identifier', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Identifier'),
				'width'     =>'50px',
				'index'     => 'identifier'
		));

		$this->addColumn('content_heading', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Content_Heading'),
				'width'     =>'50px',
				'index'     => 'content_heading',
				'frame_callback' => array($this, 'entityDecode')
		));

		$this->addColumn('content', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Content'),
				'width'     =>'50px',
				'index'     => 'content',
				'frame_callback' => array($this, 'entityDecode')
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

		$this->addColumn('sort_order', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Sort_Order'),
				'width'     =>'50px',
				'index'     => 'sort_order'
		));


		$this->addColumn('layout_update_xml', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Layout_Update_XML'),
				'width'     =>'50px',
				'index'     => 'layout_update_xml',
				'frame_callback' => array($this, 'entityDecode')
				
		));


		$this->addColumn('custom_theme', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Custom_Theme'),
				'width'     =>'50px',
				'index'     => 'custom_theme'
		));

		$this->addColumn('custom_root_template', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Custom_Root_Template'),
				'width'     =>'50px',
				'index'     => 'custom_root_template'
		));

		$this->addColumn('custom_layout_update_xml', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Custom_Layout_Update_XML'),
				'width'     =>'50px',
				'index'     => 'custom_layout_update_xml'
		));

		$this->addColumn('custom_theme_from', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Custom_Theme_From'),
				'width'     =>'50px',
				'index'     => 'custom_theme_from'
		));

		$this->addColumn('custom_theme_to', array(
				'header'    =>Mage::helper('ves_tempcp')->__('Custom_Theme_To'),
				'width'     =>'50px',
				'index'     => 'custom_theme_to'
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
		$collection = Mage::getResourceModel('cms/page_collection');

		// add the stores this block belongs to
		foreach ($collection as $key => $page) {
			$stores = $page->getResource()->lookupStoreIds($page->getPageId());
			$stores = implode(';', $stores);
			$page->setStores($stores);
		} // end

		/* @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
		$this->setCollection($collection);

		return parent::_prepareCollection();
	}


}
