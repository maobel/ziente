<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Ves * @package     Ves_Tempcp
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Theme controller
 *
 * @category   Ves
 * @package     Ves_Tempcp
 * @author      
 */
class Ves_Tempcp_Adminhtml_ThemeController extends Mage_Adminhtml_Controller_Action {
    var $destination_filename = "ves_themes.csv";
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('tempcp');

        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('Ves Themes Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ves_tempcp/adminhtml_theme'));
        $this->renderLayout();
		
    }

    public function addAction() {
        $this->_title($this->__('New Theme'));
		
        $_model  = Mage::getModel('ves_tempcp/theme');
        Mage::register('theme_data', $_model);
        Mage::register('current_theme', $_model);
		
        $this->_initAction();
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Theme Manager'), Mage::helper('adminhtml')->__('Theme Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Theme'), Mage::helper('adminhtml')->__('Add Theme'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }

        $this->_addContent($this->getLayout()->createBlock('ves_tempcp/adminhtml_theme_add'));
		
        $this->renderLayout();
		
    }

    public function editAction() {
        $themeId     = $this->getRequest()->getParam('id');

		$theme = Mage::helper('ves_tempcp/theme');
        $theme->getTheme( $themeId );
        
        if ( $theme->theme_id ) {
            $this->_title( $this->__('Venus Theme Control Panel - Edit '.$theme->title ));
			
            $this->_initAction();
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Ves Theme Manager'), Mage::helper('adminhtml')->__('Ves Theme Manager'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Theme'), Mage::helper('adminhtml')->__('Edit Theme'));
			
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
            $this->_addContent($this->getLayout()->createBlock('ves_tempcp/adminhtml_theme_edit'));
			
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_tempcp')->__('The theme does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function customizeAction() {
        $themeId     = $this->getRequest()->getParam('id');

        $theme = Mage::helper('ves_tempcp/theme');
        $theme->getTheme( $themeId );
        
        if ( $theme->theme_id ) {
            $this->_title( $this->__('Venus Theme Control Panel - Live Customize Theme '.$theme->title ));
            
            $this->_initAction();
            
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

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

            $customize_block = $this->getLayout()->createBlock('ves_tempcp/adminhtml_theme_customize');

            $this->getResponse()->setBody( $customize_block->toHtml() );

            return;
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_tempcp')->__('The theme does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function saveCustomizeAction() {
        if ($data = $this->getRequest()->getPost()) {

            $selectors = $data['customize'];
            $matches = $data["customize_match"];
            $theme = $this->getRequest()->getParam('theme');
            $id = (int)$this->getRequest()->getParam('id');

            $output = '';

            $cache = array();

            $themeCustomizePath = $this->getThemeCustomizePath( $theme );
            foreach( $selectors as $match => $customizes  ){
                $output .= "\r\n/* customize for $match */ \r\n";
                foreach( $customizes as $key => $customize ){
                    if( isset($matches[$match]) && isset($matches[$match][$key]) ){
                        $tmp = explode("|", $matches[$match][$key]);

                        if( trim($customize) ) {
                            $output .= $tmp[0]." { ";
                            if( strtolower(trim($tmp[1])) == 'background-image'){
                                $output .= $tmp[1] . ':url('.$customize .')';   
                            }elseif( strtolower(trim($tmp[1])) == 'font-size' ){
                                $output .= $tmp[1] . ':'.$customize.'px';   
                            }else {
                                $output .= $tmp[1] . ':#'.$customize;   
                            }
                            
                            $output .= "} \r\n";
                        }
                        $cache[$match][] =  array('val'=>$customize,'selector'=>$tmp[0] );
                    }
                }   

            }
             
            if(  !empty($data['saved_file'])  ){
                if( $data['saved_file'] && file_exists($themeCustomizePath.$data['saved_file'].'.css') ){
                    unlink( $themeCustomizePath.$data['saved_file'].'.css' );
                }
                if( $data['saved_file'] && file_exists($themeCustomizePath.$data['saved_file'].'.json') ){
                    unlink( $themeCustomizePath.$data['saved_file'].'.json' );
                }
                $nameFile = $data['saved_file'];
            }else {
                if( isset($data['newfile']) && empty($data['newfile']) ){
                    $nameFile = time();
                }else {
                    $nameFile = preg_replace("#\s+#", "-", trim($data['newfile']));
                }
            }
        
            if( $data['action-mode'] != 'save-delete' ){
                
                if( !empty($output) ){
                    $this->writeToCache( $themeCustomizePath, $nameFile, $output );
                }
                if( !empty($cache) ){
                    $this->writeToCache(  $themeCustomizePath, $nameFile, json_encode($cache),"json" );
                }

             }  
              
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('Theme Profile Was Saved Successfully.'));
            
            $this->_redirect('*/adminhtml_theme/customize', array("id"=>$id));
        }
    }

    public function uploadCsvAction(){
        $this->loadLayout();
        $block = $this->getLayout()->createBlock('ves_tempcp/adminhtml_cms_enhanced_theme_upload');
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }
    public function importCsvAction(){
        // get uploaded file
        $filepath = $this->getUploadedFile();

        if ($filepath != null) {
            try {
                // import into model
                Mage::getSingleton('ves_tempcp/import_theme')->process($filepath);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('CSV Imported Successfully'));

            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('An Error occured importing CSV.'));
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } // end if
            
        } // end

        // redirect to grid page.
        $this->_redirect('*/*/index');
    }
    private function getThemeCustomizePath($theme = "") {
        $customize_path = Mage::getBaseDir('skin')."/frontend/default/".$theme."/css/customize/";
        if(!file_exists($customize_path)) {
            $file = new Varien_Io_File();
            $file->mkdir($customize_path);
            $file->close();
        }
        return $customize_path;
    }

   /**
     *
     */
    public function writeToCache( $folder, $file, $value, $e='css' ){
        $file = $folder  . preg_replace('/[^A-Z0-9\._-]/i', '', $file).'.'.$e ;

        $flocal = new Varien_Io_File();
        $flocal->write($file, $value);
        $flocal->close();
    }
    /**
     * Handles CSV upload
     * @return string $filepath
     */
    private function getUploadedFile() {
        $filepath = null;

        if(isset($_FILES['importfile']['name']) and (file_exists($_FILES['importfile']['tmp_name']))) {
            try {
                $uploader = new Varien_File_Uploader('importfile');
                $uploader->setAllowedExtensions(array('csv','txt')); // or pdf or anything
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $path = Mage::helper('ves_tempcp/data')->getImportPath2();
                $uploader->save($path, $this->destination_filename);
                $filepath = $path . $this->destination_filename;

            } catch(Exception $e) {
                // log error
                Mage::logException($e);
            } // end if

        } // end if

        return $filepath;

    }
    public function importAction(){
        // get uploaded file
        $type = $this->getRequest()->getParam('type');
        $theme = $this->getRequest()->getParam('theme');
        $id = $this->getRequest()->getParam('id');
        $file = "";
        $import_type = "static_block";
        switch ($type) {
            case 'staticblock':
            case 'static_block':
            case 'static_blocks':
                $file = "static_blocks.csv";
                break;
            case 'pages':
                $file = "cms_pages.csv";
                $import_type = "cms_page";
                break;
            default:
                break;
        }
        $filepath = $this->getCsvFile($file, $theme);

        if ($filepath != null) {
            try {
                // import into model
                if($import_type == "cms_page"){
                    Mage::getSingleton('ves_tempcp/import_page')->process($filepath);
                }else if($import_type == "static_block"){
                    Mage::getSingleton('ves_tempcp/import_block')->process($filepath);
                }
                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('cms')->__('CSV Imported Successfully'));

            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('An Error occured importing CSV.'));
            }
        } // end

        // redirect to grid page.
        $this->_redirect('*/adminhtml_theme/edit', array("id"=>$id));
    }
    /**
     * Exports a CSV file
     */
    public function exportAction() {
        $type = $this->getRequest()->getParam('type');
        $theme = $this->getRequest()->getParam('theme');
        $id = $this->getRequest()->getParam('id');
        $fileName = "";
        $content = "";
        switch ($type) {
            case 'staticblock':
            case 'static_block':
            case 'static_blocks':
                $fileName = "static_blocks.csv";
                $content    = $this->getLayout()->createBlock('ves_tempcp/adminhtml_cms_enhanced_block_grid')->getCsvFile();
                break;
            
            default:
            case 'pages':
                $fileName = "cms_pages.csv";
                $content    = $this->getLayout()->createBlock('ves_tempcp/adminhtml_cms_enhanced_page_grid')->getCsvFile();
                break;
        }

        $this->_prepareDownloadResponse($fileName, $content);

    }

    public function exportCsvAction(){
        $fileName = "ves_themes.csv";
        $content    = $this->getLayout()->createBlock('ves_tempcp/adminhtml_cms_enhanced_theme_grid')->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);

    }

    public function setdefaultAction(){
       $this->updateStatus(1);
    }

    public function removedefaultAction(){
       $this->updateStatus(0);
    }

    public function ajaxsaveAction(){
        
    }
    public function saveAction() {
        $action = "";
        if ($data = $this->getRequest()->getPost()) {
            $action = $this->getRequest()->getParam('action');
			$themecontrol = isset($data['themecontrol'])?$data['themecontrol']:'';

			if(isset($_FILES['bg_image']['name']) && $_FILES['bg_image']['name'] != '') {		
					try {	
						/* Starting upload */	
						$uploader = new Varien_File_Uploader('bg_image');
						
						// Any extention would work
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(false);
						
						// Set the file upload mode 
						// false -> get the file directly in the specified folder
						// true -> get the file in the product like folders 
						//	(file.jpg will go in something like /media/f/i/file.jpg)
						$uploader->setFilesDispersion(false);
								
						// We set media as the upload dir
						$path = Mage::getBaseDir('media') . '/ves_tempcp/upload/';
						$uploader->save($path, $_FILES['bg_image']['name'] );
						
					} catch (Exception $e) {
				        
					}
					//this way the name is saved in DB
					$themecontrol['bg_image'] = 'ves_tempcp/upload/' . $_FILES['bg_image']['name'];
			}
            $data = array();
            $custom_css = "";
            if(isset($themecontrol['custom_css'])) { 
                $custom_css = $themecontrol['custom_css'];
                unset($themecontrol['custom_css']);
            }
            $theme_id = $this->getRequest()->getParam('id');
            $data['params'] = base64_encode(serialize($themecontrol));
            $data['group'] = isset($themecontrol['default_theme'])?$themecontrol['default_theme']:'ves default theme';
            $data['is_default'] = 1;
            $data['stores'] = $this->getRequest()->getParam('stores');
            $_model = Mage::getModel('ves_tempcp/theme')->load($theme_id);
            /*
			if(empty($data['theme_id']) && $_model->checkExistsByGroup($data['group'])){

                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_tempcp')->__('The Theme is exist, you can not create a same theme!'));
            */
            //    $this->_redirect('*/*/');
            /*    return;

            }*/

            $_model->setData($data);

            if($theme_id)
                $_model->setId($theme_id);
            try {

                $_model->save();

                /*Save custom css*/
                $custom_css_path = Mage::getBaseDir('skin')."/frontend/default/".$_model->getGroup()."/css/local/";
                if(!file_exists($custom_css_path)) {
                    $file = new Varien_Io_File();
                    $file->mkdir($custom_css_path);
                    $file->close();
                }
                $this->writeToCache( $custom_css_path, "custom", $custom_css );
                /*End save custom css*/

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ves_tempcp')->__('Theme was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return;
                }
                if($action == "save_stay"){
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                }else{
                    $this->_redirect('*/*/');
                }
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_tempcp')->__('Unable to find theme to save'));
        $this->_redirect('*/*/');
        
    }

    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('ves_tempcp/theme');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Theme was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massSetdefaultAction(){
       $this->massUpdateStatus(1);
    }

     public function massRemovedefaultAction(){
       $this->massUpdateStatus(0);
    }

    public function massDeleteAction() {
        $IDList = $this->getRequest()->getParam('theme');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select theme(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('ves_tempcp/theme')
                            ->setIsMassDelete(true)->load($itemId);
                    $_model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($IDList)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massCloneAction() {
        $IDList = $this->getRequest()->getParam('theme');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select theme(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getSingleton('ves_tempcp/theme')
                            ->load($itemId);
                    $_data = $_model->getData();
                    $_data['stores'] = $_model->getStoreId();
                    unset($_data['theme_id']);
                    $_clone_model = Mage::getSingleton('ves_tempcp/theme');
                    $_clone_model->setData($_data);
                    $_clone_model->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully clone', count($IDList))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function massUpdateStatus( $status = 0){
        $IDList = $this->getRequest()->getParam('theme');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select theme(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('ves_tempcp/theme')->load($itemId);
                    $stores = $_model->getStoreId();
                    $_model->setIsDefault( $status ) ;
                    $_model->setStores($stores);
                    $_model->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully changed Default', count($IDList)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function updateStatus($status = 0){
        $themeId     = $this->getRequest()->getParam('id');

        $_model = Mage::getModel('ves_tempcp/theme')
                ->load($themeId);
        $stores = $_model->getStoreId();        
        $_model->setIsDefault( $status ) ;
        $_model->setStores($stores);

        if ( $_model->save() ) {
            
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ves_tempcp')->__('Changed Is Default theme successfully.'));
            $this->_redirect('*/*/');
        }else{
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_tempcp')->__('The theme can not save.'));
            $this->_redirect('*/*/');
        }
    }

   
    /**
     * Handles CSV upload
     * @return string $filepath
     */
    protected function getCsvFile($file = "static_block.csv", $theme = "") {
        $filepath = null;
        $path = Mage::helper('ves_tempcp/data')->getImportPath($theme);
        $filepath = $path . $file;
    
        return $filepath;

    }

    protected function _title($text = null, $resetIfExists = true)
    {
        if (is_string($text)) {
            $this->_titles[] = $text;
        } elseif (-1 === $text) {
            if (empty($this->_titles)) {
                $this->_removeDefaultTitle = true;
            } else {
                array_pop($this->_titles);
            }
        } elseif (empty($this->_titles) || $resetIfExists) {
            if (false === $text) {
                $this->_removeDefaultTitle = false;
                $this->_titles = array();
            } elseif (null === $text) {
                $this->_removeDefaultTitle = true;
                $this->_titles = array();
            }
        }
        return $this;
    }
}