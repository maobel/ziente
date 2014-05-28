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
class Ves_Brand_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action {
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('ves_brand/brand');

        return $this;
    }
	
	
	/**
	 * index action
	 */ 
    public function indexAction() {
		
		$this->_title($this->__('Brands Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ves_brand/adminhtml_brand') );
        $this->renderLayout();
		
    }
	
	public function editAction(){
		$this->_title($this->__('Edit Record'));
		$id     = $this->getRequest()->getParam('id');
        $_model  = Mage::getModel('ves_brand/brand')->load( $id );

		Mage::register('brand_data', $_model);
        Mage::register('current_brand', $_model);
		
		$this->loadLayout();
	    $this->_setActiveMenu('ves_brand/brand');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Brand Manager'), Mage::helper('adminhtml')->__('Brand Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Brand'), Mage::helper('adminhtml')->__('Add Brand'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('ves_brand/adminhtml_brand_edit'))
                ->_addLeft($this->getLayout()->createBlock('ves_brand/adminhtml_brand_edit_tabs'));
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
			$model = Mage::getModel('ves_brand/brand');
			if($data['identifier'] == '' || !isset($data['identifier']))
			$data['identifier'] = Mage::helper('ves_brand')->formatUrlKey($data['title']);
			
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('file');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('media') . '/vesbrand/';
					$uploader->save($path, $_FILES['file']['name'] );
					
				} catch (Exception $e) {
			  
				}
				//this way the name is saved in DB
				$data['file'] = 'vesbrand/' .preg_replace("#\s+#","_", $_FILES['file']['name']);
				$sizes = array( "brand_imagesize" => "l" );
				foreach( $sizes as $key => $size ){
					$c = Mage::getStoreConfig( 'ves_brand/general_setting/'.$key );
					$tmp = explode( "x", $c );
					if( count($tmp) > 0 && (int)$tmp[0] ){
						Mage::helper('ves_brand')->resizeImage( $data['file'], $size, (int)$tmp[0], (int)$tmp[1] );
					}
				}		
			}else{
				$data['file'] = $data['file']['value'];
			}
			
			
			if(isset($_FILES['icon']['name']) && $_FILES['icon']['name'] != '') {					
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('icon');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('media') . '/vesbrand/icon/';
					$uploader->save($path, $_FILES['icon']['name'] );
					
				} catch (Exception $e) {
			  
				}
				//this way the name is saved in DB
				$data['icon'] = 'vesbrand/icon/' .preg_replace("#\s+#","_", $_FILES['icon']['name']);
				 	
			}else{
				$data['icon'] = $data['icon']['value'];
			}

			$data['stores'] = $this->getRequest()->getParam('stores');
			
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			try {
				$model->save();
				$resroute = Mage::getStoreConfig('ves_brand/general_setting/route');
				$extension = ".html";
				//Save to Url Rewite
				Mage::getModel('core/url_rewrite')->loadByIdPath('venusbrand/brand/'.$model->getId())
							->setIdPath('venusbrand/brand/'.$model->getId())
							->setRequestPath($resroute .'/'.$model->getIdentifier().$extension  )
							->setTargetPath('venusbrand/brand/view/id/'.$model->getId())
							->save();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ves_brand')->__('Brand was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				
				// save rewrite url
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_brand')->__('Unable to find cat to save'));
		$this->_redirect('*/*/');
    }
	
	public function imageAction() {
        $result = array();
        try {
            $uploader = new Ves_Brand_Media_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                    Mage::getSingleton('ves_brand/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('ves_brand/config')->getMediaUrl($result['file']);
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
				$model = Mage::getModel('ves_brand/brand');
				 
				$model->setId($this->getRequest()->getParam('id'));
				
				Mage::getModel('core/url_rewrite')->loadByIdPath('ves_brand/brand/'.$model->getId())->delete();
				
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
			$collection = Mage::getModel('ves_brand/brand')->getCollection();
			$sizes = array( "brand_imagesize" => "l" );
			
			foreach( $collection as $post ){
				if( $post->getFile() ){
					
					foreach( $sizes as $key => $size ){
						$c = Mage::getStoreConfig( 'ves_brand/general_setting/'.$key );
						$tmp = explode( "x", $c );
						if( count($tmp) > 0 && (int)$tmp[0] ){
							
							Mage::helper('ves_brand')->resizeImage( $post->getFile(), $size, (int)$tmp[0], (int)$tmp[1] );
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
	
	public function massRewriteAction(){
		try {
			$collection = Mage::getModel('ves_brand/brand')->getCollection();
			$resroute = Mage::getStoreConfig('ves_brand/general_setting/route');
			$extension = ".html";
			foreach( $collection as $model ){
				Mage::getModel('core/url_rewrite')->loadByIdPath('venusbrand/brand/'.$model->getId())
							->setIdPath('venusbrand/brand/'.$model->getId())
							->setRequestPath($resroute .'/'.$model->getIdentifier().$extension  )
							->setTargetPath('venusbrand/brand/view/id/'.$model->getId())
							->save();
			}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rewrite URLs Of All Brand are resized successful'));
		} catch ( Exception $e ) {
			  Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		
		$this->_redirect('*/*/');	
	}
	
	 public function massStatusAction() {
        $IDList = $this->getRequest()->getParam('brand');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select record(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getSingleton('ves_brand/brand')
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
        $IDList = $this->getRequest()->getParam('brand');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select record(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('ves_brand/brand')
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