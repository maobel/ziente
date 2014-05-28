<?php
class Ves_Blog_Model_System_Config_Source_ListCategories {

    public function toOptionArray() {
		
		$parent = 0;
        $collection = Mage::getModel('ves_blog/category')
						->getCollection()
						->addChildrentFilter( $parent );
        $output = array();
		$output[] = array(
					'value'  => 0,
					'label'  => "Select A Root"
			);
		foreach( $collection as $category ){
			$output[] = array(
					'value'  => $category->getId(),
					'label'  => $category->getTitle()
			);
			
			$sub = Mage::getModel('ves_blog/category')
						->getCollection()
						->addChildrentFilter( $category->getId() );
						
			if( count($sub) ){
				foreach( $sub as $a ){
					$output[] = array(
							'value'  => $a->getId(),
							'label'  => "--".$a->getTitle()
					);
					
					$sub1 = Mage::getModel('ves_blog/category')
						->getCollection()
						->addChildrentFilter( $a->getId() );
						
					if( count($sub1) ){
						foreach( $sub1 as $aa ){
							$output[] = array(
								'value'  => $aa->getId(),
								'label'  => "---".$aa->getTitle()
							);
						}	
					}					
				}
			}
		}
		
		return $output;
    }

}