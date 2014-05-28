<?php
 /*------------------------------------------------------------------------
  # Ves Blog Module 
  # ------------------------------------------------------------------------
  # author:    Ves.Com
  # copyright: Copyright (C) 2012 http://www.ves.com. All Rights Reserved.
  # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.ves.com
  # Technical Support:  http://www.ves.com/
-------------------------------------------------------------------------*/
class Ves_Blog_Block_Adminhtml_Post_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('slider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ves_blog')->__('Post Information'));
    }

    protected function _beforeToHtml()
    {
	
        $this->addTab('general_section', array(
            'label'     => Mage::helper('ves_blog')->__('General Information'),
            'title'     => Mage::helper('ves_blog')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('ves_blog/adminhtml_post_edit_tab_form')->toHtml(),
        ));
		$this->addTab('seo_section', array(
            'label'     => Mage::helper('ves_blog')->__('SEO'),
            'title'     => Mage::helper('ves_blog')->__('SEO'),
            'content'   => $this->getLayout()->createBlock('ves_blog/adminhtml_post_edit_tab_meta')->toHtml(),
        ));		
        return parent::_beforeToHtml();
    }
}