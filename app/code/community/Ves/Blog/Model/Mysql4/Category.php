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
class Ves_Blog_Model_Mysql4_Category extends Mage_Core_Model_Mysql4_Abstract {

    /**
     * Initialize resource model
     */
    protected function _construct() {
	
        $this->_init('ves_blog/category', 'category_id');
    }

    /**
     * Load images
     */
   // public function loadImage(Mage_Core_Model_Abstract $object) {
   //     return $this->__loadImage($object);
   // }


    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object) {
        $select = parent::_getLoadSelect($field, $value, $object);

        return $select;
    }

    /**
     * Call-back function
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        // Cleanup stats on blog delete
        $adapter = $this->_getReadAdapter();
        $identifier = $object->getData('identifier');
        $identifier = trim($identifier);
        $identifier = strtolower($identifier);
        $identifier = str_replace(' ','-', $identifier);
        $object->setData('identifier', $identifier);

        return parent::_beforeSave($object);
    }

     /**
     * Assign page to store views
     *
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
      $condition = $this->_getWriteAdapter()->quoteInto('category_id = ?', $object->getId());
      // process faq item to store relation
      $this->_getWriteAdapter()->delete($this->getTable('ves_blog/category_store'), $condition);
      $stores = (array) $object->getData('stores');
      
      if($stores){
        foreach ((array) $object->getData('stores') as $store) {
          $storeArray = array ();
          $storeArray['category_id'] = $object->getId();
          $storeArray['store_id'] = $store;
          $this->_getWriteAdapter()->insert(
            $this->getTable('ves_blog/category_store'), $storeArray
          );
        } 
      }else{
        $storeArray = array ();
        $storeArray['category_id'] = $object->getId();
        $storeArray['store_id'] = $object->getStoreId();
        $this->_getWriteAdapter()->insert(
          $this->getTable('ves_blog/category_store'), $storeArray
        );
      }
      
      
      return parent::_afterSave($object);
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
      $this->getTable('ves_blog/category_store')
    )->where('category_id = ?', $object->getId());
    
    if ($data = $this->_getReadAdapter()->fetchAll($select)) {
      $storesArray = array ();
      foreach ($data as $row) {
        $storesArray[] = $row['store_id'];
      }
      $object->setData('store_id', $storesArray);
    }
        
    return parent::_afterLoad($object);
  }

  public function lookupStoreIds($category_id = 0){
      $select = $this->_getReadAdapter()->select()->from(
        $this->getTable('ves_blog/category_store')
      )->where('category_id = ?', (int)$category_id);

      $storesArray = array ();

      if ($data = $this->_getReadAdapter()->fetchAll($select)) {
        
        foreach ($data as $row) {
          $storesArray[] = $row['store_id'];
        }
      }
      return $storesArray;
  }

}
