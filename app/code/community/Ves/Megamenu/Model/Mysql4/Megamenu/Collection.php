<?php
/******************************************************
 * @package Ves Megamenu module for Magento 1.4.x.x and Magento 1.7.x.x
 * @version 1.0.0.1
 * @author http://landofcoder.com
 * @copyright	Copyright (C) December 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
?>
<?php
class Ves_Megamenu_Model_Mysql4_Megamenu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_previewFlag = null;
    public function _construct()
    {
        parent::_construct();
        $this->_init('ves_megamenu/megamenu');
        $this->_previewFlag = false;
    }
    
    
     /**
     * After load processing - adds store information to the datasets
     *
     */
    protected function _afterLoad()
    {
        if ($this->_previewFlag) {
            $items = $this->getColumnValues('megamenu_id');
            if (count($items)) {
                $select = $this->getConnection()->select()->from(
                    $this->getTable('ves_megamenu/megamenu_store')
                )->where(
                    $this->getTable('ves_megamenu/megamenu_store') . '.megamenu_id IN (?)',
                    $items
                );
                if ($result = $this->getConnection()->fetchPairs($select)) {
                    foreach ($this as $item) {
                        if (!isset($result[$item->getData('megamenu_id')])) {
                            continue;
                        }
                        if ($result[$item->getData('megamenu_id')] == 0) {
                            $stores = Mage::app()->getStores(false, true);
                            $storeId = current($stores)->getId();
                            $storeCode = key($stores);
                        }
                        else {
                            $storeId = $result[$item->getData('megamenu_id')];
                            $storeCode = Mage::app()->getStore($storeId)->getCode();
                        }
                        if($item->getData('is_default') == 1){
                            $this->setData('is_default', Mage::helper('ves_megamenu')->__('<span class="hightlight">Default</span>'));
                        }else{
                            $this->setData('is_default', Mage::helper('ves_megamenu')->__('No'));
                        }
                        $item->setData('_first_store_id', $storeId);
                        $item->setData('store_code', $storeCode);
                    }
                }
            }
        }
        
        parent::_afterLoad();
    }
    
    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store Store to be filtered
     * @return Ves_Megamenu_Model_Mysql4_Megamenu_Collection Self
     */
    public function addStoreFilter($store)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array (
                 $store->getId()
            );
        }
        $store = is_array($store)?$store:array($store);
        //do stuff
        $this->getSelect()->join(
            array('store_table' => $this->getTable('ves_megamenu/megamenu_store')),
            'main_table.megamenu_id = store_table.megamenu_id', array ()
        )->where('store_table.store_id in (?)', $store)->group('main_table.megamenu_id');

        return $this;
    }

    public function addIdFilter($megamenuIds) {
    	if (is_array($megamenuIds)) {
            if (empty($megamenuIds)) {
                $condition = '';
            } else {
                $condition = array('in' => $megamenuIds);
            }
        } elseif (is_numeric($megamenuIds)) {
            $condition = $megamenuIds;
        } elseif (is_string($megamenuIds)) {
            $ids = explode(',', $megamenuIds);
            if (empty($ids)) {
                $condition = $megamenuIds;
            } else {
                $condition = array('in' => $ids);
            }
        }
        $this->addFieldToFilter('parent_id', $condition);
        return $this;
    }
}