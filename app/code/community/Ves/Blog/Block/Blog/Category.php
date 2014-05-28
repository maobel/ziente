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

class Ves_Blog_Block_Blog_Category extends Ves_Blog_Block_Blog_Template
{
    private $category;
	
    protected function _prepareLayout() {			 
		$id = $this->getRequest()->getParam('id');
		$this->category = Mage::getModel('ves_blog/category')->load( $id );
		$this->getCountingPost();
		
		$this->setType( "category" )
				->setPageTitle( $this->category->getTitle() )
				->setHeadInfo( $this->category->getMetaKeyword(), $this->category->getMetaDescription() );
				
		
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb( 'home', array( 'label'=>Mage::helper('ves_blog')->__('Home'), 
											   'title'=>Mage::helper('ves_blog')->__('Go to Home Page'), 
											   'link' => Mage::getBaseUrl()) );
		
		$extension = "";
		$breadcrumbs->addCrumb( 'venus_blog', array( 'label' => $this->getGeneralConfig("title"), 
													 'title' => $this->getGeneralConfig("title"), 
													 'link'  =>  Mage::getBaseUrl().$this->getGeneralConfig("route").$extension ) );	
													
		$breadcrumbs->addCrumb( 'blogcategory_title', array( 'label'=> $this->category->getTitle(), 
													 'title'=>$this->category->getTitle(), 
													'link' => $this->category->getCategoryLink()) );		
	}
	
	public function getCategory(){
	
				
		return $this->category ;	
	}
	public function getChildrent(){
		$id = $this->getRequest()->getParam('id');
		$collection = Mage::getModel('ves_blog/category')
						->getCollection()
						->addEnableFilter()
						->addChildrentFilter( $id )
						->setOrder("position","DESC");
			
		return $collection;
	}
	
	public function countPosts( $categoryId ){
		$collection = Mage::getModel( 'ves_blog/post' )
				->getCollection()
				->addCategoryFilter( $categoryId );
		return $collection->count();		
	}
	public function getCountingPost(){
		$id = $this->getRequest()->getParam('id');
		
		$collection = Mage::getModel( 'ves_blog/post' )
				->getCollection()
				->addCategoryFilter( $id );
		$limit = (int)$this->getListConfig("list_leadinglimit") + (int)$this->getListConfig("list_secondlimit");		
		Mage::register( 'paginateTotal', count($collection) );
		Mage::register( "paginateLimitPerPage", $limit );
	}
	
	public function getPosts(){
		
		$id = $this->getRequest()->getParam('id');
		$page = $this->getRequest()->getParam('page') ? $this->getRequest()->getParam('page') : 1;
		$limit = (int)$this->getListConfig("list_leadinglimit") + (int)$this->getListConfig("list_secondlimit");
		$collection = Mage::getModel( 'ves_blog/post' )
				->getCollection()
				->addCategoryFilter( $id )
				->setOrder( 'created', 'DESC' )
				->setPageSize( $limit )
				->setCurPage( $page );
		
		return $collection;		
	}
	public function getCountingComment( $post_id = 0){

	      $comment = Mage::getModel('ves_blog/comment')->getCollection()
	        ->addEnableFilter( 1  )
	        ->addPostFilter( $post_id  );
	      return count($comment);
 	}
}
?>