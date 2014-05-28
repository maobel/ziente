<?php
/******************************************************
 * @package Ves Megamenu module for Magento 1.4.x.x and Magento 1.7.x.x
 * @version 1.0.0.1
 * @author http://landofcoder.com
 * @copyright	Copyright (C) December 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
if (!class_exists("Ves_Megamenu_Block_List")) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "List.php";
}

class Ves_Megamenu_Block_Top extends Ves_Megamenu_Block_List {
	
	/**
	 *
	 */
	private $_editString = '';

	/**
	 *
	 */
	private $children;
	
	/**
	 *
	 */
	private $shopUrl ;

	/**
	 *
	 */
	private $megaConfig = array();

	private $_editStringCol = '';

	private $_isLiveEdit = true;

    function _toHtml() {
		$show_megamenu = $this->getConfig('show');
		if(!$show_megamenu){
			return $this->getLayout()->getBlock('catalog.topnav')->toHtml();
		}
		
		$this->getLayout()->unsetBlock('catalog.topnav');

		//get store
		$store_id = Mage::app()->getStore()->getId();
		$this->assign('store_id', $store_id);

		$parent = '1';
		$html = $this->getTree( $parent, true, $store_id);
 		
 		$my_template = $this->getTemplate();
 		if(empty($my_template)) {
 			$this->_config['template'] = 'ves/megamenu/default.phtml';
 		} else {
 			$this->_config['template'] = $my_template;	
 		}
 		

        $this->assign('menuHtml', $html);
        $this->assign('config', $this->_config);
        $this->setTemplate($this->_config['template']);
        return parent::_toHtml();
    }
	
	public function getMenuClassAcitve( $type ){

		switch(  $this->getRequest()->getRouteName()  ){
			case 'catalog':
				if( $this->getRequest()->getParam('id') == $menu->getItem() ){
					return ' active';
				}
				break;
			case 'cms_page':
				if( $this->getRequest()->getParam('page_id') == $menu->getItem() ){
					return ' active';
				}
				break;
			
		}
			

		return '';
	}

	/**
	 *
	 */
	public function hasChild( $id ){
		return isset($this->children[$id]);
	}	
	
	/**
	 *
	 */
	public function getNodes( $id ){
		return $this->children[$id];
	}

	public function hasMegaMenuConfig( $menu ){
        $id = $menu->getId();
        return isset( $this->megaConfig[$id] )?$this->megaConfig[$id] : new stdClass(); 
    }
	/**
	 *
	 */
	public function parserMegaConfig( $params ) {
		if( !empty($params) ) { 
			foreach( $params as $param ){
				if( $param  ){
					$this->megaConfig[$param->id] = $param;
				}
			}	
		}
	}
	/**
	 *
	 */
	public function renderAttrs( $menu ) {  

	}

	/**
	 *
	 */
	public function getLink( $menu ) {
		$id = (int)$menu->getItem();

		switch( $menu->getType() ) {
			case 'category'     :
				return Mage::getModel("catalog/category")->load($id)->getUrl();
				;
			case 'product'      :
				return  Mage::getModel("catalog/product")->load($id)->getProductUrl();
				;
			case 'cms_page'      :
				return  Mage::Helper('cms/page')->getPageUrl($id);
				;
			case 'ves_blog'      :
				$modules = Mage::getConfig()->getNode('modules')->children();
				$modulesArray = (array)$modules;

				if(isset($modulesArray['Ves_Blog'])) {
					$route = Mage::getStoreConfig('ves_blog/general_setting/route');
					$extension = "";
					return  Mage::getBaseUrl().$route.$extension;
				} else {
					return ;
				}

			case 'url':
				return $menu->getUrl();
			default:
				return ;
		}
	}

	/**
	 *
	 */
	public function getTree( $parent=1 , $edit=false, $store_id = 0){
		$params = Mage::getStoreConfig('ves_megamenu/ves_megamenu/params'); //$this->model_setting_setting->getSetting( 'pavmegamenu_params' );

        if( !empty($params) ){
            $params = json_decode( $params );
        }
        $this->parserMegaConfig( $params );

		if( $edit ){
			$this->_editString  = ' data-id="%s" data-group="%s"  data-cols="%s" ';
		}
		$this->_editStringCol = ' data-colwidth="%s" data-class="%s" ' ;

		if($parent == 1 || empty($parent)){
			$parent = 1;
			$childs = Mage::getModel('ves_megamenu/megamenu')->getChilds( $parent, $store_id );
			$parent = $childs->getFirstItem()->getId();
		}
		$childs = Mage::getModel('ves_megamenu/megamenu')->getChilds( null, $store_id );

		foreach($childs as $child ){
			$megaconfig = $this->hasMegaMenuConfig( $child );
			$child->setData("megaconfig", $megaconfig);

			if( isset($megaconfig->group) && $megaconfig->group) {
                $child->setData( "is_group", $megaconfig->group );
            } 

            if( isset($megaconfig->submenu) && $megaconfig->submenu == 0) {
                $menu_class = $child->getData("menu_class");
                $child->setData("menu_class", $menu_class .' disable-menu');
            }

			$this->children[$child->getParentId()][] = $child;	
		}

		$this->shopUrl = Mage::getBaseUrl(); ;
	 
		if( $this->hasChild($parent) ){
			$data = $this->getNodes( $parent );
			
			// render menu at level 0
			$output = '<ul class="nav navbar-nav megamenu">';
			foreach( $data as $menu ){
 				
				if( $this->hasChild($menu->getId()) || $menu->getTypeSubmenu() == 'html' || $menu->getTypeSubmenu() == 'widget'){
					$output .= '<li class="parent dropdown '.$menu->getMenuClass().'" '.$this->renderAttrs($menu).'>
					<a class="dropdown-toggle" data-toggle="dropdown" href="'.$this->getLink( $menu ).'">';
					
					if( $menu->getImage()){ $output .= '<span class="menu-icon" style="background:url(\''.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$menu->getImage().'\') no-repeat;">';	}
					if($menu->getShowTitle()) {
						$output .= '<span class="menu-title">'.$menu->getTitle()."</span>";
					}
					if( $menu->getDescription() ){
						$output .= '<span class="menu-desc">' . $menu->getDescription() . "</span>";
					}
					$output .= "<b class=\"caret\"></b></a>";
					if( $menu->getImage()){  $output .= '</span>'; }
					
					if($menu->getId() > 1) {
						$output .= $this->genTree( $menu->getId(), 1, $menu );	
					}
					$output .= '</li>';
				} else if ( !$this->hasChild($menu->getId()) && isset($menu->getMegaconfig()->rows) && $menu->getMegaconfig()->rows ){
					$output .= $this->genMegaMenuByConfig( $menu->getId(), 1, $menu );
				}elseif($menu->getType() == 'html'){
					$output .= '<li class="'.$menu->getMenuClass().'" '.$this->renderAttrs($menu).'>';
					
					if( $menu->getImage()){ $output .= '<span class="menu-icon" style="background:url(\''.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$menu->getImage().'\') no-repeat;">';	}
					
					if($menu->getShowTitle()) {
						$output .= '<span class="menu-title">'.$menu->getTitle()."</span>";
					}
					
					if( $menu->getContentText() ){
						$processor = Mage::helper('cms')->getPageTemplateProcessor();
						$content_text = $processor->filter($menu->getContentText());
						$output .= '<span class="menu-desc">' . $content_text . "</span>";
					}
					if( $menu->getImage()){ $output .= '</span>';	}
					$output .= '</li>';
				}else {

					$output .= '<li class="'.$menu->getMenuClass().'" '.$this->renderAttrs($menu).'>
					<a href="'.$this->getLink( $menu ).'">';
					
					if( $menu->getImage()){ $output .= '<span class="menu-icon" style="background:url(\''.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$menu->getImage().'\') no-repeat;">';	}
					
					if($menu->getShowTitle()) {
						$output .= '<span class="menu-title">'.$menu->getTitle()."</span>";
					}
					
					if( $menu->getDescription() ){
						$output .= '<span class="menu-desc">' . $menu->getDescription() . "</span>";
					}
					if( $menu->getImage()){ $output .= '</span>';	}
					$output .= '</a></li>';
				}
			}
			$output .= '</ul>';
			
		}

		 return $output;
	
	}
	
	/**
	 *
	 */
	public function genTree( $parentId, $level,$parent, $store_id = 0) {
		 
		$attrw = '';

		$class = $parent->getIsGroup()?"dropdown-mega":"dropdown-menu";
		$class = $class." ".$parent->getMenuClass();
		$menu_width = (float)$parent->getWidth();
		if( isset($parent->getMegaconfig()->subwidth) &&  $parent->getMegaconfig()->subwidth ){
			$attrw .= ' style="width:'.$parent->getMegaconfig()->subwidth.'px"' ;
		}elseif($menu_width){
			$attrw .= ' style="width:'.$menu_width.'px"' ;
		}

		if( $parent->getTypeSubmenu() == 'html' ) {
			$output = '<div class="'.$class.'"><div class="menu-content">';
			$processor = Mage::helper('cms')->getPageTemplateProcessor();
			$submenu_content = $processor->filter($parent->getSubmenuContent());
			$output .= $submenu_content;
			$output .= '</div></div>';
			return $output;
		}elseif( $parent->getTypeSubmenu() == 'widget' ) {

			$output = '<div class="'.$class.'"><div class="menu-content">';
			$output .= Mage::getModel("ves_megamenu/widget")->renderContent( $parent->getWidgetId() );
			$output .= '</div></div>';
			return $output;
		}elseif( $this->hasChild($parentId) ){
			
			$data = $this->getNodes( $parentId );			
			$parent_colums = (int)$parent->getColums();
			
			if( $parent_colums > 1  ){

				if( isset($parent->getMegaconfig()->rows) && $parent->getMegaconfig()->rows) {
					
					$cols   = array_chunk( $data, ceil(count($data)/$parent->getColums())  );
					$output = '<div class="'.$class.' level'.$level.'" '.$attrw.' ><div class="dropdown-menu-inner">';
					foreach( $parent->getMegaconfig()->rows as $rows ){ 
						foreach( $rows as $rowcols ){
							$output .='<div class="row">';
							
							foreach( $rowcols as $key => $col ) {
								$col->colwidth = isset($col->colwidth)?$col->colwidth:6;
								if( isset($col->type) && $col->type == 'menu' && isset($cols[$key]) ){
									$scol = '<div class="mega-col col-sm-'.$col->colwidth.'" '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
									$scol .= '<ul>';
									foreach( $cols[$key] as $menu ) {
										 $scol .= $this->renderMenuContent( $menu, $level+1 );
									}
									$scol .='</ul></div></div>';
								}else {
									$scol = '<div class="mega-col col-sm-'.$col->colwidth.'"  '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
										$scol .= $this->renderWidgetsInCol( $col );
									$scol .= '</div></div>';	
								}
								$output .= $scol;
							}

							$output .= '</div>';
						}
					}
					$output .= '</div></div>';

				}else {
					$output = '<div class="'.$class.' mega-cols cols'.$parent->getColums().'" '.$attrw.' ><div class="dropdown-menu-inner"><div class="row">';
					$cols   = array_chunk( $data, ceil(count($data)/$parent->getColums())  );

					$oSpans = $this->getColWidth( $parent, (int)$parent->getColums() );
				
					foreach( $cols as $i =>  $menus ){

						$output .='<div class="mega-col '.$oSpans[$i+1].' col-'.($i+1).'" data-type="menu"><div class="mega-col-inner"><ul>';
							foreach( $menus as $menu ) {
								$output .= $this->renderMenuContent( $menu, $level+1 );
							}
						$output .='</ul></div></div>';
					}

					$output .= '</div></div></div>';
				}	
				return $output;
			}else {

				$failse = false; 

			///	echo '<pre>' .print_r( $parent, 1 );
				if( isset($parent->getMegaconfig()->rows) && $parent->getMegaconfig()->rows ) {
					$output = '<div class="'.$class.' level'.$level.'" '.$attrw.' ><div class="dropdown-menu-inner">';
					foreach( $parent->getMegaconfig()->rows as $rows ){ 
						foreach( $rows as $rowcols ){
							$output .='<div class="row">';
							foreach( $rowcols as $col ) {
								
								if( isset($col->type) && $col->type == 'menu' ){
									$colwidth = isset($col->colwidth)?$col->colwidth:'';
									$scol = '<div class="mega-col col-sm-'.$colwidth.'" '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
									$scol .= '<ul>';
									foreach( $data as $menu ){
										$scol .= $this->renderMenuContent( $menu , $level+1 );
									}	
									$scol .= '</ul>';
									
								}else {
									$scol = '<div class="mega-col col-sm-'.$col->colwidth.'"  '.$this->getColumnDataConfig( $col ).'><div class="mega-col-inner">';
									$scol .= $this->renderWidgetsInCol( $col );
								}
								$scol .= '</div></div>';
								$output .= $scol;
							}	
							$output .= '</div>';
						}

					}$output .= '</div></div>';
				} else {
					$output = '<div class="'.$class.' level'.$level.'" '.$attrw.' ><div class="dropdown-menu-inner">';
					$row = '<div class="row"><div class="col-sm-12 mega-col" data-colwidth="12" data-type="menu"><div class="mega-col-inner"><ul>';
					foreach( $data as $menu ){
						$row .= $this->renderMenuContent( $menu , $level+1 );
					}	
					$row .= '</ul></div></div></div></div></div>';

					$output .= $row;
					
				}
				
			}

			return $output;

		}
		return ;
	}

	/**
	 *
	 */
	public function genMegaMenuByConfig( $parentId, $level,$menu  ){
	 
		$attrw = '';
		$class = $level > 1 ? "dropdown-submenu":"dropdown";
		$output = '<li class="'.$menu->getMenuClass().' parent '.$class.' " '.$this->renderAttrs($menu).'>
					<a href="'.$this->getLink( $menu ).'" class="dropdown-toggle" data-toggle="dropdown">';
					
					if( $menu->getImage()){ $output .= '<span class="menu-icon" style="background:url(\''.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$menu->getImage().'\') no-repeat;">';	}
					
					$output .= '<span class="menu-title">'.$menu->getTitle()."</span>";
					if( $menu->getDescription() ){
						$output .= '<span class="menu-desc">' . $menu->getDescription() . "</span>";
					}
					if( $menu->getImage()){ $output .= '</span>';	}
					$output .= "<b class=\"caret\"></b></a>";

		if( isset($menu->getMegaconfig()->subwidth) &&  $menu->getMegaconfig()->subwidth ){
            $attrw .= ' style="width:'.$menu->getMegaconfig()->subwidth.'px"' ;
        }
		$class  = 'dropdown-menu';
		$output .= '<div class="'.$class.'" '.$attrw.' ><div class="dropdown-menu-inner">';

		foreach( $menu->getMegaconfig()->rows  as $row ){
		
			$output .= '<div class="row">';
				 foreach( $row->cols as $col ){
					$colclass = isset($col->colclass)?$col->colclass:'';
					 $output .= '<div class="mega-col col-sm-'.$col->colwidth.' '.$colclass.'" '.$this->getColumnDataConfig( $col ).'> <div class="mega-col-inner">';
					 $output .= $this->renderWidgetsInCol( $col );
					 $output .= '</div></div>';
				}
			$output .= '</div>';
		}
		unset($colclass);

		$output .= '</div></div>';
		$output .= '</li>';
		return $output; 
	}
	public function getColumnDataConfig( $col ){
        $output = '';
        if( is_object($col)  && $this->_isLiveEdit ){
            $vars = get_object_vars($col);
            foreach( $vars as $key => $var ){
                $output .= ' data-'.$key.'="'.$var . '" ' ;
            }
        }
        return $output;
    }
	public function renderWidgetsInCol( $col ){
		 if( is_object($col) && isset($col->widgets)  ){
		 	$widgets = $col->widgets; 
		 	$widgets = explode( '|wid-', '|'.$widgets );
			if( !empty($widgets) ){
				unset( $widgets[0] );
				$output = '';
				foreach( $widgets as $wid ){
					$output .= Mage::getModel("ves_megamenu/widget")->renderContent( $wid );
				}

				return $output;
			}
		 }
	}
	/**
	 *
	 */
	public function renderMenuContent( $menu , $level ){
		$output = '';
		$class = $menu->getIsGroup()?"mega-group":"";
		$menu_class = ' '.$class." ".$menu->getMenuClass();
		if( $menu->getType() == 'html' ){
			$output .= '<li class="'.$menu_class.'" '.$this->renderAttrs($menu).'>';
			$processor = Mage::helper('cms')->getPageTemplateProcessor();
			$content_text = $processor->filter($menu->getContentText());
			$output .= '<div class="menu-content">'.$content_text.'</div>'; 
			$output .= '</li>';
			return $output;
		}
		if( $this->hasChild($menu->getId()) || $menu->getTypeSubmenu() == 'html' || $menu->getTypeSubmenu() == 'widget'){

			$output .= '<li class="parent dropdown-submenu'.$menu_class.'" '.$this->renderAttrs($menu). '>';
			if( $menu->getShowTitle() ){
				$output .= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$this->getLink( $menu ).'">';
				$t = '%s';
				if( $menu->getImage()){ $output .= '<span class="menu-icon" style="background:url(\''.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$menu->getImage().'\') no-repeat;">';	}
				$output .= '<span class="menu-title">'.$menu->getTitle()."</span>";
				if( $menu->getDescription() ){
					$output .= '<span class="menu-desc">' . $menu->getDescription() . "</span>";
				}
				$output .= "<b class=\"caret\"></b>";
				if( $menu->getImage()){ 
					$output .= '</span>';
				}
				$output .= '</a>';
			}	
			if($menu->getId() > 1) {
				$output .= $this->genTree( $menu->getId(), $level, $menu );
			}
			$output .= '</li>';

		}else if ( $menu->getMegaconfig()->rows ){
			$output .= $this->genMegaMenuByConfig( $menu->getId(), $level, $menu );
		}else {
			$output .= '<li class="'.$menu_class.'" '.$this->renderAttrs($menu).'>';
			if( $menu->getShowTitle() ){
				$output .= '<a href="'.$this->getLink( $menu ).'">';
			
				if( $menu->getImage()){ $output .= '<span class="menu-icon" style="background:url(\''.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$menu->getImage().'\') no-repeat;">';	}
				$output .= '<span class="menu-title">'.$menu->getTitle()."</span>";
				if( $menu->getDescription() ){
					$output .= '<span class="menu-desc">' . $menu->getDescription() . "</span>";
				}
				if( $menu->getImage()){ 
					$output .= '</span>';
				}

				$output .= '</a>';
			}
			$output .= '</li>';
		}
		return $output;
	}
	/**
	 *
	 */
	public function getColWidth( $menu, $cols ){
		$output = array();
		$submenu_column_width  = $menu->getSubmenuColumWidth();
		$split = preg_split('#\s+#',$submenu_column_width );
		if( !empty($split) && !empty($submenu_column_width) ){
			foreach( $split as $sp ) {
				$tmp = explode("=",$sp);
				if( count($tmp) > 1 ){
					$output[trim(preg_replace("#col#","",$tmp[0]))]=(int)$tmp[1];
				}
			}
		}
		$tmp = array_sum($output);
		$spans = array();
		$t = 0; 
		for( $i=1; $i<= $cols; $i++ ){
			if( array_key_exists($i,$output) ){
				$spans[$i] = 'col-sm-'.$output[$i];
			}else{		
				if( (12-$tmp)%($cols-count($output)) == 0 ){
					$spans[$i] = "col-sm-".((12-$tmp)/($cols-count($output)));
				}else {
					if( $t == 0 ) {
						$spans[$i] = "col-sm-".( ((11-$tmp)/($cols-count($output))) + 1 ) ;
					}else {
						$spans[$i] = "col-sm-".( ((11-$tmp)/($cols-count($output))) + 0 ) ;
					}
					$t++;
				}					
			}
		}
		return $spans;
	}
	/**
	 * render static block by id
	 */
	public function renderBlockStatic( $id ){
		return $this->getLayout()->createBlock('cms/block')->setBlockId( $id )->toHtml();
	}
	
	/**
	 * get childrent menu by parent id
	 */
	public function getMenuList( $id ){
		return $this->menus[$id];
	}
	
	/**
	 * check menu having sub menu or not
	 */
	public function hasSubMenu( $id ){
		return isset($this->menus[$id]); 
	}
	
	/**
	 * get url icon
	 */
	public function getMenuIcon( $image ){
		if ( file_exists( Mage::getBaseDir('media') . DS . $image ) ){
			return Mage::getBaseDir('media') . DS . $image;
		}
		return '';
	}
	 
}
