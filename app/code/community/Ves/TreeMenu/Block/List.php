<?php

class Ves_TreeMenu_Block_List extends Mage_Core_Block_Template {

    protected $_config = '';
    protected $_listDesc = array();
    protected $_show = 0;
    protected $_theme = "";

    /**
     * Contructor
     */
    public function __construct($attributes = array()) {
        $helper = Mage::helper('ves_treemenu/data');
        $this->_config = $helper->get($attributes);
        /* End init meida files */
        $mediaHelper = Mage::helper('ves_treemenu/media');
        $this->_theme = $this->getConfig("theme");
        $mediaHelper->addMediaFile("skin_css", "ves_treemenu/" . $this->_theme . "/style.css");
        parent::__construct();
    }
 /**
     * Rendering block content
     * @return string
     */
    function _toHtml() {
        
		if( !$this->getConfig('show')  ){	return ;	}
        /* Verify */
        $theme = ($this->getConfig('theme') != "") ? $this->getConfig('theme') : "default";
		/* Build Data */
		$result = array();
		$this->assign( 'items', $result );
		/* Assign html */
        $theme = ($this->getConfig('theme') != "") ? $this->getConfig('theme') : "default";
        $this->_config['template'] = 'ves/treemenu/' . $theme . '/default.phtml';
        // render html

        $this->assign('config', $this->_config);
        $this->setTemplate($this->_config['template']);
        return parent::_toHtml();
    }
	
	/**
     * Get Current Category
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        if (Mage::getSingleton('catalog/layer')) {
            return Mage::getSingleton('catalog/layer')->getCurrentCategory();
        }
        return false;
    }
	/**
     * Get catagories of current store
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getStoreCategories()
    {
        $helper = Mage::helper('catalog/category');
        return $helper->getStoreCategories();
    }
	/**
     * Retrieve child categories of current category
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getCurrentChildCategories()
    {
        $layer = Mage::getSingleton('catalog/layer');
        $category   = $layer->getCurrentCategory();
        /* @var $category Mage_Catalog_Model_Category */
        $categories = $category->getChildrenCategories();
        $productCollection = Mage::getResourceModel('catalog/product_collection');
        $layer->prepareProductCollection($productCollection);
        $productCollection->addCountToCategories($categories);
        return $categories;
    }
	/**
     * Checkin activity of category
     * @param   Varien_Object $category
     * @return  bool
     */
    public function isCategoryActive($category)
    {
        if ($this->getCurrentCategory()) {
            return in_array($category->getId(), $this->getCurrentCategory()->getPathIds());
        }
        return false;
    }
	protected function _getCategoryInstance()
    {
        if (is_null($this->_categoryInstance)) {
            $this->_categoryInstance = Mage::getModel('catalog/category');
        }
        return $this->_categoryInstance;
    }

    /**
     * Get url for category data
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        if ($category instanceof Mage_Catalog_Model_Category) {
            $url = $category->getUrl();
        } else {
            $url = $this->_getCategoryInstance()
                ->setData($category->getData())
                ->getUrl();
        }

        return $url;
    }

    /**
     * Enter description here...
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int $level
     * @param boolean $last
     * @return string
     */
 	public function drawItem($category, $level=0, $last=false)
    {
        $html = '';
        if (!$category->getIsActive()) {
            return $html;
        }
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        $html.= '<li';
        if ($hasChildren) {
             $html.= ' onmouseover="Element.addClassName(this, \'over\') " onmouseout="Element.removeClassName(this, \'over\') "';
        }

        $html.= ' class="level'.$level;
        $html.= ' nav-'.str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath()));
        if ($this->isCategoryActive($category)) {
            $html.= ' active';
        }
        if ($last) {
            $html .= ' last';
        }
        if ($hasChildren) {
            $cnt = 0;
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $cnt++;
                }
            }
            if ($cnt > 0) {
                $html .= ' parent';
            }
        }
        $html.= '">'."\n";
    	if($level==0)
        {
        	$html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a><span class="head"><a href="#" style="float:right;"></a></span>'."\n";
        }
        else 
        {
        $html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'."\n";
        }
        if ($hasChildren){

            $j = 0;
            $htmlChildren = '';
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawItem($child, $level+1, ++$j >= $cnt);
                }
            }

            if (!empty($htmlChildren)) {
                $html.= '<ul class="level' . $level . '">'."\n"
                        .$htmlChildren
                        .'</ul>';
            }

        }
        $html.= '</li>'."\n";
        return $html;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getCurrentCategoryPath()
    {
        if ($this->getCurrentCategory()) {
            return explode(',', $this->getCurrentCategory()->getPathInStore());
        }
        return array();
    }

    /**
     * Enter description here...
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function drawOpenCategoryItem($category) {
        $html = '';
        if (!$category->getIsActive()) {
            return $html;
        }

        $html.= '<li';

        if ($this->isCategoryActive($category)) {
            $html.= ' class="active"';
        }

        $html.= '>'."\n";
        $html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'."\n";

        if (in_array($category->getId(), $this->getCurrentCategoryPath())){
            $children = $category->getChildren();
            $hasChildren = $children && $children->count();

            if ($hasChildren) {
                $htmlChildren = '';
                foreach ($children as $child) {
                    $htmlChildren.= $this->drawOpenCategoryItem($child);
                }

                if (!empty($htmlChildren)) {
                    $html.= '<ul>'."\n"
                            .$htmlChildren
                            .'</ul>';
                }
            }
        }
        $html.= '</li>'."\n";
        return $html;
    }
    
    // render item category
 	protected function _renderCategoryMenuItemHtml($category, $level = 0, $isLast = false, $isFirst = false,
        $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false)
    {
        if (!$category->getIsActive()) {
            return '';
        }
        $html = array();

        // get all children
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = (array)$category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = ($children && $childrenCount);

        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);

        // prepare list item html classes
        $classes = array();
        $classes[] = 'level' . $level;
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        $linkClass = '';
        if ($isOutermost && $outermostItemClass) {
            $classes[] = $outermostItemClass;
            $linkClass = ' class="'.$outermostItemClass.'"';
        }
        if ($this->isCategoryActive($category)) {
            $classes[] = 'active';
        }
        if ($isFirst) {
            $classes[] = 'first';
        }
        if ($isLast) {
            $classes[] = 'last';
        }
        if ($hasActiveChildren) {
            $classes[] = 'parent';
        }

        // prepare list item attributes
        $attributes = array();
        if (count($classes) > 0) {
            $attributes['class'] = implode(' ', $classes);
        }
        if ($hasActiveChildren && !$noEventAttributes) {
             $attributes['onmouseover'] = 'Element.addClassName(this, \'over\') ';
             $attributes['onmouseout'] = 'Element.removeClassName(this, \'over\') ';
        }

        // assemble list item with attributes
        $htmlLi = '<li';
        foreach ($attributes as $attrName => $attrValue) {
            $htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
        }
        $htmlLi .= '>';
        $html[] = $htmlLi;

        $html[] = '<a href="'.$this->getCategoryUrl($category).'"'.$linkClass.'>';
        $html[] = '<span>' . $this->escapeHtml($category->getName()) . '</span>';
        $html[] = '</a>';

        // render children
        
        $htmlChildren = '';
        $j = 0;
        foreach ($activeChildren as $child) {
            $htmlChildren .= $this->_renderCategoryMenuItemHtml(
                $child,
                ($level + 1),
                ($j == $activeChildrenCount - 1),
                ($j == 0),
                false,
                $outermostItemClass,
                $childrenWrapClass,
                $noEventAttributes
            );
            $j++;
        }
        if (!empty($htmlChildren)) {
            if ($childrenWrapClass) {
                $html[] = '<div class="' . $childrenWrapClass . '">';
            }
            $html[] = '<ul class="level' . $level . '">';
            $html[] = $htmlChildren;
            $html[] = '</ul>';
            if ($childrenWrapClass) {
                $html[] = '</div>';
            }
        }

        $html[] = '</li>';

        $html = implode("\n", $html);
        return $html;
    }
	public function mtdrawItem($category, $level = 0, $last = false)
    {
        return $this->_renderCategoryMenuItemHtml($category, $level, $last);
    }
    
	protected function _getItemPosition($level)
    {
		$itemLevelPositions = $this->_itemLevelPositions;
        if ($level == 0) {
            $zeroLevelPosition = isset($this->_itemLevelPositions[$level]) ? $this->_itemLevelPositions[$level] + 1 : 1;
			$itemLevelPositions = array();
			//$this->_itemLevelPositions = array();
            //$this->_itemLevelPositions[$level] = $zeroLevelPosition;
			$itemLevelPositions[$level] = $zeroLevelPosition;
        } elseif (isset($this->_itemLevelPositions[$level])) {
			$itemLevelPositions[$level]++;
            //$this->_itemLevelPositions[$level]++;
        } else {
			$itemLevelPositions[$level] = 1;
            //$this->_itemLevelPositions[$level] = 1;
        }

        $position = array();
        for($i = 0; $i <= $level; $i++) {
            if (isset($this->_itemLevelPositions[$i])) {
                $position[] = $this->_itemLevelPositions[$i];
            }
        }
        return implode('-', $position);
    }
    
    public function getCheckoutUrl()
    {
        return $this->helper('checkout/url')->getCheckoutUrl();
    }
    
    public function getCheckCartUrl()
    {
        return Mage::getUrl('checkout/cart');
    }
    
	
    
    
    /**
     * get value of the extension's configuration
     *
     * @return string
     */
    function getConfig($key, $default = "") {
        return (!isset($this->_config[$key]) || (isset($this->_config[$key]) && empty($this->_config[$key]))) ? $default : $this->_config[$key];
    }

    /**
     * overrde the value of the extension's configuration
     *
     * @return string
     */
    function setConfig($key, $value) {
        $this->_config[$key] = $value;
        return $this;
    }

    /**
     *
     */
    function parseParams($params) {
        $params = html_entity_decode($params, ENT_QUOTES);
        $regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
        preg_match_all($regex, $params, $matches);
        $paramarray = null;
        if (count($matches)) {
            $paramarray = array();
            for ($i = 0; $i < count($matches[1]); $i++) {
                $key = $matches[1][$i];
                $val = $matches[3][$i] ? $matches[3][$i] : ($matches[4][$i] ? $matches[4][$i] : $matches[5][$i]);
                $paramarray[$key] = $val;
            }
        }
        return $paramarray;
    }

    function isStaticBlock() {
        $name = isset($this->_config["name"]) ? $this->_config["name"] : "";
        if (!empty($name)) {
            $regex1 = '/static_(\s*)/';
            if (preg_match_all($regex1, $name, $matches)) {
                return true;
            }
        }
        return false;
    }

    function set($params) {
        $params = preg_split("/\n/", $params);
        foreach ($params as $param) {
            $param = trim($param);
            if (!$param)
                continue;
            $param = split("=", $param, 2);
            if (count($param) == 2 && strlen(trim($param[1])) > 0)
                $this->_config[trim($param[0])] = trim($param[1]);
        }
        $theme = $this->getConfig("theme");
        if ($theme != $this->_theme) {
            $mediaHelper = Mage::helper('ves_treemenu/media');
            $mediaHelper->addMediaFile("skin_css", "ves_treemenu/" . $theme . "/style.css");
        }
    }

    /**
     * render thumbnail image
     */
    public function buildThumbnail($imageArray, $twidth, $theight) {
        $thumbnailMode = $this->_config['thumbnailMode'];
        if ($thumbnailMode != 'none') {
            $imageProcessor = Mage::helper('ves_treemenu/vesimage');
            $imageProcessor->setStoredFolder();
            if (is_array($imageArray)) {
                foreach ($imageArray as $image) {
                    $thumbs[] = $imageProcessor->resize($image, $twidth, $theight);
                }
            } else {
                $thumbs = $imageProcessor->resize($imageArray, $twidth, $theight);
            }
            return $thumbs;
        }

        return $imageArray;
    }

    public function substring($producttext, $length = 100, $replacer = '...', $isStriped = true) {
        $producttext = strip_tags($producttext);
        if (strlen($producttext) <= $length) {
            return $producttext;
        }
        $producttext = substr($producttext, 0, $length);
        $posSpace = strrpos($producttext, ' ');
        return substr($producttext, 0, $posSpace) . $replacer;
    }

}
