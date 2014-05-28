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
 * @category    Lof * @package     Lof_Coinslider
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Banner edit block
 *
 * @category   Lof
 * @package     Lof_Coinslider
 * @author    
 */

class Ves_Contentslider_Block_Adminhtml_Banner_Typo extends Mage_Adminhtml_Block_Widget_Form_Container
{
    var $data = null;
    var $params = array();
    public function __construct()
    {
        parent::__construct();

        $this->_objectId    = 'id';
        $this->_blockGroup  = 'ves_contentslider';
        $this->_controller  = 'adminhtml_banner';

        $this->setTemplate('ves_contentslider/form/typo.phtml');
        
        $this->data['field'] = $this->getRequest()->getParam('field');

        $skin_base_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
        $skin_base_dir = Mage::getBaseDir('skin');

        $default_theme =  Mage::getSingleton('core/design_package')->getTheme('admin');

        $typoFile =     $skin_base_url."adminhtml/default/".$default_theme."/ves_contentslider/style.css";
        
        $content = file_get_contents(  $typoFile );

        $this->data['typoFile'] = $typoFile; 
        $data = preg_match_all("#\.ves-caption\.([\w\-]+)\s*{\s*#", $content, $matches);
    
        $this->data['captions'] = array();

        if( isset($matches[1]) ){
            $this->data['captions']  = $matches[1];
        }
        
    }
    public function getTypoData(){
        return $this->data;
    }
    protected function _prepareLayout() {

        return parent::_prepareLayout();
    }
}