<?php 
/******************************************************
 * @package Ves Magento Theme Framework for Magento 1.4.x or latest
 * @version 1.1
 * @author http://www.venusthemes.com
 * @copyright	Copyright (C) Feb 2013 VenusThemes.com <@emai:venusthemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

class VesCache {
		
		/**
		 *
		 */
		private $expire = 3600; 
		
		/**
		 *
		 */
		private $ext = 'css';
		
		/**
		 *
		 */
		public function setExtension( $ext='css'){
			if( !is_dir(VES_CSS_CACHE) ){ 
				mkdir( VES_CSS_CACHE, 0755 );
			}
			$this->ext = $ext; 
			return $this;
		}
		
		/**
		 *
		 */
		public function get($key) {
			$files = glob(VES_CSS_CACHE . 'c-' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.'. $this->ext );

			if ($files) {
				$cache = file_get_contents($files[0]);
				
				$data = unserialize($cache);
				
				foreach ($files as $file) {
					$time = substr(strrchr($file, '.'), 1);

					if ($time < time()) {
						if (file_exists($file)) {
							unlink($file);
						}
					}
				}
				
				return $data;			
			}
		}
		
		/**
		 *
		 */
		public function isExisted( $key ){
			return is_file( VES_CSS_CACHE . $key.'.'.$this->ext ); 
		}
		
		/**
		 *
		 */
		public function set($key, $value) {
			$this->delete($key);
			$file = VES_CSS_CACHE . $key .'.'.$this->ext;
			
			$handle = fopen($file, 'w');
			fwrite($handle,($value));
			fclose($handle);
			@chmod($file, 0755);
			return true;
		}
		
		/**
		 *
		 */
		public function delete($key) {
			$files = glob(VES_CSS_CACHE . $key . '.*');
			if ($files) {
				foreach ($files as $file) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
		}
	}
?>