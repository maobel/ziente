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
class Ves_Blog_Model_Mysql4_Post_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    /**
     * Constructor method
     */
    protected function _construct() {
        $this->_init('ves_blog/post');
    }

    /**
     * Add Filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Ves_Slider_Model_Mysql4_Post_Collection
     */
    public function addStoreFilter($store) {
        if (!Mage::app()->isSingleStoreMode()) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()->join(
                    array('store_table' => $this->getTable('ves_blog/post_store')),
                    'main_table.post_id = store_table.post_id',
                    array()
                    )
                    ->where('store_table.store_id in (?)', array(0, $store));
            return $this;
        }
        return $this;
    }
	
	function _prepareCollection () {
			echo 'ha cong tien'; 
	}
    /**
     * Add Filter by status
     *
     * @param int $status
     * @return Ves_Slider_Model_Mysql4_Post_Collection
     */
    public function addEnableFilter($status = 1) {
        $this->getSelect()->where('main_table.is_active = ?', $status);
        return $this;
    }
	public function addAuthorFilter( $userId ) {
        $this->getSelect() 
                ->where('main_table.user_id = ?', $userId);

        return $this;
    }
	public function  addCategoriesFilter( $categoryId ){
		 $this->getSelect()->join(
                    array('cate' => $this->getTable('ves_blog/category')),
                    'main_table.category_id = cate.category_id',
                    array("cate.title as cat_title")
                    )
                    ->where('main_table.category_id not in (?)', array(0, $categoryId));
		return $this;			
	}
	public function addCategoryFilter( $categoryId ) {
        $this->getSelect() 
                ->where( 'main_table.category_id = ?', $categoryId );

        return $this;
    }
	
	public function addTagsFilter( $tags ){
		$condition = array();
		$collection = 	$this->getSelect();
        $where = array();
		foreach( $tags as $tag ) {
			$where[] = ' main_table.tags like "%'.trim($tag).'%" ';
		}
        $where = implode(" OR ", $where);
        $collection->where( $where );
		return $this;
	}

    public function addIdFilter( $post_id, $where_code = "!="){
        $condition = array();
        $collection =   $this->getSelect(); 
        $collection->where( ' main_table.post_id '.$where_code.(int)$post_id);

        return $this;
    }

    /**
     * After load processing - adds store information to the datasets
     *
     */
    protected function _beforeLoad()
    {
       parent::_beforeLoad();
    }
	 
}