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
class Ves_Blog_Block_Blog_Post extends Ves_Blog_Block_Blog_Template
{
    private $post;
    protected function _prepareLayout() {
		$id = $this->getRequest()->getParam('id');
		$this->post = Mage::getModel('ves_blog/post')->load( $id );

		// updateing hits	
		if( $this->post->getId() > 0 ){
			$this->post->setId( $this->post->getId() );
			$this->post->setHits( (int)$this->post->getHits()+ 1 );
			$this->post->save();
		}

		$this->setType( "post" )
				->setPageTitle( $this->post->getTitle() )
				->setHeadInfo( $this->post->getMetaKeyword(), $this->post->getMetaDescription() );
				
				
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		
		$breadcrumbs->addCrumb( 'home', array( 'label'=>Mage::helper('ves_blog')->__('Home'), 
											   'title'=>Mage::helper('ves_blog')->__('Go to Home Page'), 
											   'link' => Mage::getBaseUrl()) );
		
		$extension = "";
		$breadcrumbs->addCrumb( 'venus_blog', array( 'label' => $this->getGeneralConfig("title"), 
													 'title' => $this->getGeneralConfig("title"), 
													 'link'  =>  Mage::getBaseUrl().$this->getGeneralConfig("route").$extension ) );	
													
		$breadcrumbs->addCrumb( 'blogcategory_title', array( 'label'=> $this->post->getCategoryTitle(), 
													 'title'=>$this->post->getCategoryTitle(), 
													'link' => $this->post->getCategoryLink()) );
											   
		$breadcrumbs->addCrumb( 'blogpost_title', array( 'label'=> $this->post->getTitle(), 
											   'title'=>$this->post->getTitle(), 
											   'link' => $this->post->getURL()) );
	
	}
	
	public function getPost(){
		if( !$this->post ){
			$post = $this->post; 

		 	Mage::register('current_post', $this->post);
		} 
	
		

		return $this->post;
		 
	}
	 
	
	public function getCommnet( $id=0 ){
	
	}
	
	public function getMoreInCat(){
		$collection = Mage::getModel( 'ves_blog/post' )
				->getCollection()
				->setOrder( 'created', 'DESC' )
				->setPageSize( (int)$this->getPostConfig("post_showmorepostlimit") )
				->addCategoryFilter( $this->post->getCategoryId() )
				->setCurPage( 1 );	
		return $collection;		
	}
	
	public function getRelatedPost(){
		$tags = $this->post->getTags();
		if( $tags ){
			$tags = explode(',',$tags );
			
			$id = $this->getRequest()->getParam('id');

			$collection = Mage::getModel( 'ves_blog/post' )
				->getCollection()
				->addTagsFilter( $tags )
				->addIdFilter( $id )
				->setOrder( 'created', 'DESC' )
				->setPageSize( (int)$this->getPostConfig("post_showrelatedpostlimit") )
				->setCurPage( 1 );

			return $collection;
		}
		return ;
	}
	 
 
}