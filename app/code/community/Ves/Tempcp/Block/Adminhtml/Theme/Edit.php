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

class Ves_Tempcp_Block_Adminhtml_Theme_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    var $data = null;
    public function __construct()
    {
        parent::__construct();

        $this->_objectId    = 'id';
        $this->_blockGroup  = 'ves_tempcp';
        $this->_controller  = 'adminhtml_theme';

        $this->_updateButton('save', 'label', Mage::helper('ves_tempcp')->__('Save Theme'));
        $this->_updateButton('delete', 'label', Mage::helper('ves_tempcp')->__('Delete Theme'));

        $this->setTemplate('ves_tempcp/form/edit.phtml');
        
        $mediaHelper = Mage::helper('ves_tempcp/media');
        $mediaHelper->loadMedia();

        $themeHelper = Mage::helper('ves_tempcp/theme');

        $this->data = $themeHelper->initTheme();

       
    }
    protected function _prepareLayout() {
         /**
         * Display store switcher if system has more one store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->setChild('store_switcher',
                   $this->getLayout()->createBlock('adminhtml/store_switcher')
                   ->setUseConfirm(false)
                   ->setSwitchUrl($this->getUrl('*/*/*/id/'.Mage::registry('theme_data')->get('theme_id'), array('store'=>null)))
           );
        }

        return parent::_prepareLayout();
    }
    public function getThemeData(){
        return $this->data;
    }
    public function getHeaderText()
    {
        return Mage::helper('ves_tempcp')->__("Venus Theme Control Panel - Edit Theme '%s'", $this->htmlEscape(Mage::registry('theme_data')->get('theme')));
    }

    /**
     *
     */
    public function getFileList( $path , $e=null ) {
        $output = array(); 
        $directories = glob( $path.'*'.$e );
        foreach( $directories as $dir ){
            $output[] = basename( $dir );
        }           
         
        return $output;
    }
    
    public function getContentCustomCss( ) {
        $output = "";
        $theme = Mage::registry('theme_data')->get('group');
        if($theme) {

           $custom_css_path = Mage::getBaseDir('skin')."/frontend/default/".$theme."/css/local/custom.css";
           if(file_exists($custom_css_path)) {
                $file = new Varien_Io_File();
                $output = $file->read(Mage::getBaseDir('skin')."/frontend/default/".$theme."/css/local/custom.css");

           }
        }
                  
         
        return $output;
    }
    public function getCancelLink(){
        return $this->getUrl('*/adminhtml_theme/index');
    }
    public function getLiveEditLink(){
        $theme_id = Mage::registry('theme_data')->get('theme_id');
        return $this->getUrl('*/adminhtml_theme/customize', array("id"=>$theme_id));
    }
    public function getAjaxSaveLink(){
        return $this->getUrl('*/adminhtml_theme/ajaxsave');
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