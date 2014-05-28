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

class Ves_Blog_Block_Adminhtml_Post_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('post_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

		$fieldset = $form->addFieldset('post_meta', array('legend'=>Mage::helper('ves_blog')->__('Meta Information')));
        
		try{
			$config = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
                array(
                        'add_widgets' => false,
                        'add_variables' => false,
                    )
                );
			if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
				$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			}  

			$config->setData(Mage::helper('ves_blog')->recursiveReplace(
					'/ves_blog/',
					'/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/',
					$config->getData()
				)
			);
				
		}
        catch (Exception $ex){
            $config = null;
        }		
		$fieldset->addField('meta_keywords', 'editor', array(
			'label'     => Mage::helper('ves_blog')->__('Meta Keywords'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_keywords',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false,
			//'value'     => $_model->getDescription()
			 
		));
		$fieldset->addField('meta_description', 'editor', array(
			'label'     => Mage::helper('ves_blog')->__('Meta Description'),
			'class'     => '',
			'required'  => false,
			'name'      => 'meta_description',
			'style'     => 'width:600px;height:100px;',
			'wysiwyg'   => false,
			//'value'     => $_model->getDescription()
		 
		));
		
        
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
