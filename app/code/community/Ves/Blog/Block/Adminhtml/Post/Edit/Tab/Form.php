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
class Ves_Blog_Block_Adminhtml_Post_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
    {
        $_model = Mage::registry('post_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

		$fieldset = $form->addFieldset('post_meta', array('legend'=>Mage::helper('ves_blog')->__('Meta Information')));
        
		$fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('ves_blog')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            //'value'     => $_model->getIsActive()
        ));
		$fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('ves_blog')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
		$fieldset->addField('identifier', 'text', array(
            'label'     => Mage::helper('ves_blog')->__('Identifier'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'identifier',
        ));
		$fieldset->addField('category_id', 'select', array(
            'label'     => Mage::helper('ves_blog')->__('Category'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'category_id',
			'values'   => Mage::helper('ves_blog')->getCategoriesList()
        ));
		$fieldset->addField('tags', 'text', array(
            'label'     => Mage::helper('ves_blog')->__('Tags'),
            'class'     => '',
            'required'  => false,
            'name'      => 'tags',
        ));
		$fieldset->addField('file', 'image', array(
            'label'     => Mage::helper('ves_blog')->__('Image'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'file',
        ));
		$fieldset->addField('position', 'text', array(
            'label'     => Mage::helper('ves_blog')->__('Position'),
            'class'     => '',
            'required'  => false,
            'name'      => 'position',
			//'value'     => $_model->getPosition()
        ));
		$fieldset->addField('hits', 'text', array(
            'label'     => Mage::helper('ves_blog')->__('Hits'),
            'class'     => '',
            'required'  => false,
            'name'      => 'hits',
			//'value'     => $_model->getPosition()
        ));
	$fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('ves_blog')->__('Description'),
            'class'     => '',
            'required'  => false,
            'name'      => 'description',
			'style'     => 'width:600px;height:200px;',
            'wysiwyg'   => true,
			'config'    => $config
        ));
		$fieldset->addField('detail_content', 'editor', array(
            'label'     => Mage::helper('ves_blog')->__('Content'),
            'class'     => '',
            'required'  => false,
            'name'      => 'detail_content',
			'style'     => 'width:600px;height:300px;',
            'wysiwyg'   => true,
			'config'   => $config
        ));
        
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('ves_blog')->__('Store View'),
                'title' => Mage::helper('ves_blog')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')
                             ->getStoreValuesForForm(false, true),
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }
        
		if ( Mage::getSingleton('adminhtml/session')->getPostData() )
		  {
			  $form->setValues(Mage::getSingleton('adminhtml/session')->getPostData());
			  Mage::getSingleton('adminhtml/session')->getPostData(null);
		  } elseif ( Mage::registry('post_data') ) {
			  $form->setValues(Mage::registry('post_data')->getData());
		  }
        
        return parent::_prepareForm();
    }
}
