<?php
 /*------------------------------------------------------------------------
  # VenusTheme Brand Module 
  # ------------------------------------------------------------------------
  # author:    VenusTheme.Com
  # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
  # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.venustheme.com
  # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/

class Ves_Brand_Block_Adminhtml_Brand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('brand_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('brand_form', array('legend'=>Mage::helper('ves_brand')->__('General Information')));
        
		$fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('ves_brand')->__('Is Active'),
            'name'      => 'is_active',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            //'value'     => $_model->getIsActive()
        ));
		 
		$fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('ves_brand')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
		$fieldset->addField('identifier', 'text', array(
            'label'     => Mage::helper('ves_brand')->__('Identifier'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'identifier',
            //'value'     => $_model->getLabel()
        ));
		 
		$fieldset->addField('icon', 'image', array(
            'label'     => Mage::helper('ves_brand')->__('Icon'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'icon',
        ));
		
		$fieldset->addField('file', 'image', array(
            'label'     => Mage::helper('ves_brand')->__('Image'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'file',
        ));
		
		
		
		
		$fieldset->addField('position', 'text', array(
            'label'     => Mage::helper('ves_brand')->__('Position'),
            'class'     => '',
            'required'  => false,
            'name'      => 'position',
			//'value'     => $_model->getPosition()
        ));
		
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

			$config->setData(Mage::helper('ves_brand')->recursiveReplace(
					'/ves_brand/',
					'/'.(string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName').'/',
					$config->getData()
				)
			);
				
		}
        catch (Exception $ex){
            $config = null;
        }		
		$fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('ves_brand')->__('Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'description',
			'style'     => 'width:600px;height:300px;',
            'wysiwyg'   => true,
			//'value'     => $_model->getDescription()
			 'config'    =>  $config,
        ));
		
		
        
		if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('ves_brand')->__('Store View'),
                'title' => Mage::helper('ves_brand')->__('Store View'),
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
		 
        
		if ( Mage::getSingleton('adminhtml/session')->getBrandData() )
		  {
			  $form->setValues(Mage::getSingleton('adminhtml/session')->getBrandData());
			  Mage::getSingleton('adminhtml/session')->getBrandData(null);
		  } elseif ( Mage::registry('brand_data') ) {
			  $form->setValues(Mage::registry('brand_data')->getData());
		  }
        
        return parent::_prepareForm();
    }
	
	  public function getParentToOptionArray() {
		$catCollection = Mage::getModel('ves_brand/brand')
					->getCollection();
		$id = Mage::registry('brand_data')->getId();
		if($id) {
			$catCollection->addFieldToFilter('brand_id', array('neq' => $id));
		}
		$option = array();
		$option[] = array( 'value' => 0, 
						   'label' => 'Top Level');
		foreach($catCollection as $cat) {
			$option[] = array( 'value' => $cat->getId(), 
							   'label' => $cat->getTitle() );
		}
		return $option;
    }
}
