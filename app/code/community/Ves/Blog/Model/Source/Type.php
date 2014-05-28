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
class Ves_Blog_Model_Config_Source_Type
{
    const IMAGE       = 'image';
    const PRODUCT    = 'product';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::IMAGE, 'label'=>Mage::helper('adminhtml')->__('Image')),
            array('value' => self::PRODUCT, 'label'=>Mage::helper('adminhtml')->__('Product'))
        );
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toGridOptionArray()
    {
        return array(
            self::IMAGE => Mage::helper('adminhtml')->__('Image'),
            self::PRODUCT => Mage::helper('adminhtml')->__('Product')
        );
    }
}
