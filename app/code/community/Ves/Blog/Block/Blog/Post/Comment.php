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
class Ves_Blog_Block_Blog_Post_Comment extends Ves_Blog_Block_Blog_Template
{
	protected function _prepareLayout() {			 
		
			
	}
	
	public function getCollection(){
		$page = $this->getRequest()->getParam('page') ? $this->getRequest()->getParam('page') : 1;
		$comment = Mage::getModel('ves_blog/comment')->getCollection()
			->addEnableFilter( 1  )
			->setPageSize( $this->getGeneralConfig("comment_limit") )
			->setCurPage($page)
			->addPostFilter( $this->getRequest()->getParam('id')  );
 
		return $comment;
	}
	
	public function getCountingComment(){
		$comment = Mage::getModel('ves_blog/comment')->getCollection()
			->addEnableFilter( 1  )
			->addPostFilter( $this->getRequest()->getParam('id')  );
		Mage::register( 'paginateTotal', count($comment) );
		Mage::register( "paginateLimitPerPage", $this->getGeneralConfig("comment_limit") );
		return count($comment);
	}
	
	public function getReCaptcha(){ 
		return Mage::helper('ves_blog/recaptcha')
			->setKeys( $this->getGeneralConfig("privatekey"), $this->getGeneralConfig("publickey") )
			->getReCapcha();
	}
}
?>