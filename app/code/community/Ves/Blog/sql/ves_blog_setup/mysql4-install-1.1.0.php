<?php


$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();
$installer->run("
 

CREATE TABLE IF NOT EXISTS `{$this->getTable('ves_blog/category')}` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `layout` varchar(250) NOT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `file` varchar(255) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `{$this->getTable('ves_blog/category')}`
--

-- DROP TABLE IF EXISTS `{$this->getTable('ves_blog/category_store')}`;
CREATE TABLE `{$this->getTable('ves_blog/category_store')}` (
  `category_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`store_id`),
  CONSTRAINT `FK_BLOG_CATEGORY_STORE_CATEGORY` FOREIGN KEY (`category_id`) REFERENCES `{$this->getTable('ves_blog/category')}` (`category_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_BLOG_CATEGORY_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Category items to Stores';


-- --------------------------------------------------------

--
-- Table structure for table `{$this->getTable('ves_blog/comment')}`
--

CREATE TABLE IF NOT EXISTS `{$this->getTable('ves_blog/comment')}` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL,
  `comment` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `user` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `FK_blog_comment` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `{$this->getTable('ves_blog/comment')}`
--

-- DROP TABLE IF EXISTS `{$this->getTable('ves_blog/comment_store')}`;
CREATE TABLE `{$this->getTable('ves_blog/comment_store')}` (
  `comment_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`comment_id`,`store_id`),
  CONSTRAINT `FK_BLOG_COMMENT_STORE_COMMENT` FOREIGN KEY (`comment_id`) REFERENCES `{$this->getTable('ves_blog/comment')}` (`comment_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_BLOG_COMMENT_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Comments to Stores';


-- --------------------------------------------------------

--
-- Table structure for table `{$this->getTable('ves_blog/post')}``{$this->getTable('ves_blog/post')}`
--

CREATE TABLE IF NOT EXISTS `{$this->getTable('ves_blog/post')}` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_active` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `detail_content` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `update_user` varchar(255) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `position` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`post_id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


-- DROP TABLE IF EXISTS `{$this->getTable('ves_blog/post_store')}`;
CREATE TABLE `{$this->getTable('ves_blog/post_store')}` (
  `post_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`store_id`),
  CONSTRAINT `FK_BLOG_POST_STORE_POST` FOREIGN KEY (`post_id`) REFERENCES `{$this->getTable('ves_blog/post')}` (`post_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `FK_BLOG_POST_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Article items to Stores';


");
$installer->endSetup();

