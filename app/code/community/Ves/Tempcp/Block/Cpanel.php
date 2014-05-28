<?php

if (!class_exists("Ves_Tempcp_Block_List")) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "List.php";
}

class Ves_Tempcp_Block_Cpanel extends Ves_Tempcp_Block_List {

    /**
     * Rendering block content
     *
     * @return string
     */
    public function _toHtml() {
        $this->_config['template'] = 'venustheme/tempcp/paneltool.phtml';
        $helper = Mage::helper('ves_tempcp/data');
		if( !$this->_config['paneltool'] ){
			return ;
		}
        $config = $helper->get();
        //var_dump($config); die;
        $data = $helper->getThemeInfo();
        if(!empty($data)){
            $cookies = Mage::getModel('core/cookie')->get(); 
            //var_dump($cookies);

            $skin_default = (!isset($cookies['skin']) || (isset($cookies['skin']) && empty($cookies['skin']))) ? $config["skin"] : $cookies["skin"];
            $bgpattern = (!isset($cookies['bgpattern']) || (isset($cookies['bgpattern']) && empty($cookies['bgpattern']))) ? $config["backgroundpattern"] : $cookies["bgpattern"];
            $layout = (!isset($cookies['layout']) || (isset($cookies['layout']) && empty($cookies['layout']))) ? $config['layout'] : $cookies['layout'];
            $paneltool = (!isset($config['paneltool'])) ? "1" : $config['paneltool'];
			$responsive = (!isset($cookies['responsive']) || (isset($cookies['responsive']) && empty($cookies['responsive']))) ? $config['responsive'] : $cookies['responsive'];
			
            //var_dump($paneltool); die;
            $items = array(
                'paneltool' => $paneltool,
                'skin_default' => $skin_default,
                'bgpattern' => $bgpattern,
                'layout' => $layout,
                'data' => $data
            );
            $this->assign('items', $items);
        
            // render html
            $this->assign('config', $this->_config);
            $this->setTemplate($this->_config['template']);
        }
        return parent::_toHtml();
    }
    
    public function getPostActionUrl(){
        $helper = Mage::helper('ves_tempcp/data');
        return $helper->getApplyPostUrl();
    }

}
