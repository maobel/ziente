<?php
class Ves_TreeMenu_Model_Config extends Mage_Catalog_Model_Product_Media_Config {

    public function getBaseMediaPath() {
        return Mage::getBaseDir('media') .DS. 'treemenu';
    }

    public function getBaseMediaUrl() {
        return Mage::getBaseUrl('media') . 'treemenu';
    }

    public function getBaseTmpMediaPath() {
        return Mage::getBaseDir('media') .DS. 'tmp' .DS. 'treemenu';
    }

    public function getBaseTmpMediaUrl() {
        return Mage::getBaseUrl('media') . 'tmp/treemenu';
    }

}