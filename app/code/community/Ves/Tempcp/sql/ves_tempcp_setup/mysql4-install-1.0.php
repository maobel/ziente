<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Ves 
 * @package     Ves_Tempcp
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS `{$this->getTable('ves_tempcp/theme')}`;
CREATE TABLE `{$this->getTable('ves_tempcp/theme')}` (
  `theme_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `group` varchar(100) DEFAULT NULL,
  `params` text DEFAULT NULL,
  `creation_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `is_default` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Lof coinslider Image';

-- DROP TABLE IF EXISTS `{$this->getTable('ves_tempcp/theme_store')}`;
CREATE TABLE `{$this->getTable('ves_tempcp/theme_store')}` (
  `theme_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`theme_id`,`store_id`),
  CONSTRAINT `FK_THEME_THEME_STORE_THEME` FOREIGN KEY (`theme_id`) REFERENCES `{$this->getTable('ves_tempcp/theme')}` (`theme_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_THEME_THEME_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Theme items to Stores';
");

$installer->endSetup();