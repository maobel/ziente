<?php

class Ziente_Appointments_Adminhtml_LocationsController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("appointments/locations")->_addBreadcrumb(Mage::helper("adminhtml")->__("Locations  Manager"),Mage::helper("adminhtml")->__("Locations Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Appointments"));
			    $this->_title($this->__("Manager Locations"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Appointments"));
				$this->_title($this->__("Locations"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("appointments/locations")->load($id);
				if ($model->getId()) {
					Mage::register("locations_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("appointments/locations");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Locations Manager"), Mage::helper("adminhtml")->__("Locations Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Locations Description"), Mage::helper("adminhtml")->__("Locations Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("appointments/adminhtml_locations_edit"))->_addLeft($this->getLayout()->createBlock("appointments/adminhtml_locations_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("appointments")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Appointments"));
		$this->_title($this->__("Locations"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("appointments/locations")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("locations_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("appointments/locations");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Locations Manager"), Mage::helper("adminhtml")->__("Locations Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Locations Description"), Mage::helper("adminhtml")->__("Locations Description"));


		$this->_addContent($this->getLayout()->createBlock("appointments/adminhtml_locations_edit"))->_addLeft($this->getLayout()->createBlock("appointments/adminhtml_locations_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("appointments/locations")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Locations was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setLocationsData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setLocationsData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("appointments/locations");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("appointments/locations");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'locations.csv';
			$grid       = $this->getLayout()->createBlock('appointments/adminhtml_locations_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'locations.xml';
			$grid       = $this->getLayout()->createBlock('appointments/adminhtml_locations_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
