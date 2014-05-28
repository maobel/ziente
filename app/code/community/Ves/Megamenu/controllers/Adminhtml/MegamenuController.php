<?php

/******************************************************
 * @package Ves Megamenu module for Magento 1.4.x.x and Magento 1.7.x.x
 * @version 1.0.0.1
 * @author http://landofcoder.com
 * @copyright   Copyright (C) December 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 * @license     GNU General Public License version 2
*******************************************************/
class Ves_Megamenu_Install extends Mage_Catalog_Model_Resource_Eav_Mysql4_Setup {
    public function install(){
        $sqldir = Mage::getModuleDir('sql', 'Ves_Megamenu');
        $sqldir = $sqldir.DS."ves_megamenu_setup".DS;
        $files = glob($sqldir.'*');
        foreach( $files as $dir ){
            if( preg_match("#.php#", $dir)){
                include( $dir );
            }
        }
    }
}
class Ves_Megamenu_Adminhtml_MegamenuController extends Mage_Adminhtml_Controller_Action {

    private $_DEFAULT_ROOT_CATE_ID = 1;

    protected function _initAction() {
        $this->_title($this->__('Manage Megamenu Items'))
                ->_title($this->__('Megamenu'));

        $this->loadLayout()
                ->_setActiveMenu('ves/megamenu')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {

        $this->_forward('edit');
    }

    public function index2Action() {
        $this->_forward('edit', null, null, array("position_type" => "left"));
    }

    public function editAction($position_type = "top") {
        /*
        $installer = new Ves_Megamenu_Install();
        $installer->install();
        */
        $position_type = $this->getRequest()->getParam('position_type');
        Mage::getSingleton('admin/session')
                ->setMegaPositionType($position_type);

        $id = $this->getRequest()->getParam('id');
        $megamenu = Mage::getModel('ves_megamenu/megamenu')->load($id);
        Mage::register('current_megamenu', $megamenu);
        
        $storeId = $this->getRequest()->getParam('store_id');
        $storeId = !empty($storeId) ? $storeId : 0;
        Mage::getSingleton('admin/session')
                ->setLastViewedStore($storeId);
        Mage::getSingleton('admin/session')
                ->setLastEditedMegamenu($megamenu->getId());

        
        if ( $megamenu->getId() ) {
            $this->_title( $this->__('Venus Megamenu - Edit '.$megamenu->getTitle() ));
        }

        $this->_initAction();
       
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->addItem('js', 'prototype/window.js')
                ->addItem('js_css', 'prototype/windows/themes/default.css')
                ->addCss('lib/prototype/windows/themes/magento.css')
                ->addItem('js', 'mage/adminhtml/variables.js')
                ->addItem('js', 'mage/adminhtml/wysiwyg/widget.js')
                ->addItem('js', 'lib/flex.js')
                ->addItem('js', 'lib/FABridge.js')
                ->addItem('js', 'mage/adminhtml/flexuploader.js')
                ->addItem('js', 'mage/adminhtml/browser.js')
                ;
        }
        $this->_addContent($this->getLayout()->createBlock('ves_megamenu/adminhtml_megamenu_edit'));
        
        $this->renderLayout();
    }
    
    public function addwidgetAction(){

        Mage::getSingleton('admin/session')
                ->setMegaPositionType("top");
        $id = $this->getRequest()->getParam('id');

        $widget = Mage::getModel('ves_megamenu/widget')->load($id);
        Mage::register('current_widget', $widget);
        $form            = '';
        $widget_selected = '';
        $this->_title( $this->__('Add & Edit Widget'));
        
        $this->_initAction();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->addItem('js', 'prototype/window.js')
                ->addItem('js_css', 'prototype/windows/themes/default.css')
                ->addCss('lib/prototype/windows/themes/magento.css')
                ->addItem('js', 'mage/adminhtml/variables.js')
                ->addItem('js', 'mage/adminhtml/wysiwyg/widget.js')
                ->addItem('js', 'lib/flex.js')
                ->addItem('js', 'lib/FABridge.js')
                ->addItem('js', 'mage/adminhtml/flexuploader.js')
                ->addItem('js', 'mage/adminhtml/browser.js')
                ;
        }

        $this->_addContent($this->getLayout()->createBlock('ves_megamenu/adminhtml_widget_edit'));
        $this->renderLayout();

        return;
    }
    public function delwidgetAction(){

        Mage::getSingleton('admin/session')
                ->setMegaPositionType("top");
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('ves_megamenu/widget');
        $model->setId($id)
                ->delete();
        Mage::getSingleton('adminhtml/session')
            ->addSuccess(Mage::helper('adminhtml')
            ->__('Widget was deleted successfully'));
        $store_id = Mage::helper("ves_megamenu")->getStoreId();
        if($store_id) {
            $this->_redirect('*/*/store_id/'.$store_id."/");
        } else {
            $this->_redirect('*/*/');
        }
        
    }
    public function savewidgetAction(){
        $data =  $this->getRequest()->getPost();
        if( isset($data['widget']) && isset($data['params']) ){

            $data['widget']['params'] = $data['params'];
            $post = array(
                'id'     => '',
                'params' => '',
                'type'   => ''
            );
            
            $data['widget'] = array_merge( $post, $data['widget'] ); 

            $image = isset($data['widget']['params']['image_path'])?$data['widget']['params']['image_path']:"";

           
            $data['widget']['store_id'] = $this->getRequest()->getParam('store_id');
            $id = $data['widget']['id'];

            unset( $data['widget']['id'] );

            $widget = Mage::getModel('ves_megamenu/widget')->load($id);

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    $uploader = new Varien_File_Uploader('image');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(true);
                    $path = Mage::getBaseDir('media') . DS . 'ves_megamenu';
                    $uploader->save($path, $_FILES['image']['name']);
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    return;
                }
                $image = 'ves_megamenu' . $uploader->getUploadedFileName();

            }  
     
            if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                $path = Mage::getBaseDir('media') . DS . $image;
                if( $image && file_exists($path) ){
                    @unlink($path);
                }
                $image = '';
            }
            $data['widget']['params']['image_path'] = $image;

            if( $data['widget']['params'] ){
                $data['widget']['params'] = base64_encode(serialize($data['widget']['params']));
            }

            $widget->setData( $data['widget'] );
            if($id){
                $widget->setId($id);
            }
            try {
                $widget->save();
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError('Could not save widget');
                Mage::throwException($e);
            }
            $this->_redirect('*/*/addwidget', array("done"=>1,"id"=>$widget->getId(),"wtype"=>$widget->getType()));
        }
        $this->_forward('addwidget');
    }
    
    private function _updateStore($menu_id = 0){
        if($menu_id > 1){
            $store_id = $this->getRequest()->getParam('store_id');
            $stores = !empty($store_id)?array($store_id):array(0);
            if($menu_id){
                Mage::getModel('ves_megamenu/megamenu')
                                    ->updateStores($stores, $menu_id);
            }
            return true;
        }
        
        return false;
    }

    private function _importCategory($category) {
        $category_id = $category->getId();
        if($category_id == $this->_DEFAULT_ROOT_CATE_ID ) {
          $parent = Mage::getModel('ves_megamenu/megamenu')->loadByTitle("ROOT");
          $p = $parent->getId()>0?$parent->getId():0;
          if($p)
            return; 
        }

        $category = $category->load($category->getId());
        $store_id = $this->getRequest()->getParam('store_id');
        $store_id = !empty($store_id)?$store_id:0;
        $stores = !empty($store_id)?array($store_id):array(0);
        $parentCategoryId = $category->getParentId();
        $parentMegamenu = Mage::getModel('ves_megamenu/megamenu')->loadByCategoryId($parentCategoryId, $store_id);
        $p = $parentMegamenu->getId()>0?$parentMegamenu->getId():0;

        if(!$p){
            $parentMegamenu = Mage::getModel('ves_megamenu/megamenu')->loadByTitle("ROOT");
            $p = $parentMegamenu->getId()>0?$parentMegamenu->getId():0;
        }

        if($category->getParentId() == 0){
            if($p == 0){
               $category->setName("ROOT"); 
            }
            
        }

        if($p == 1 && $store_id) {
            $store_info = Mage::getModel('core/store')->load($store_id);
            $name = $category->getName()." (".$store_info->getName().")";
        } else {
            $name = $category->getName();
        }

        $megamenu = Mage::getModel('ves_megamenu/megamenu')->load(null);
        $data = array("title" => $name,
                      "description" => $category->getDescription(),
                      "level" => ($category->getLevel() - 1),
                      "show_title" => 1,
                      "published" => 1,
                      "is_group" => 0,
                      "image" => "",
                      "widget_id" => 0,
                      "type_submenu" => "menu",
                      "type" => "category",
                      "left" => 0,
                      "right" => 0,
                      "item" => $category->getId(),
                      "parent_id" => $p,
                      "store_id" => $store_id,
                      "stores" => $stores);

        $megamenu->setData($data);

        try {
            $megamenu->save();
            if($data['level'] < 0){
                $megamenu->updateId(1);
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError('Could not import categories');
            Mage::throwException($e);
        }
    }

    public function importCategoriesAction() {
        $existsIds = array();
        $store_id = $this->getRequest()->getParam('store_id');
        $store_id = !empty($store_id)?$store_id:0;

        $existsCategory = Mage::getModel('ves_megamenu/megamenu')
                ->getCollection()
                ->addFieldToFilter('type', 'category')
                ->addStoreFilter($store_id);

        $menuIds = array();
        foreach ($existsCategory as $menu) {
            $existsIds[] = $menu->getItem();
            $menuIds[ $menu->getItem() ] = $menu->getId();
        }

        $collection = Mage::getModel('catalog/category')
                ->getCollection()
                ->setOrder('level', 'ASC');
        try {
            foreach ($collection as $category) {
                if (!in_array($category->getId(), $existsIds)){
                    $this->_importCategory($category);
                }
                else
                    $this->_updateStore($menuIds[$category->getId()]);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError('Could not import categories.');
            return false;
        }
    }


    public function deleteCategoriesAction(){
        $store_id = $this->getRequest()->getParam('store_id');
        $existsCategory = Mage::getModel('ves_megamenu/megamenu')
                ->getCollection()
                ->addFieldToFilter('type', 'category');
        if($store_id){
            $existsCategory =  $existsCategory->addStoreToFilter($store_id);
        }

        foreach ($existsCategory as $menu) {
            if((int)$menu->getLevel() >=0 ) {
                $menu->delete();
            }
        }
    }
    /*
    * Update position of menu
    */
    public function updateAction(){
        $data =  $this->getRequest()->getPost();
        $store_id = !isset($data['store_switcher'])?0:$data['store_switcher'];
        $dataString = isset($data['sortable'])?$data['sortable']:"";
        $data = Mage::helper("ves_megamenu")->stringtoURL($dataString);

        $root = $this->getRequest()->getParam('root');
        $child = array();
        foreach( $data as $id => $parentId ){
            if( $parentId <=0 ){
                $parentId = $root;
            }
            $child[$parentId][] = $id;
        }
        
        foreach( $child as $parentId => $menus ){
            $i = 1;
            foreach( $menus as $menuId ){
                $model = Mage::getModel('ves_megamenu/megamenu')->load($menuId);
                $model->setParentId((int)$parentId)
                        ->setPosition($i);

                $model->save();
                $i++;
            }
        }
       
        //Populate Resultarray
        $result = array("count"=>$i);

        Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('adminhtml')
                    ->__('<strong>'.$i.'</strong> item(s) was stored successfully!'));
        if($store_id) {
            $this->_redirect('*/*/index', array('store_id' => $store_id));
        } else {
           $this->_redirect('*/*/index');  
        }
       
    }
    /**
     * save data menu
     */
    public function saveAction() {
        $result = array();
        if ($data = $this->getRequest()->getPost()) {
            $data['store_id'] = !isset($data['store_switcher'])?0:$data['store_switcher'];
            $store_id = $data['store_id'];

            $save_mode = $this->getRequest()->getParam('save_mode');
            $id = 0;
            if ($id = $this->getRequest()->getParam('id')) {
                $model = Mage::getModel('ves_megamenu/megamenu')->load($id);
            }else{
                $model = Mage::getModel('ves_megamenu/megamenu')->load(null);
            }
            $image = $model->getImage();

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    $uploader = new Varien_File_Uploader('image');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $path = Mage::getBaseDir('media') . DS . 'ves_megamenu';
                    $uploader->save($path, $_FILES['image']['name']);
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    return;
                }
                $image = 'ves_megamenu' . $uploader->getUploadedFileName();
            }  
     
            if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                $path = Mage::getBaseDir('media') . DS . $image;
                if( $image && file_exists($path) ){
                    @unlink($path);
                }
                $image = '';
            }

            $data['megamenu']['image'] = $image;

            switch ($data['megamenu']['type']) {
                case 'category':
                    $data['megamenu']['item'] = isset($data['category'])?$data['category']:0;
                    break;
                case 'cms_page':
                    $data['megamenu']['item'] = isset($data['cms_page'])?$data['cms_page']:0;
                    break;
                case 'product':
                    $data['megamenu']['item'] = isset($data['megamenu']['product'])?$data['megamenu']['product']:0;
                    break;
                case 'static_block':
                    $data['megamenu']['item'] = isset($data['static_block'])?$data['static_block']:0;
                    break;
            }

            if( !isset($data['megamenu']['parent_id']) || empty($data['megamenu']['parent_id']) ){
                $data['megamenu']['parent_id'] = 1;
            }
            if ($data['megamenu']['parent_id']) {
                $level = Mage::getModel('ves_megamenu/megamenu')->load($data['megamenu']['parent_id'])->getLevel();
                $level += 1;
                $data['megamenu']['level'] = $level;
            } else {
                $root = Mage::getModel('ves_megamenu/megamenu')
                        ->getCollection()
                        ->addFieldToFilter('parent_id', 1)
                        ->addStoreFilter($data['store_id']);
                if (count($root)) {
                    $firstitem = $root->getFirstItem();
                    if ($firstitem->getId() != $id) {
                        Mage::getSingleton('adminhtml/session')->addError('Only one root Menu on Store');
                        if($save_mode == "save-edit"){
                            $result['redirect'] = $this->getUrl('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'store_id' => $store_id));
                            $this->getResponse()->setBody(
                                    '<script type="text/javascript">parent.window.location.href = "' . $result['redirect'] . '";</script>'
                            );  
                        }else{
                            $result['redirect'] = $this->getUrl('*/*/index', array('store_id' => $store_id));
                            $this->getResponse()->setBody(
                                    '<script type="text/javascript">parent.window.location.href = "' . $result['redirect'] . '";</script>'
                            );
                        }
                        
                        return;
                    }
                }
                $data['megamenu']['level'] = 0;
            }
            $data['megamenu']['stores'] = !empty($data['store_id'])?array($data['store_id']):array(0);

            $data_megamenu = array("title" => $data['megamenu']['title'],
                      "description" => $data['megamenu']['description'],
                      "level" => $data['megamenu']['level'],
                      "show_title" => $data['megamenu']['show_title'],
                      "published" => $data['megamenu']['published'],
                      "is_group" => $data['megamenu']['is_group'],
                      "image" => $data['megamenu']['image'],
                      "widget_id" => $data['megamenu']['widget_id'],
                      "submenu_content" => $data['megamenu']['submenu_content'],
                      "submenu_colum_width" => $data['megamenu']['submenu_colum_width'],
                      "colums" => $data['megamenu']['colums'],
                      "menu_class" => $data['megamenu']['menu_class'],
                      "type_submenu" => $data['megamenu']['type_submenu'],
                      "content_text" => $data['megamenu']['content_text'],
                      "type" => $data['megamenu']['type'],
                      "item" => $data['megamenu']['item'],
                      "url" => $data['megamenu']['url'],
                      "width" => $data['megamenu']['width'],
                      "parent_id" => $data['megamenu']['parent_id'],
                      "store_id" => $data['store_id'],
                      "stores" => $data['megamenu']['stores']);
            
            $model->setData($data_megamenu);

            if ($this->getRequest()->getParam('id'))
                $model->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('ves_megamenu')
                    ->__('Megamenu was successfully saved'));

                if($save_mode == "save-edit"){
                    $result['redirect'] = $this->getUrl('*/*/edit', array('id' => $model->getId(), 'store_id' => $store_id));

                }else{
                    $result['redirect'] = $this->getUrl('*/*/index', array('store_id' => $store_id));
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $result['redirect'] = $this->getUrl('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                if($save_mode == "save-edit"){
                    $result['redirect'] = $this->getUrl('*/*/edit', array('id' => $model->getId(), 'store_id' => $store_id));
                }else{
                    $result['redirect'] = $this->getUrl('*/*/index', array('store_id' => $store_id));
                }
            }
        } else {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('ves_megamenu')
                ->__('Could not find anymegamenu to save'));
            if($save_mode == "save-edit"){
                $result['redirect'] = $this->getUrl('*/*/edit', array('id' => $model->getId(), 'store_id' => $store_id));
            }else{
                $result['redirect'] = $this->getUrl('*/*/index', array( 'store_id' => $store_id));
            }
        }
        
        $this->getResponse()->setBody(
                '<script type="text/javascript">parent.window.location.href = "' . $result['redirect'] . '";</script>'
        );
    }

    /**
     * Delete menu item and all of submenu if have
     */
    public function deleteAction($menuid = null) {
        $menuid = $menuid === null ? $this->getRequest()->getParam('id') : $menuid;
        $store_id = $this->getRequest()->getParam('store_id');
        $store_id = !empty($store_id)?$store_id:0;
        if ($menuid) {
            try {

                $model = Mage::getModel('ves_megamenu/megamenu');
                $test = $model->load($menuid)->hasChild();
                $title = $model->load($menuid)->getTitle();

                if ($model->load($menuid)->hasChild()) {
                    $childs = $model->load($menuid)->getChildItem();
                    foreach ($childs as $child) {
                        $this->deleteAction($child->getId());
                    }
                } else {
                    $model->load($menuid)
                            ->delete();
                }
                $model->load($menuid)
                        ->delete();

                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('adminhtml')
                    ->__('Item was deleted successfully'));
                if($store_id) {
                   $this->_redirect('*/*/', array('store_id' => $store_id)); 
                } else {
                   $this->_redirect('*/*/');
                }
               
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if($store_id) {
                   $this->_redirect('*/*/edit', array('id' => $menuid, 'store_id' => $store_id));
                } else {
                   $this->_redirect('*/*/edit', array('id' => $menuid));
                }
            }
        }
        if($store_id) {
           $this->_redirect('*/*/', array('store_id' => $store_id)); 
        } else {
           $this->_redirect('*/*/');
        }
    }

    /**
     * Live Edit Mega Menu Action
     */
    public function liveeditAction(){
        $position_type = $this->getRequest()->getParam('position_type');
        Mage::getSingleton('admin/session')
                ->setMegaPositionType($position_type);

        $this->_initAction();
       
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->addItem('js', 'prototype/window.js')
                ->addItem('js_css', 'prototype/windows/themes/default.css')
                ->addCss('lib/prototype/windows/themes/magento.css')
                ->addItem('js', 'mage/adminhtml/variables.js')
                ->addItem('js', 'mage/adminhtml/wysiwyg/widget.js')
                ->addItem('js', 'lib/flex.js')
                ->addItem('js', 'lib/FABridge.js')
                ->addItem('js', 'mage/adminhtml/flexuploader.js')
                ->addItem('js', 'mage/adminhtml/browser.js')
                ;
        }

        $this->_addContent($this->getLayout()->createBlock('ves_megamenu/adminhtml_megamenu_liveedit'));
        $this->renderLayout();

        return;
    }
 

    /**
     *  Ajax Live Save Action.
     */
    public function livesaveAction(){
        $this->_forward('ajxgenmenu');
    }

    /**
     * Ajax Render List Tree Mega Menu Action
     */
    public function ajxgenmenuAction( ){ 
        $parent                 = '1';
        $store_id = $this->getRequest()->getParam('store_id');
        $store_id = !empty($store_id)?$store_id:0;

         /* unset mega menu configuration */
        $action_reset = $this->getRequest()->getParam("reset");
        if( $action_reset == "1" ){
           if($store_id) {
             Mage::getConfig()->saveConfig('ves_megamenu/ves_megamenu/params', "", 'stores', $store_id );
           } else {
             Mage::getConfig()->saveConfig('ves_megamenu/ves_megamenu/params', "" );
           }
          
           Mage::getConfig()->reinit();
           Mage::app()->reinitStores();
        }
        $ajaxmenu_block = $this->getLayout()->createBlock('ves_megamenu/adminhtml_megamenu_ajaxgenmenu');

        $this->getResponse()->setBody( $ajaxmenu_block->toHtml() );

        return;
    }
    /**
     * Ajax Menu Information Action
     */
    public function ajxmenuinfoAction(){
        $params = $this->getRequest()->getParam('params');
        if( $params ) {
           $store_id = $this->getRequest()->getParam('store_id');
           $store_id = !empty($store_id)?$store_id:0;
           $params = trim(html_entity_decode($params));

           if($store_id) {
                Mage::getConfig()->saveConfig('ves_megamenu/ves_megamenu/params', $params, 'stores', $store_id );
           } else {
                Mage::getConfig()->saveConfig('ves_megamenu/ves_megamenu/params', $params );
           }
           Mage::getConfig()->reinit();
           Mage::app()->reinitStores();
        }

        return $this->ajxgenmenuAction();
        
    }
    public function renderwidgetAction(){
         /* unset mega menu configuration */
        $widget_html = Mage::helper("ves_megamenu")->renderwidget();
        if($widget_html){
            $this->getResponse()->setBody( $widget_html );
        }else{
            exit();
        }
        return;
    }
}