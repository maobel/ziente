<?php

class Zientedev_Web_Block_Adminhtml_Web_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('web_form', array('legend'=>Mage::helper('web')->__('Item Appointments')));
     
      $fieldset->addField('day_id', 'text', array(
          'label'     => Mage::helper('web')->__('Day'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'day_id',
      ));

      $fieldset->addField('customer_id', 'text', array(
          'label'     => Mage::helper('web')->__('customer_id'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'customer_id',
      ));

      $fieldset->addField('time', 'text', array(
          'label'     => Mage::helper('web')->__('Time'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'time',
      ));

      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('web')->__('Name Customer'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
      ));

	

      $fieldset->addField('reservation_date', 'date', array(
    'label'     => Mage::helper('web')->__('Reservation Date'),                   
    'name'      => 'reservation_date',
    'image'     => $this->getSkinUrl('images/grid-cal.gif'),
    'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
));


      $fieldset->addField('notes', 'editor', array(
          'name'      => 'notes',
          'label'     => Mage::helper('web')->__('Content'),
          'title'     => Mage::helper('web')->__('Content'),
          'style'     => 'width:400px; height:300px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getWebData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getWebData());
          Mage::getSingleton('adminhtml/session')->setWebData(null);
      } elseif ( Mage::registry('web_data') ) {
          $form->setValues(Mage::registry('web_data')->getData());
      }
      return parent::_prepareForm();
  }
}
