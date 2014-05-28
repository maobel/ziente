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

class Ves_Blog_Block_Adminhtml_Comment_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('comment_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

		$fieldset = $form->addFieldset('comment_meta', array('legend'=>Mage::helper('ves_blog')->__('Meta Information')));
        
		
		$fieldset->addField('meta_keywords', 'editor', array(
			'label'     => Mage::helper('ves_blog')->__('Meta Keywords'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_keywords',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false,
			//'value'     => $_model->getDescription()
			 'config'    =>  $config,
		));
		$fieldset->addField('meta_description', 'editor', array(
			'label'     => Mage::helper('ves_blog')->__('Meta Description'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_description',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false,
			//'value'     => $_model->getDescription()
			 'config'    =>  $config,
		));
		
        
		if ( Mage::getSingleton('adminhtml/session')->getCommentData() )
		  {
			  $form->setValues(Mage::getSingleton('adminhtml/session')->getCommentData());
			  Mage::getSingleton('adminhtml/session')->getCommentData(null);
		  } elseif ( Mage::registry('comment_data') ) {
			  $form->setValues(Mage::registry('comment_data')->getData());
		  }
        
        return parent::_prepareForm();
    }
	
	  
}
