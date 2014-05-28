<?php 
 /*------------------------------------------------------------------------
  # Ves Blog Module 
  # ------------------------------------------------------------------------
  # author:    Venustheme.Com
  # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
  # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.venustheme.com
  # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/
class Ves_Blog_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action {
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('ves_blog/category');

        return $this;
    }
	
	
	/**
	 * index action
	 */ 
    public function indexAction() {
		
		$this->_title($this->__('Categories Manager'));
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('ves_blog/adminhtml_category') );
        $this->renderLayout();
		
    }
	
	public function editAction(){
		$this->_title($this->__('Edit Record'));
		$id     = $this->getRequest()->getParam('id');
        $_model  = Mage::getModel('ves_blog/category')->load( $id );

		Mage::register('category_data', $_model);
        Mage::register('current_category', $_model);
		
		$this->loadLayout();
	    $this->_setActiveMenu('ves_blog/category');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Blog Manager'), Mage::helper('adminhtml')->__('Blog Manager'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Add Category'), Mage::helper('adminhtml')->__('Add Category'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('ves_blog/adminhtml_category_edit'))
                ->_addLeft($this->getLayout()->createBlock('ves_blog/adminhtml_category_edit_tabs'));
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
		    $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
	    }
        $this->renderLayout();
	}
	
	public function addAction(){
		$this->_redirect('*/*/edit');
	}
	
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {	    
			$model = Mage::getModel('ves_blog/category');
			if($data['identifier'] == '' || !isset($data['identifier']))
			$data['identifier'] = Mage::helper('ves_blog')->formatUrlKey($data['title']);
			
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {					
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('file');
					
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . '/blog/category';
					$uploader->save($path, $_FILES['file']['name'] );
					
				} catch (Exception $e) {
			  
				}
				//this way the name is saved in DB
				$data['file'] = 'blog/category/' .preg_replace("#\s+#","_", $_FILES['file']['name']);
				$sizes = array( "catimagesize" => "l" );
				foreach( $sizes as $key => $size ){
					$c = Mage::getStoreConfig( 'ves_blog/category_setting/'.$key );
					$tmp = explode( "x", $c );
					if( count($tmp) > 0 && (int)$tmp[0] ){
						Mage::helper('ves_blog')->resizeImage( $data['file'], $size, (int)$tmp[0], (int)$tmp[1] );
					}
				}		
				
			}else{
				$data['file'] = $data['file']['value'];
			}
		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			try {
				$model->save();
				$resroute = Mage::getStoreConfig('ves_blog/general_setting/route');
				$extension = ".html";
				//Save to Url Rewite
				Mage::getModel('core/url_rewrite')->loadByIdPath('venusblog/category/'.$model->getId())
							->setIdPath('venusblog/category/'.$model->getId())
							->setRequestPath($resroute .'/'.$model->getIdentifier().$extension  )
							->setTargetPath('venusblog/category/view/id/'.$model->getId())
							->save();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ves_blog')->__('Category was successfully saved'));
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
		
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ves_blog')->__('Unable to find cat to save'));
		$this->_redirect('*/*/');
    }
	
	public function imageAction() {
        $result = array();
        try {
            $uploader = new Ves_Blog_Media_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                    Mage::getSingleton('ves_blog/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('ves_blog/config')->getMediaUrl($result['file']);
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
				$model = Mage::getModel('ves_blog/category');
				 
				$model->setId($this->getRequest()->getParam('id'));
				
				Mage::getModel('core/url_rewrite')->loadByIdPath('ves_blog/category/'.$model->getId())->delete();
				
				$model->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('This Category Was Deleted Done'));
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
			$collection = Mage::getModel('ves_blog/category')->getCollection();
			
			foreach( $collection as $post ){
				if( $post->getFile() ){
					$sizes = array( "catimagesize" => "l" );
					foreach( $sizes as $key => $size ){
						$c = Mage::getStoreConfig( 'ves_blog/category_setting/'.$key );
						$tmp = explode( "x", $c );
						if( count($tmp) > 0 && (int)$tmp[0] ){
							Mage::helper('ves_blog')->resizeImage( $post->getFile(), $size, (int)$tmp[0], (int)$tmp[1] );
						}
					}	
				}
			}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Images Of All Categories are resized successful'));
		} catch ( Exception $e ) {
			  Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/');
	}
	
	public function massRewriteAction(){
		try {
			$collection = Mage::getModel('ves_blog/category')->getCollection();
			$resroute = Mage::getStoreConfig('ves_blog/general_setting/route');
			$extension = ".html";
			foreach( $collection as $model ){
				Mage::getModel('core/url_rewrite')->loadByIdPath('venusblog/category/'.$model->getId())
							->setIdPath('venusblog/category/'.$model->getId())
							->setRequestPath($resroute .'/'.$model->getIdentifier().$extension  )
							->setTargetPath('venusblog/category/view/id/'.$model->getId())
							->save();
			}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rewrite URLs Of All Category are resized successful'));
		} catch ( Exception $e ) {
			  Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		
		$this->_redirect('*/*/');	
	}
	
	 public function massStatusAction() {
        $IDList = $this->getRequest()->getParam('category');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select record(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getSingleton('ves_blog/category')
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
        $IDList = $this->getRequest()->getParam('category');
        if(!is_array($IDList)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select record(s)'));
        } else {
            try {
                foreach ($IDList as $itemId) {
                    $_model = Mage::getModel('ves_blog/category')
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