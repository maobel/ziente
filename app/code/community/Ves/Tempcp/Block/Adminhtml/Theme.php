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
 * @category    Lof
 * @package     Lof_Coinslider
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Banner base block
 *
 * @category    Lof
 * @package     Lof_Coinslider
 * @author    
 */
class Ves_Tempcp_Block_Adminhtml_Theme extends Mage_Adminhtml_Block_Widget_Grid_Container {
    public function __construct() {
        $this->_controller = 'adminhtml_theme';
        $this->_blockGroup = 'ves_tempcp';
        $this->_headerText = Mage::helper('ves_tempcp')->__('Theme Manager');
        parent::__construct();

        $this->setTemplate('ves_tempcp/theme.phtml');
    }

    protected function _prepareLayout() {
        $this->setChild('add_new_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('ves_tempcp')->__('Add Theme'),
                'onclick'   => "setLocation('".$this->getUrl('*/*/add')."')",
                'class'   => 'add'
                ))
        );
        $this->setChild('importcsv',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label'     => Mage::helper('ves_tempcp')->__('Import CSV'),
                'onclick'   => 'setLocation(\'' . $this->getImportUrl() .'\')',
                'class'   => 'import'
                ))
        );
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
        $this->setChild('grid', $this->getLayout()->createBlock('ves_tempcp/adminhtml_theme_grid', 'theme.grid'));
        return parent::_prepareLayout();
    }

    private function getImportUrl() {
        return $this->getUrl('*/*/uploadCsv');
    } // end

    public function getAddNewButtonHtml() {
        return $this->getChildHtml('add_new_button');
    }

    public function getImportButtonHtml() {
        return $this->getChildHtml('importcsv');
    }

    public function getGridHtml() {
        return $this->getChildHtml('grid');
    }

    public function getStoreSwitcherHtml() {
       return $this->getChildHtml('store_switcher');
    }
}