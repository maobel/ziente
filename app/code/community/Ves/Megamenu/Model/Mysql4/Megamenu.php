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
class Ves_Megamenu_Model_Mysql4_Megamenu extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the megamenu_id refers to the key field in your database table.
        $this->_init('ves_megamenu/megamenu', 'megamenu_id');
    }
    
    /**
     * Assign page to store views
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterDelete(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('megamenu_id = ?', $object->getId());
        // process faq item to store relation
        $this->_getWriteAdapter()->delete($this->getTable('ves_megamenu/megamenu_store'), $condition);
    }
    /**
     * Assign page to store views
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('megamenu_id = ?', $object->getId());
        // process faq item to store relation
        $this->_getWriteAdapter()->delete($this->getTable('ves_megamenu/megamenu_store'), $condition);
        $stores = (array) $object->getData('stores');

        if($stores){
            foreach ((array) $object->getData('stores') as $store) {
                $storeArray = array ();
                $storeArray['megamenu_id'] = $object->getId();
                $storeArray['store_id'] = $store;
                $this->_getWriteAdapter()->insert(
                    $this->getTable('ves_megamenu/megamenu_store'), $storeArray);
            }   
        }else{
            $storeArray = array ();
            $storeArray['megamenu_id'] = $object->getId();
            $storeArray['store_id'] = $object->getStoreId();
            $this->_getWriteAdapter()->insert(
                    $this->getTable('ves_megamenu/megamenu_store'), $storeArray);
            
        }
        
        return parent::_afterSave($object);
    }
    

    /*protected function _beforeDelete(Mage_Core_Model_Abstract $object) {
        $adapter = $this->_getReadAdapter();
        $adapter->delete($this->getTable('ves_megamenu/megamenu_store'), 'megamenu_id='.$object->getId());

        return parent::_beforeDelete($object);
    }*/
    
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $select->join(array('cbs' => $this->getTable('ves_megamenu/megamenu_store')), $this->getMainTable().'.megamenu_id = cbs.megamenu_id')
                    ->where('cbs.store_id in (0, ?) ', $object->getStoreId())
                    ->order('store_id DESC')
                    ->limit(1);
        }
        return $select;
    }

    /**
     * Do store and category processing after loading
     * 
     * @param Mage_Core_Model_Abstract $object Current faq item
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        // process faq item to store relation
        $select = $this->_getReadAdapter()->select()->from(
            $this->getTable('ves_megamenu/megamenu_store')
        )->where('megamenu_id = ?', $object->getId());
        
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array ();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }
        
        return parent::_afterLoad($object);
    }

    public function updateStores( $stores = array(), $menu_id = 0)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('megamenu_id = ?', $menu_id);
        // process faq item to store relation
        $this->_getWriteAdapter()->delete($this->getTable('ves_megamenu/megamenu_store'), $condition);
        if($stores){
            foreach ((array) $stores as $store) {
                $storeArray = array ();
                $storeArray['megamenu_id'] = $menu_id;
                $storeArray['store_id'] = $store;
                $this->_getWriteAdapter()->insert(
                    $this->getTable('ves_megamenu/megamenu_store'), $storeArray);
            }   
        }
        return true;
    }
}