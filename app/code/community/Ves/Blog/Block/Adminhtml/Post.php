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
class Ves_Blog_Block_Adminhtml_Post extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
		
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'ves_blog';
        $this->_headerText = Mage::helper('ves_blog')->__('Blog Manager');
		
        parent::__construct();

        $this->setTemplate('ves_blog/post.phtml');
		
		
    }

    protected function _prepareLayout() {
	
        $this->setChild('add_new_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('ves_blog')->__('Add Record'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/add')."')",
                'class'   => 'add'
                ))
        );
		
		$this->setChild('mass_resize_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('ves_blog')->__('Mass Resize Images'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/massResize')."')",
                'class'   => 'mass'
                ))
        );
		
		$this->setChild('mass_rewrite_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('ves_blog')->__('Mass Generate Rewrite URLs'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/massRewrite')."')",
                'class'   => 'mass'
                ))
        );
        /**
         * Display store switcher if system has more one store
         */
        //if (!Mage::app()->isSingleStoreMode()) {
        //    $this->setChild('store_switcher',
        //             $this->getLayout()->createBlock('adminhtml/store_switcher')
        //             ->setUseConfirm(false)
        //             ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
        //     );
        // }
        $this->setChild('grid', $this->getLayout()->createBlock('ves_blog/adminhtml_post_grid', 'post.grid'));
        return parent::_prepareLayout();
    }

    public function getAddNewButtonHtml() {
        return $this->getChildHtml('mass_resize_button');
    }
	
	public function getMassResizeButtonHtml() {
        return $this->getChildHtml('add_new_button');
    }
	
	public function getMassRewritePostButtonHtml(){
	   return $this->getChildHtml('mass_rewrite_button');
	}
    public function getGridHtml() {
        return $this->getChildHtml('grid');
    }

    //public function getStoreSwitcherHtml() {
     //   return $this->getChildHtml('store_switcher');
    //}
}