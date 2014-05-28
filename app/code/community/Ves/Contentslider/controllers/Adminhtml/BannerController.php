<?php 
 /*------------------------------------------------------------------------
  # VenusTheme Brand Module 
  # ------------------------------------------------------------------------
  # author:    VenusTheme.Com
  # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
  # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.venustheme.com
  # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/
class Ves_Contentslider_Adminhtml_BannerController extends Mage_Adminhtml_Controller_Action {
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('ves_contentslider/banner');

        return $this;
    }
	
	
	/**
	 * index action
	 */ 
    public function indexAction() {
		
		$this->_title($this->__('Banner Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ves_contentslider/adminhtml_banner') );
        $this->renderLayout();
		
    }

    /**
	 * typo action
	 */ 
    public function typoAction() {
		
		$this->_title($this->__('Typo Management'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ves_contentslider/adminhtml_banner_typo') );

        $block = $this->getLayout()->createBlock('ves_contentslider/adminhtml_banner_typo');
        echo $block->renderView();
       // $this->renderLayout();
		
    }
	
	public function editAction(){
		$this->_title($this->__('Edit Record'));
		$id     = $this->getRequest()->getParam('id');
		$id 	= $id?$id: 0;
        $_model  = Mage::getModel('ves_contentslider/banner')->load( $id );

		Mage::register('banner_data', $_model);
        Mage::register('current_banner', $_model);
		
		$this->loadLayout();
	    $this->_setActiveMenu('ves_contentslider/banner');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Banner Manager'), Mage::helper('adminhtml')->__('Banner Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Banner'), Mage::helper('adminhtml')->__('Add Banner'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('ves_contentslider/adminhtml_banner_edit'));

		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
		    $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
	    }
        $this->renderLayout();
	}
	
	public function addAction(){
		$this->_forward('edit');
	}
	
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {	    
			$model = Mage::getModel('ves_contentslider/banner');
			$action = $this->getRequest()->getParam('action');

			$banner_data = isset($data['banner'])?$data['banner']:'';
			$banner_id = $this->getRequest()->getParam('banner_id');

			$banner_image_data = isset($data['banner_image'])?$data['banner_image']:array();
			
			$banner_data['stores'] = $this->getRequest()->getParam('stores');
			
			if($banner_image_data) {
				foreach($banner_image_data as $key=>$value) {
					if(isset($value['clear_image']) && $value['clear_image']) {
						$path = Mage::getBaseDir('media') . '/ves_contentslider/upload/';
						$banner_image_data[$key]['image'] = "";
						@unlink(Mage::getBaseDir('media').$value['image']);
					}


					if(isset($_FILES['image'.$key]['name']) && $_FILES['image'.$key]['name'] != '') {

						try {
							/* Starting upload */	
							$uploader = new Varien_File_Uploader('image'.$key);
							$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
							$uploader->setAllowRenameFiles(false);
							$uploader->setFilesDispersion(false);
							$path = Mage::getBaseDir('media') . '/ves_contentslider/upload/';
							$uploader->save($path, $_FILES['image'.$key]['name'] );	
							
						} catch (Exception $e) {
					  		Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						}
						//this way the name is saved in DB
						$banner_image_data[$key]['image'] = 'ves_contentslider/upload/' .preg_replace("#\s+#","_", $_FILES['image'.$key]['name']);
						
					 }
				}
			}


			$banner_data['params']["banner_images"] = $banner_image_data;


			$banner_data['params'] = base64_encode(serialize($banner_data['params']));

			$model->setData($banner_data);

			if($banner_id)
                $model->setId($banner_id);
            
			try {
				$model->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ves_contentslider')->__('Banner was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}

				if($action == "save_stay"){
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                }else{
                    $this->_redirect('*/*/');
                }
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($banner_data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('banner_id')));
				return;
			}
		}
		
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_contentslider')->__('Unable to find cat to save'));
		$this->_redirect('*/*/');
    }
	
	public function imageAction() {
        $result = array();
        try {
            $uploader = new Venustheme_Brand_Media_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                    Mage::getSingleton('ves_contentslider/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('ves_contentslider/config')->getMediaUrl($result['file']);
            $result['cookie'] = array(
                    'name'     => session_name(),
                    'value'    => $this->_getSession()->getSessionId(),
                    'lifetime' => $this->_getSession()->getCookieLifetime(),
                    'path'     => $this->_getSession()->getCookiePath(),
                    'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
	/**
	 * Delete
	 */
	 public function deleteAction() {
	 
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('ves_contentslider/banner');
				 
				$model->setId($this->getRequest()->getParam('id'));
				
				$model->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('This Brand Was Deleted Done'));
				$this->_redirect('*/*/');
			
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
    }
	
	public function massResizeAction(){
		try {
			$collection = Mage::getModel('ves_contentslider/banner')->getCollection();
			$sizes = array( "brand_imagesize" => "l" );
			
			foreach( $collection as $post ){
				if( $post->getFile() ){
					
					foreach( $sizes as $key => $size ){
						$c = Mage::getStoreConfig( 'ves_contentslider/general_setting/'.$key );
						$tmp = explode( "x", $c );
						if( count($tmp) > 0 && (int)$tmp[0] ){
							
							Mage::helper('ves_contentslider')->resizeImage( $post->getFile(), $size, (int)$tmp[0], (int)$tmp[1] );
						}
					}	
				}
			}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Images Of All Brands are resized successful'));
		} catch ( Exception $e ) {
			  Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}
	
	 public function massStatusAction() {
        $IDList = $this->getRequest()->getParam('banner');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select record(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getSingleton('ves_contentslider/banner')
                            ->setIsMassStatus(true)
                            ->load($itemId)
                            ->setIsActive($this->getRequest()->getParam('status'))
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($IDList))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	public function massDeleteAction() {
        $IDList = $this->getRequest()->getParam('banner');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select record(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('ves_contentslider/banner')
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
	
}
?>