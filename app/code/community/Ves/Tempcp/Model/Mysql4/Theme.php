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
class Ves_Tempcp_Model_Mysql4_Theme extends Mage_Core_Model_Mysql4_Abstract {

	public function loadThemeByGroup($group = "", $store_id = null){
		if($group){
			if($store_id !== null){
				$stores = $store_id;
				$cond = $this->_getReadAdapter()->quoteInto('t1.theme_id = t2.theme_id','');
				$table = $this->getTable('ves_tempcp/theme');
				$table2 = $this->getTable('ves_tempcp/theme_store');
				$select = $this->_getReadAdapter()->select()->from(array('t1'=>$table))
							->joinLeft(array('t2'=>$table2), $cond)->where('`group` = ?', $group)
							->where('`store_id` IN ('.$stores.')')
							->order(array('is_default DESC'))
							->limit(1, 0);

				$theme_id = $this->_getReadAdapter()->fetchOne($select);
				if(empty($theme_id)){
					$select = $this->_getReadAdapter()->select()->from(array('t1'=>$table))
							->joinLeft(array('t2'=>$table2), $cond)->where('`group` = ?', $group)
							->where('`store_id` IN (0)')
							->order(array('is_default DESC'))
							->limit(1, 0);

					$theme_id = $this->_getReadAdapter()->fetchOne($select);
				}
				return $theme_id;
			}else{
				$select = $this->_getReadAdapter()->select()->from(
					$this->getTable('ves_tempcp/theme'))->where('`group` = ?', $group);

				$data = $this->_getReadAdapter()->fetchAll($select);
				return $data;
        
			}
			
		}
		return false;
	}
	/**
	 * Constructor
	 * 
	 */
	protected function _construct() {

		$this->_init('ves_tempcp/theme', 'theme_id');
	}


	/**
	 * Retrieve select object for load object data
	 *
	 * @param string $field
	 * @param mixed $value
	 * @return Zend_Db_Select
	 */
	protected function _getLoadSelect($field, $value, $object) {

		$select = parent::_getLoadSelect($field, $value, $object);
		
		if ($object->getStoreId()) {
			$select->join(
				array('nns' => $this->getTable('ves_tempcp/theme_store')),
				$this->getMainTable() . '.item_id = `nns`.theme_id'
			)->where('is_active=1 AND `nns`.store_id in (0, ?) ',
			$object->getStoreId())->order('creation_time DESC')->limit(1);
		}
		
		return $select;
	}

	
	/**
	 * Some processing prior to saving to database - processes the given images
	 * and the store configuration
	 *
	 * @param Mage_Core_Model_Abstract $object Current faq item
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object) {

		if (!$object->getId()) {
			$object->setCreationTime(Mage :: getSingleton('core/date')->gmtDate());
		}
		
		$object->setUpdateTime(Mage :: getSingleton('core/date')->gmtDate());
	}


	/**
	 * Assign page to store views
	 *
	 * @param Mage_Core_Model_Abstract $object
	 */
	protected function _afterSave(Mage_Core_Model_Abstract $object) {
		$condition = $this->_getWriteAdapter()->quoteInto('theme_id = ?', $object->getId());
		// process faq item to store relation
		$this->_getWriteAdapter()->delete($this->getTable('ves_tempcp/theme_store'), $condition);
		$stores = (array) $object->getData('stores');

		if($stores){
			foreach ((array) $object->getData('stores') as $store) {
				$storeArray = array ();
				$storeArray['theme_id'] = $object->getId();
				$storeArray['store_id'] = $store;
				$this->_getWriteAdapter()->insert(
					$this->getTable('ves_tempcp/theme_store'), $storeArray
				);
			}
		}else{
			$storeArray = array ();
			$storeArray['theme_id'] = $object->getId();
			$storeArray['store_id'] = $object->getStoreId();
			$this->_getWriteAdapter()->insert(
				$this->getTable('ves_tempcp/theme_store'), $storeArray
			);
		}
		if($object->getIsDefault()){
			 /**
		     * Get the resource model
		     */
		    $resource = Mage::getSingleton('core/resource');
		    /**
		     * Retrieve the write connection
		     */
		    $writeConnection = $resource->getConnection('core_write');
		    /**
		     * Retrieve our table name
		     */
		    $table = $resource->getTableName('ves_tempcp/theme');
		     
		    /**
		     * Set the product ID
		     */
		    $group = $object->getGroup();
		    /**
		     * Set the new SKU
		     * It is assumed that you are hard coding the new SKU in
		     * If the input is not dynamic, consider using the
		     * Varien_Db_Select object to insert data
		     */
		    $newSku = 'new-sku';
		     
		    $query = "UPDATE {$table} SET `is_default` = '0' WHERE `group` = '{$group}' AND `theme_id` != ".(int)$object->getId();

		    /**
		     * Execute the query
		     */
		    $writeConnection->query($query);
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
			$this->getTable('ves_tempcp/theme_store')
		)->where('theme_id = ?', $object->getId());
		
		if ($data = $this->_getReadAdapter()->fetchAll($select)) {
			$storesArray = array ();
			foreach ($data as $row) {
				$storesArray[] = $row['store_id'];
			}
			$object->setData('store_id', $storesArray);
		}
        
		return parent::_afterLoad($object);
	}

	public function lookupStoreIds($theme_id = 0){
		$select = $this->_getReadAdapter()->select()->from(
			$this->getTable('ves_tempcp/theme_store')
		)->where('theme_id = ?', (int)$theme_id);

		$storesArray = array ();

		if ($data = $this->_getReadAdapter()->fetchAll($select)) {
			
			foreach ($data as $row) {
				$storesArray[] = $row['store_id'];
			}
		}
		return $storesArray;
    }
}