<?php
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
class Ves_Tempcp_Model_Import_Abstract_Csv extends Mage_Core_Model_Abstract {

	private $array_delimiter = ';';
	private $delimiter = ',';
	private $header_columns;
	protected $_modelname; // = 'cms/block'; = 'cms/page';

	private function openFile($filepath) {
		$handle = null;

		if (($handle = fopen($filepath, "r")) !== FALSE) {
			return $handle;
		} else {
			throw new Exception('Error opening file ' . $filepath);
		} // end

	} // end


	public function process($filepath) {

		$handle = $this->openfile($filepath);

		$row = 0;
		if ( $handle != null ) {

			// loop thru all rows
			while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {
				$row++;

				// if this is the head row keep this as a column reference
				if ($row == 1) {
					$this->mapHeader($data);
					continue;
				}

				// make sure we have a reset model object
				//$staticblock = Mage::getSingleton($this->_modelname)->clearInstance();
				$staticblock = Mage::getModel($this->_modelname);
				
				// get the identifier column for this row
				$identifier = $data[$this->getIdentifierColumnIndex()];

				// if a static block already exists for this identifier - load the data
				$staticblock->load($identifier);

				// loop through each column
				foreach ($this->header_columns as $index => $keyname) {
					$keyname = strtolower($keyname);

					// switch statement incase we need to do logic depending on the column name
					switch ($keyname) {

						case "stores":
							// stores are separated with ";" when they're exported
							$stores = $data[$index];
							$stores_array = explode(';', $stores);
							$staticblock->setData($keyname, $stores_array);
							$staticblock->setData('store_id', $stores_array);
						break;

						case "block_id":
						case "page_id":
						case "theme_id":
							// dont need to add this. @todo remove this column from export.
						break;

						default:
							// fgetcsv encodes everything
							$staticblock->setData($keyname, html_entity_decode($data[$index]));
						break;

					} // end switch
				} // end for

				// save our block
				try {
					$staticblock->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ves_tempcp')->__('Updated ' . $identifier));
				} catch (Exception $e) {
					Mage::throwException($e->getMessage() . ' URL Key = ' . $data[$this->getIdentifierColumnIndex()]);
				}
			} // end while
		}// end if

	} // end

	private function mapHeader($data_array) {
		$this->header_columns = $data_array;
	}

	private function getIdentifierColumnIndex() {
		$header = $this->header_columns;
		$index = array_search('Identifier', $header);
		return $index;
	}

}
