<?php
/**
 * Sample DB of Quickstart Installer
 *
 * @category   Mage
 * @package    Mage_Install
 * @author     venustheme.com
 */
class Mage_Install_Model_Installer_Quickstart extends Mage_Install_Model_Installer_Db
{
    /**
     * Install quickstart database
     *
     * @param array $data
     */
	var $quickstart_db_name = "sample_data.sql";
	
    public function installQuickstartDB_ ($data) {
        $config = array(
            'host'      => $data['db_host'],
            'username'  => $data['db_user'],
            'password'  => $data['db_pass'],
            'dbname'    => $data['db_name']
        );
        $connection = Mage::getSingleton('core/resource')->createConnection('core_setup', $this->_getConnenctionType(), $config);

        $installer = new Mage_Core_Model_Resource_Setup('core_setup');
		$installer->startSetup();
		//Get content from quickstart data
		$tablePrefix = $data['db_prefix'];
		$base_url = $data['unsecure_base_url'];
		$base_surl = $base_url;
		if (!empty($data['use_secure'])) $base_surl = $data['secure_base_url'];
		$file = Mage::getConfig()->getBaseDir().'/sql/'.$this->quickstart_db_name;
		if (is_file($file) && ($sqls = file_get_contents ($file))) {
			$sqls = str_replace ('#__', $tablePrefix, $sqls);
			$installer->run ($sqls);
		}
		
		$installer->run ("
			UPDATE `{$tablePrefix}core_config_data` SET `value`='$base_url' where `path`='web/unsecure/base_url';
			UPDATE `{$tablePrefix}core_config_data` SET `value`='$base_surl' where `path`='web/secure/base_url';
		"
		);
		
		$installer->endSetup();
    }
    
	public function installQuickstartDB ($data) {
		$tablePrefix = $data['db_prefix'];
		$base_url = $data['unsecure_base_url'];
		$base_surl = $base_url;
		if (!empty($data['use_secure'])) $base_surl = $data['secure_base_url'];	
		$file = Mage::getConfig()->getBaseDir().'/sql/'.$this->quickstart_db_name;

		if (is_file($file)) {
			$connection = mysql_connect($data['db_host'], $data['db_user'], $data['db_pass']);
			if (!$connection) {
				return false;
			}
			if (!mysql_select_db($data['db_name'], $connection)) { 
				mysql_close ($connection);
				return false; 
			}
			$contents = file_get_contents ($file);
			$sqls = self::parseSQL ($contents);
			foreach ($sqls as $sql) {
				$sql = trim(str_replace ('#__', $tablePrefix, $sql));			
				if ($sql && !mysql_query($sql, $connection)) {
					mysql_close ($connection);
					return false;
				}
			}
			mysql_close ($connection);
			return true;
		}
		return false;
	}
	
	function parseSQL ($contents) {
		$comment_patterns = array('/\/\*.*(\n)*.*(\*\/)?/', 
								  '/^\s*--.*\n/',
								  '/^\s*#.*\n/',
								  );
		$contents = preg_replace($comment_patterns, "\n", $contents);
		$statements = explode(";\n", $contents);

		return $statements;
	}
}