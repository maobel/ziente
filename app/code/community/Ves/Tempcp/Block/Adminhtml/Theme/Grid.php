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
 * @package     Lof_Slider
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Banner grid
 *
 * @category    Lof 
 * @package     Lof_Slider
 * @author    
 */
class Ves_Tempcp_Block_Adminhtml_Theme_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    public function __construct() {
	
        parent::__construct();
        $this->setId('themeGrid');
        $this->setDefaultSort('date_from');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->addExportType('*/adminhtml_theme/exportCsv', Mage::helper('ves_tempcp')->__('CSV'));
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('ves_tempcp/theme')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('theme_id', array(
                'header'    => Mage::helper('ves_tempcp')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'theme_id',
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', 
                    array (
                            'header' => Mage::helper('cms')->__('Store view'), 
                            'index' => 'store_id', 
                            'type' => 'store', 
                            'store_all' => true, 
                            'store_view' => true, 
                            'sortable' => false, 
                            'filter_condition_callback' => array (
                                    $this, 
                                    '_filterStoreCondition' ) ));
        }
		$this->addColumn('group', array(
                'header'    => Mage::helper('ves_tempcp')->__('Theme'),
                'align'     =>'left',
                'index'     => 'group',
        ));
        $this->addColumn('default', array(
                'header'    => Mage::helper('ves_tempcp')->__('Default'),
                'align'     =>'left',
                'index'     => 'is_default',
                'type' => 'options',
                'width' => '100px',
                'options' => array (
                                0 => Mage::helper('ves_tempcp')->__('No'),
                                1 => Mage::helper('ves_tempcp')->__('Default') ),
                'filter_condition_callback' => array (
                                    $this,
                                    '_filterDefaultCondition' )
        ));

        $this->addColumn('action',
                array(
                'header'    =>  Mage::helper('ves_tempcp')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                        array(
                                'caption'   => Mage::helper('ves_tempcp')->__('Set Default'),
                                'url'       => array('base'=> '*/*/setdefault'),
                                'field'     => 'id'
                        ),
                        array(
                                'caption'   => Mage::helper('ves_tempcp')->__('Remove Default'),
                                'url'       => array('base'=> '*/*/removedefault'),
                                'field'     => 'id'
                        ),
                        array(
                                'caption'   => Mage::helper('ves_tempcp')->__('Edit'),
                                'url'       => array('base'=> '*/*/edit'),
                                'field'     => 'id'
                        ),
                        array(
                                'caption'   => Mage::helper('ves_tempcp')->__('Delete'),
                                'url'       => array('base'=> '*/*/delete'),
                                'field'     => 'id'
                        )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }
    /**
     * Helper function to do after load modifications
     *
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    protected function _prepareMassaction() {
        $this->setMassactionIdField('theme_id');
        $this->getMassactionBlock()->setFormFieldName('theme');

        $this->getMassactionBlock()->addItem('setdefault', array(
                'label'    => Mage::helper('ves_tempcp')->__('Set Default Themes'),
                'url'      => $this->getUrl('*/*/massSetdefault')
        ));

        $this->getMassactionBlock()->addItem('removedefault', array(
                'label'    => Mage::helper('ves_tempcp')->__('Remove Default Themes'),
                'url'      => $this->getUrl('*/*/massRemovedefault'),
                'sortable'  => false
        ));

        $this->getMassactionBlock()->addItem('clone', array(
                'label'    => Mage::helper('ves_tempcp')->__('Clone Themes'),
                'url'      => $this->getUrl('*/*/massClone')
        ));

        $this->getMassactionBlock()->addItem('delete', array(
                'label'    => Mage::helper('ves_tempcp')->__('Delete'),
                'url'      => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('ves_tempcp')->__('Are you sure?')
        ));

        return $this;
    }
     /**
     * Helper function to add store filter condition
     *
     * @param Mage_Core_Model_Mysql4_Collection_Abstract $collection Data collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column Column information to be filtered
     */
    protected function _filterDefaultCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        
        $this->getCollection()->addIsDefaultFilter($value);
    }
    /**
     * Helper function to add store filter condition
     *
     * @param Mage_Core_Model_Mysql4_Collection_Abstract $collection Data collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column Column information to be filtered
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        
        $this->getCollection()->addStoreFilter($value);
    }
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}