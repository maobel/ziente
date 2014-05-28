<?php
/**
 * Tempcp for Magento
 *
 * @category   Ves
 * @package    Ves_Tempcp
 * @copyright  Copyright (c) 2009 Ves GmbH & Co. KG <magento@Ves.de>
 */

/**
 * Tempcp for Magento
 *
 * @category   Ves
 * @package    Ves_Tempcp
 * @author     Landofcoder <landofcoder@gmail.com>
 */
class Ves_Tempcp_Model_Mysql4_Theme_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_previewFlag;

    /**
     * Constructor
     *
     */
    protected function _construct()
    {
        $this->_init('ves_tempcp/theme')
            ->setOrder('creation_time', 'DESC');
    }
    
    /**
     * Creates an options array for grid filter functionality
     *
     * @return array Options array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('theme_id', 'creation_time');
    }

    public function addIsActiveFilter()
    {
        $this->addFilter('is_active', 1);
        return $this;
    }

    public function addIsDefaultFilter($val = 0)
    {
        $this->addFilter('is_default', $val);
        return $this;
    }
    
   
    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store Store to be filtered
     * @return Ves_Tempcp_Model_Mysql4_Theme_Collection Self
     */
    public function addStoreFilter($store)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array (
                 $store->getId()
            );
        }
        
        $this->getSelect()->join(
            array('store_table' => $this->getTable('ves_tempcp/theme_store')),
            'main_table.theme_id = store_table.theme_id',
            array ()
        )->where('store_table.store_id in (?)', array (
            0, 
            $store
        ))->group('main_table.theme_id');
        
        return $this;
    }

    
    /**
     * After load processing - adds store information to the datasets
     *
     */
    protected function _afterLoad()
    {
        if ($this->_previewFlag) {
            $items = $this->getColumnValues('theme_id');
            if (count($items)) {
                $select = $this->getConnection()->select()->from(
                    $this->getTable('ves_tempcp/theme_store')
                )->where(
                    $this->getTable('ves_tempcp/theme_store') . '.theme_id IN (?)',
                    $items
                );
                if ($result = $this->getConnection()->fetchPairs($select)) {
                    foreach ($this as $item) {
                        if (!isset($result[$item->getData('theme_id')])) {
                            continue;
                        }
                        if ($result[$item->getData('theme_id')] == 0) {
                            $stores = Mage::app()->getStores(false, true);
                            $storeId = current($stores)->getId();
                            $storeCode = key($stores);
                        }
                        else {
                            $storeId = $result[$item->getData('theme_id')];
                            $storeCode = Mage::app()->getStore($storeId)->getCode();
                        }
                        if($item->getData('is_default') == 1){
                            $this->setData('is_default', Mage::helper('ves_tempcp')->__('<span class="hightlight">Default</span>'));
                        }else{
                            $this->setData('is_default', Mage::helper('ves_tempcp')->__('No'));
                        }
                        $item->setData('_first_store_id', $storeId);
                        $item->setData('store_code', $storeCode);
                    }
                }
            }
        }
        
        parent::_afterLoad();
    }
}
