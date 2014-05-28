<?php
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
class Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Theme_Upload extends Ves_Tempcp_Block_Adminhtml_Cms_Enhanced_Abstract_Upload {

	public function __construct()
	{
		parent::__construct();

		$this->_mode = 'theme_upload';
		
		$this->_headerText = 'Venus Theme Uploader';


	} // end

	
	/**
	 * Get URL for back (reset) button
	 *
	 * @return string
	 */
	public function getBackUrl()
	{
		return $this->getUrl('*/*/index/');
	}


} // end class
