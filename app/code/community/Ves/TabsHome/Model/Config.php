<?php

class Ves_TabsHome_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') . DS . 'tabshome';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'tabshome';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') . DS . 'tmp' . DS . 'tabshome';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/tabshome';
    }

}