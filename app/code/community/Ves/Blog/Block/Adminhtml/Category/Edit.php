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

class Ves_Blog_Block_Adminhtml_Category_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup  = 'ves_blog';
        $this->_controller  = 'adminhtml_category';

        $this->_updateButton('save', 'label', Mage::helper('ves_blog')->__('Save Record'));
        $this->_updateButton('delete', 'label', Mage::helper('ves_blog')->__('Delete Record'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    protected function _prepareLayout() {
         /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                   $this->getLayout()->createBlock('adminhtml/store_switcher')
                   ->setUseConfirm(false)
                   ->setSwitchUrl($this->getUrl('*/*/*/id/'.Mage::registry('category_data')->get('category_id'), array('store'=>null)))
           );
        }

        return parent::_prepareLayout();
    }

    public function getStoreSwitcherHtml() {
       return $this->getChildHtml('store_switcher');
    }

    public function getHeaderText()
    {
        if( Mage::registry('category_data')->getId() ) {
			return Mage::helper('ves_blog')->__("Edit Record '%s'", $this->htmlEscape(Mage::registry('category_data')->getTitle()));
		}else {
			return Mage::helper('ves_blog')->__("Add New Category");
		}
	}
}