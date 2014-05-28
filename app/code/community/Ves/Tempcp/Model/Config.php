<?php

class Ves_Tempcp_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') . DS . 'tempcp';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'tempcp';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') . DS . 'tmp' . DS . 'tempcp';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/tempcp';
    }

}