<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Lof * @package     lof_slidingcaption
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Page config model
 *
 * @category   Lof
 * @package     lof_slidingcaption
 * @author    
 */
class Ves_Megamenu_Model_Config_Source_Type
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
