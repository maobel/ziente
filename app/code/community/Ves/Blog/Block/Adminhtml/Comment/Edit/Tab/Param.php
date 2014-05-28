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

class Ves_Blog_Block_Adminhtml_Comment_Edit_Tab_Param extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('comment_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('comment_params', array('legend'=>Mage::helper('ves_blog')->__('Parameter')));
		
		$fieldset->addField('template', 'select', array(
			'label'     => Mage::helper('ves_blog')->__('Template'),
			'name'      => 'param[template]',
			'values'    => array( 0=> $this->__("No"), 1=> $this->__("Yes") )
		));
		
		$fieldset->addField('show_childrent', 'select', array(
			'label'     => Mage::helper('ves_blog')->__('Show Childrent'),
			'name'      => 'param[show_childrent]',
			'values'    => array( 0=> $this->__("No"), 1=> $this->__("Yes") )
		));
		
		$fieldset->addField('primary_cols', 'text', array(
			'label'     => Mage::helper('ves_blog')->__('Show Childrent'),
			'name'      => 'param[primary_cols]',
			'default'   => '2'
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
	
	  public function getParentToOptionArray() {
		$catCollection = Mage::getModel('ves_blog/comment')
					->getCollection();
		$id = Mage::registry('comment_data')->getId();
		if($id) {
			$catCollection->addFieldToFilter('comment_id', array('neq' => $id));
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
