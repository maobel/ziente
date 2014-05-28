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
class Ves_Blog_Block_Blog_Toolbar extends Ves_Blog_Block_Blog_Template
{
	protected function _prepareLayout() {			 
		
	}
	public function getTotal() {
		return Mage::registry('paginateTotal');
    }
    
    public function getPages() {
		return ceil(($this->getTotal())/(int)$this->getLimitPerPage() );
    }
	
	public function getLimitPerPage(){
		return Mage::registry('paginateLimitPerPage');
	}
}

?>