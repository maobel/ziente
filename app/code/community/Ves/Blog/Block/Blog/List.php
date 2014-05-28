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
class Ves_Blog_Block_Blog_List extends Ves_Blog_Block_Blog_Template
{
    
    protected function _prepareLayout() {			 
		$tag = $this->getRequest()->getParam( "tag" ); 
		$author = (int)$this->getRequest()->getParam( "user" ); 
		if( $tag ){
			$this->setType( "tag" )
				->setPageTitle( sprintf($this->__("Displaying posts by tag: %s"),$tag) )
				->setHeadInfo( $this->getGeneralConfig("metakeywords"), $this->getGeneralConfig("metadescription") );
		}elseif( $author ) {
			$author = Mage::getModel("admin/user")->load( $author ); 
			$f = $author->getFirstname().' '.$author->getLastname();
			$this->setType( "author" )
				->setPageTitle( sprintf($this->__("Displaying posts by author: %s"),$f) )
				->setHeadInfo( $this->getGeneralConfig("metakeywords"), $this->getGeneralConfig("metadescription") );
		}else {
			$this->setType( "latest" )
				->setPageTitle( $this->__("Latest Posts") )
				->setHeadInfo( $this->getGeneralConfig("metakeywords"), $this->getGeneralConfig("metadescription") );
				
		}
		
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb( 'home', array( 'label'=>Mage::helper('ves_blog')->__('Home'), 
											   'title'=>Mage::helper('ves_blog')->__('Go to Home Page'), 
											   'link' => Mage::getBaseUrl()) );
		
		$extension = "";
		$breadcrumbs->addCrumb( 'venus_blog', array( 'label' => $this->getGeneralConfig("title"), 
													 'title' => $this->getGeneralConfig("title"), 
													 'link'  =>  Mage::getBaseUrl().$this->getGeneralConfig("route").$extension ) );	
													
	}

	public function getPosts(){
	
		$id = $this->getRequest()->getParam('id');
		$page = $this->getRequest()->getParam('page') ? $this->getRequest()->getParam('page') : 1;
		$limit = (int)$this->getListConfig("list_leadinglimit") + (int)$this->getListConfig("list_secondlimit");
		$collection = Mage::getModel( 'ves_blog/post' )
				->getCollection();
		if( $this->getType() == "tag" ){ 
			$collection->addTagsFilter( array($this->getRequest()->getParam( "tag" )) );
		}elseif ( $this->getType() == "author" ){
			$collection->addAuthorFilter( (int)$this->getRequest()->getParam( "user" ) );
		}		
		$collection->addCategoriesFilter(0)->setOrder( 'created', 'DESC' )
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