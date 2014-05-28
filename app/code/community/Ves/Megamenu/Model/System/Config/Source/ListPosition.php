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
 * @category    Lof * @package     ves_megamenu
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Position config model
 *
 * @category   Lof
 * @package     ves_megamenu
 * @author    
 */
class Ves_Megamenu_Model_System_Config_Source_ListPosition
{
 public function toOptionArray()
    {
        return array(
        	array('value' => "root", 'label'=>Mage::helper('adminhtml')->__('Root')),
            array('value' => "content", 'label'=>Mage::helper('adminhtml')->__('Content')),
            array('value' => "left", 'label'=>Mage::helper('adminhtml')->__('Left')),
            array('value' => "right", 'label'=>Mage::helper('adminhtml')->__('Right')),
            array('value' => "top.menu", 'label'=>Mage::helper('adminhtml')->__('Top Menu')),
            array('value' => "product.info", 'label'=>Mage::helper('adminhtml')->__('Product Info')),
            array('value' => "top.links", 'label'=>Mage::helper('adminhtml')->__('Top Links')),
            array('value' => "my.account.wrapper", 'label'=>Mage::helper('adminhtml')->__('My Account Wrapper')),
            array('value' => "footer", 'label'=>Mage::helper('adminhtml')->__('Footer')),
            array('value' => "footer_links", 'label'=>Mage::helper('adminhtml')->__('Footer Links'))
        );
    }
}
