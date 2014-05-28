<?php
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
class Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Abstract_Upload extends Mage_Adminhtml_Block_Widget_Form_Container {

	public function __construct()
	{
		parent::__construct();

		$this->_objectId = 'theme_id';
		$this->_blockGroup = 'ves_tempcp';
		$this->_controller = 'adminhtml_cms_enhanced';

		$this->removeButton('reset');
		$this->removeButton('delete');
		$this->removeButton('save');

	} // end


} // end class
