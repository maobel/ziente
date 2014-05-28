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

class Ves_Blog_Model_System_Config_Source_ListComment
{	
 
    public function toOptionArray()
    {
		

		$output = array();
		$output[] = array("value"=>"" , "label" => Mage::helper('adminhtml')->__("Default Engine"));
		$output[] = array("value"=>"disqus" , "label" => Mage::helper('adminhtml')->__("Disqus"));
		$output[] = array("value"=>"facebook" , "label" => Mage::helper('adminhtml')->__("Facebook"));
		
        return $output ;
    }    
}
