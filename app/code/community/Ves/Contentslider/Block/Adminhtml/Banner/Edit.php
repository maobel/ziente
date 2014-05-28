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

class Ves_Contentslider_Block_Adminhtml_Banner_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    var $data = null;
    var $params = array();
    public function __construct()
    {
        parent::__construct();

        $this->_objectId    = 'id';
        $this->_blockGroup  = 'ves_contentslider';
        $this->_controller  = 'adminhtml_banner';

        $this->_updateButton('save', 'label', Mage::helper('ves_tempcp')->__('Save Theme'));
        $this->_updateButton('delete', 'label', Mage::helper('ves_tempcp')->__('Delete Theme'));

        $this->setTemplate('ves_contentslider/form/edit.phtml');
        
        $mediaHelper = Mage::helper('ves_contentslider/media');
        $mediaHelper->loadMedia();
        $this->data = Mage::registry("banner_data");
        
    }
    protected function _prepareLayout() {
         /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                   $this->getLayout()->createBlock('adminhtml/store_switcher')
                   ->setUseConfirm(false)
                   ->setSwitchUrl($this->getUrl('*/*/*/id/'.Mage::registry('banner_data')->get('banner_id'), array('store'=>null)))
           );
        }

        return parent::_prepareLayout();
    }
    public function getBannerData(){
        return $this->data;
    }
    public function getBannerParams(){
        $this->params = $this->data->getData("params");
        if($this->params) {
            $this->params = unserialize(base64_decode($this->params));
        }
        
        return $this->params;
    }
    
    public function getHeaderText()
    {
        $banner_id = Mage::registry('banner_data')->getData('banner_id');

        if ($banner_id) {
            return Mage::helper('ves_tempcp')->__("Venus Content Slider - Edit Banner '%s'", $this->htmlEscape(Mage::registry('banner_data')->getData('title')));
        } else {
            return Mage::helper('ves_tempcp')->__("Venus Content Slider - New Banner");
        }
        
    }

    public function getTypoUrl($field = ""){
        $field = !empty($field)?"/field/".$field:"";
        return $this->getUrl('*/adminhtml_banner/typo'.$field);
    }
    public function getCancelLink(){
        return $this->getUrl('*/adminhtml_banner/index');
    }
    public function getLiveEditLink(){
        return "#";
    }
    public function getAjaxSaveLink(){
        return $this->getUrl('*/adminhtml_banner/ajaxsave');
    }
    public function getStoreSwitcherHtml() {
       return $this->getChildHtml('store_switcher');
    }
    protected function getCustomLink($route , $params = array()){
        $link =  Mage::helper("adminhtml")->getUrl($route, $params);
        $link = str_replace("/adminhtml/","/", $link);
        $link = str_replace("/tempcp/","/", $link);
        $link = str_replace("//admin","/admin", $link);
        return $link;
    }
    public function getDirectivesLink($params = array()){
       return $this->getCustomLink("*/adminhtml/admin/cms_wysiwyg/directive", $params);
    }
    public function getVariablesLink($params = array()){
       return $this->getCustomLink("*/adminhtml/admin/system_variable/wysiwygPlugin", $params);
    }
    public function getImagesLink($params = array()){
       return $this->getCustomLink("*/adminhtml/admin/cms_wysiwyg_images/index", $params);
    }
    public function getWidgetLink($params = array()){
        return $this->getCustomLink("*/adminhtml/admin/widget/index", $params);
    }
}