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
 * @category    Lof * @package     Lof_Coinslider
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Banner add block
 *
 * @category   Lof
 * @package     Lof_Coinslider
 * @author    
 */

class Ves_Tempcp_Block_Adminhtml_Theme_Add extends Mage_Adminhtml_Block_Widget_Form_Container
{
    var $data = null;

    public function __construct()
    {
        parent::__construct();

        $this->_objectId    = 'id';
        $this->_blockGroup  = 'ves_tempcp';
        $this->_controller  = 'adminhtml_theme';

        $this->_updateButton('save', 'label', Mage::helper('ves_tempcp')->__('Save Theme'));
        $this->setTemplate('ves_tempcp/form/edit.phtml');

        $mediaHelper = Mage::helper('ves_tempcp/media');
        $mediaHelper->loadMedia();

        $themeHelper = Mage::helper('ves_tempcp/theme');
        $this->data = $themeHelper->initTheme();

    }
    protected function _prepareLayout() {
         /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                   $this->getLayout()->createBlock('adminhtml/store_switcher')
                   ->setUseConfirm(false)
                   ->setSwitchUrl($this->getUrl('*/*/*', array('store'=>null)))
           );
        }
        return parent::_prepareLayout();
    }
    public function getThemeData(){
        return $this->data;
    }
    public function getHeaderText()
    {
        return Mage::helper('ves_tempcp')->__("Venus Theme Control Panel: Add Theme");
    }
    public function getCancelLink(){
        return $this->getUrl('*/adminhtml_theme/index');
    }
    public function getLiveEditLink(){
        return "#";
    }
    public function getAjaxSaveLink(){
        return $this->getUrl('*/adminhtml_theme/ajaxsave');
    }
    public function getStoreSwitcherHtml() {
       return $this->getChildHtml('store_switcher');
    }
    
}