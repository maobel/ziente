<?php

class Ves_Tempcp_Block_List extends Mage_Catalog_Block_Product_Abstract {

    protected $_config = '';
   


    public function __construct($attributes = array()) {
        $helper = Mage::helper('ves_tempcp/data');
        $this->_config = $helper->get($attributes);
       
        /* End init meida files */
        $mediaHelper = Mage::helper('ves_tempcp/media');
        
        
        $config = $this->_config;
        //$responsive = (empty($config['responsive'])) ? 0 : $config['responsive'];
        //if($responsive == 1){
        //    $mediaHelper->addMediaFile("skin_css", "ves_tempcp/responsive.css");

        $mediaHelper->addMediaFile("skin_css", "venustheme/ves_tempcp/style.css");
        parent::__construct();
    }

    /**
     * get value of the extension's configuration
     *
     * @return string
     */
    function getConfig($key) {
        return $this->_config[$key];
    }

    /**
     * overrde the value of the extension's configuration
     *
     * @return string
     */
    function setConfig($key, $value) {
        $this->_config[$key] = $value;
        return $this;
    }

    function parseParams($params) {
        $params = html_entity_decode($params, ENT_QUOTES);
        $regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
        preg_match_all($regex, $params, $matches);
        $paramarray = null;
        if (count($matches)) {
            $paramarray = array();
            for ($i = 0; $i < count($matches[1]); $i++) {
                $key = $matches[1][$i];
                $val = $matches[3][$i] ? $matches[3][$i] : ($matches[4][$i] ? $matches[4][$i] : $matches[5][$i]);
                $paramarray[$key] = $val;
            }
        }
        return $paramarray;
    }

    function isStaticBlock() {
        $name = isset($this->_config["name"]) ? $this->_config["name"] : "";
        if (!empty($name)) {
            $regex1 = '/static_(\s*)/';
            if (preg_match_all($regex1, $name, $matches)) {
                return true;
            }
        }
        return false;
    }

    function set($params) {
        $params = preg_split("/\n/", $params);
        foreach ($params as $param) {
            $param = trim($param);
            if (!$param)
                continue;
            $param = split("=", $param, 2);
            if (count($param) == 2 && strlen(trim($param[1])) > 0)
                $this->_config[trim($param[0])] = trim($param[1]);
        }
        $theme = $this->getConfig("theme");
        if ($theme != $this->_theme) {
            $mediaHelper = Mage::helper('ves_tempcp/media');
            $mediaHelper->addMediaFile("skin_css", "ves_tempcp/" . $theme . "/style.css");
        }
    }

    /**
     * render thumbnail image
     */
    public function buildThumbnail($imageArray, $twidth, $theight) {
        $thumbnailMode = $this->_config['thumbnailMode'];
        if ($thumbnailMode != 'none') {
            $imageProcessor = Mage::helper('ves_tempcp/vesimage');
            $imageProcessor->setStoredFolder();
            foreach ($imageArray as $image) {
                $thumbs[] = $imageProcessor->resize($image, $twidth, $theight);
            }
            return $thumbs;
        }
        return $imageArray;
    }

}
