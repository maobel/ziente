<?php
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
require_once (dirname(__FILE__)."/field.php");

class Tempcp_Theme_Core extends Mage_Core_Helper_Abstract{
	protected $error = array(); 
	protected $theme = '';
	protected $theme_path = '';
	protected $params = "";
	protected $element_group = "themecontrol";
	public $store_id = 0;
	public $theme_name = '';
	public $positions = array();
	public $default_config = array();
	public $skins = array();

	/**
	 * Get all config params of theme
	 * @param: $property is string, that field name of theme config which you want get
	 * @param: $default is string, when field is not exists function will return default
	 * @param: $run_mage_variables is boolean, allow run helper to convert magento variables to html
	 * @return: string
	 */
	public function get($property, $default = "", $run_mage_variables = false) {
		$default = (empty($default) && isset($this->default_config[$property]))?$this->default_config[$property]:$default;
		$return = "";
		if(isset($this->$property)){
			$return = $this->$property;
		}elseif(isset($this->params[$property])){
			$return = $this->params[$property];
		}else{
			$return = $default;
		}
		if($run_mage_variables){
			$processor = Mage::helper('cms')->getPageTemplateProcessor();
			$return = $processor->filter($return);
		}
        return $return;
    }
    /**
	 *
	 */
    public function set($property, $value) {
        $this->$property = $value;
        return $this;
    }
    public function getCurrentTheme($theme_name = ""){
    	$theme_name =  Mage::getDesign()->getTheme('frontend');
    	if($theme_name){
    		if(!$theme = Mage::registry('theme_data')){
    			$_model  = Mage::getModel('ves_tempcp/theme')->loadThemeByGroup($theme_name);
				if($_model && $_model->getId()){
					$object = $this->bind( $_model );
				}
				$params = $this->getParams();
    			$theme = $this->initTheme();
    			Mage::register('theme_data', $theme);
    		}
    		return $theme;
    	}
    	return false;
    }
    public function getTheme($themeId = 0){
		if($themeId){
			$_model  = Mage::getModel('ves_tempcp/theme')->load($themeId);
        	if ($_model->getId()) {

        		$object = $this->bind( $_model );
        		Mage::register('theme_data', $this);
        		Mage::register('current_data', $_model);
        	}
		}
		if(empty($themeId)){
    		if($_model = Mage::registry('current_data')){
    			$object = $this->bind( $_model );
    		}
    	}
    	$params = $this->getParams();
    	return $this;
	}
	public function bind($data = null){

		if(is_array($data) && !empty($data)){
			$theme = isset($data['theme'])?$data['theme']:array();
			$themecontrol = isset($data[$this->element_group])?$data[$this->element_group]:array();
			$theme_id = isset($data['theme_id'])?$data['theme_id']:0;

			if(!empty($theme)){
				foreach($theme as $key=>$val){
					$this->{$key} = $val;
				}
			}
			$this->params = $themecontrol;
			if($theme_id){
				$this->update_time = date("Y-M-d H:i:s");
			}else{
				$this->creation_time = date("Y-m-d H:i:s");
			}
		}elseif(is_object($data) && !empty($data)){
			$this->theme_id = $data->getThemeId();
			$this->title = $data->getTitle();
			$this->group = $data->getGroup();
			$this->is_active = $data->getIsActive();
			$this->creation_time = $data->getCreationTime();
			$this->update_time = $data->getUpdateTime();
			$this->store_id = $data->getStoreId();

			$params = $data->getParams();
			$this->params = unserialize(base64_decode($params));

			//$this->params = $this->defaultParams($params);

		}else{
			return false;
		}
		return $this;
		
	}

    /**
	 *
	 */
    public function getParams(){
    	$params = $this->get("params", array());
    	return $params;
    	/*
    	if(!empty($params)){
    		foreach($params as $key=>$val){
    			if(!empty($val) && $val != 0)
    				$this->$key = $val;
    		}
    	}
    	return $this;*/
    }

	/**
	 *
	 */
    public function getDefaultThemeConfig(){

		$config_xml = $this->theme_path.'/config.xml';
		$config_ini = $this->theme_path.'/etc/config.ini';
		$theme_name = "";
		$positions = $skins = $config = $modules = array();
		/*get config from xml file*/
		if( file_exists($config_xml) ){
			$info = simplexml_load_file( $config_xml, 'SimpleXMLElement', LIBXML_NOCDATA );
			$theme_name = isset($info->name)?$info->name:"";
			/*get Positions*/
			if(isset($info->positions) && is_object($info->positions)){
				$vars = get_object_vars($info->positions);
				$positions = $vars['position'];
			}

			/*get Skins*/
			if(isset($info->skins) && is_object($info->skins)){
				$vars = get_object_vars($info->skins);
				$skins = $vars['skin'];
			}
			/*get font sizes*/
			if(isset($info->fontsizes) && is_object($info->fontsizes)){
				$vars = get_object_vars($info->fontsizes);
				$this->fontsizes = $vars['option'];
			}
			/*get fonts*/
			if(isset($info->fonts) && is_object($info->fonts)){
				$this->fonts = $this->getFieldList($info->fonts);
			}

			/*get fonts*/
			if(isset($info->custom_fonts) && is_object($info->custom_fonts)){
				
				$vars = get_object_vars($info->custom_fonts);
				$this->custom_fonts = $vars['font'];
			}
			
			/*get default config*/
			$default = isset($info->default)?$info->default:null;
			if(is_object($default)){
				$vars = get_object_vars($default);
				$config = $vars;
			}

			/*get internal modules*/
			if(isset($info->internal_modules) && is_object($info->internal_modules)){
				$internal_modules = $this->getFieldSets( $info->internal_modules );
			}

		}

		/*get default theme option*/
		if(file_exists($config_ini)){
			$data_ini = file_get_contents($config_ini);
			$tmp = explode("\n", $data_ini);
			if($tmp){
				foreach($tmp as $item){
					if($item){
						$tmp2 = explode("=",$item);
						$key = isset($tmp2[0])?trim($tmp2[0]):"";
						$val = isset($tmp2[1])?trim($tmp2[1]):"";

						if($key && isset($output[$key])){
							$config[$key] = !empty($val)?$val:$output[$key];

						}
					}
					
				}
			}
		}
		$this->theme_name = $theme_name;
		$this->positions = $positions;
		$this->default_config = $config;
		$this->skins	 = $skins;
		$this->internal_modules = $internal_modules;

		//if($this->default_config)
		//	$this->setDefaultConfig( $this->default_config );

		return $this->default_config;
	}

	/**
	 *
	 */
    public function initTheme() {

    	//$this->getTheme();
    	// themes 
		$directories = glob(Mage::getBaseDir('skin') . '/frontend/default/*', GLOB_ONLYDIR);
		$this->templates = array();
		foreach ($directories as $directory) {
			if( file_exists($directory."/etc/config.ini") ){
				$this->templates[] = basename($directory);
			}
		}

		$default_theme = "";
		if(isset($this->params['default_theme']) && $this->params['default_theme']){
			$default_theme = $this->params['default_theme'];
		}elseif(count($this->templates)){
			$default_theme = $this->templates[0];
		}

		$this->set( "theme", $default_theme );
		
		$base = Mage::getBaseUrl();
		$base = str_replace("/index.php","", $base);
		$this->theme_url = $base. 'skin/frontend/default/'.$this->get("theme");
		$this->theme_path 	 	 = Mage::getBaseDir('skin') . '/frontend/default/'.$this->get("theme");

		$this->patterns 	= $this->getPattern();

		$this->getDefaultThemeConfig();

		$this->checkingInfo();
		//echo "<pre>";print_r($this);die();

    	return $this;
    }
    public function setDefaultConfig( $default = array() ){
    	if(!empty($default)){
    		foreach($default as $key=>$val){
    			if(!isset($this->$key) || (isset($this->$key) && empty($this->$key)) ){
    				$this->$key = $val;
    			}
    		}
    	}
    }
    public function checkingInfo(){

    }
    /**
	 * 
	 */
	public function getPattern(){
		$output = array();
		if( $this->get("theme_path") && is_dir($this->get("theme_path").'/images/pattern/') ) {
			$files = glob($this->get("theme_path").'/images/pattern/*');
			foreach( $files as $dir ){
				if( preg_match("#.png|.jpg|.gif#", $dir)){
					$output[] = str_replace("","",basename( $dir ) );
				}
			}			
		}
		return $output;
	}
	protected function getFieldList($objxml = null){
    	$tmp = array();
    	if(!empty($objxml)){
    		foreach($objxml->children() as $key=>$child){
					$attributes = $child->attributes();
					$value = isset($attributes['value'])?(string)$attributes['value']: '';
					$text = (string) $child;
					$tmp[$value] = $text;
			}
    	}
    	return $tmp;
    }
    protected function getFieldSets($objxml = null){
    	$tmp = array();
    	if(!empty($objxml)){
    		$fields = $objxml->children();
    		if(!empty($fields)){
    			foreach($fields as $field_item){
    				$positions = array();
    				$attributes = $field_item->attributes();
    				$position = isset($attributes["name"])?$attributes["name"]:"";

    				if($array_fieldsets = $field_item->children()){
    					foreach($array_fieldsets as $key=>$fieldset_item){
    						$attributes = $fieldset_item->attributes();
    						$array_fields = array();
    						foreach($fieldset_item->children() as $key => $element){
    							$element_type = isset($element['type'])?(string)$element['type']:'label';
    							if(file_exists(dirname(__FILE__)."/fields/".$element_type.".php")){
									require_once(dirname(__FILE__)."/fields/".$element_type.".php");
									$class_name = "Field".ucfirst($element_type);
									if(class_exists($class_name)){
										$array_fields[] = new $class_name( $element, (string)$element, $this->element_group);
									}
								}
    						}
    						if(isset($attributes['name'])){
    							$positions[ (string)$attributes['name']] = $array_fields;
    						}else{
    							$positions[] = $array_fields;
    						}

    					}
    				}

    				$tmp[ (string)$position ] = $positions;
    			}
    		}
    	}

    	return $tmp;
    }
}
/**
* Class Tempcp Theme - extends Theme Core
* Use the class to get theme information
*
*/
class Ves_Tempcp_Helper_Theme extends Tempcp_Theme_Core {

	var $theme_id = 0;
	var $group = "";
	var $creation_time = "";
	var $update_time = "";
	var $skins = array();
	var $default_config = array();

	public function initTheme() {
		return parent::initTheme();

	}
	
	public function checkingInfo(){
		
	}

	/**
	 *
	 */
	public function writeToCache( $folder, $file, $value, $e='css' ){
		$file = $folder  . preg_replace('/[^A-Z0-9\._-]/i', '', $file).'.'.$e ;
		$handle = fopen($file, 'w');
    	fwrite($handle, ($value));
		
    	fclose($handle);

	}
	/**
	 *
	 */
	private function getFileList( $path , $e=null ) {
		$output = array(); 
		$directories = glob( $path.'*'.$e );
		foreach( $directories as $dir ){
			$output[] = basename( $dir );
		}			
		 
		return $output;
	}
	
	/**
	 *
	 */
	public function getConfig( $config ){
		return ''.$config;
	}
}