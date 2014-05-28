<?php

class Ves_Tempcp_Block_System_Config_Form_Field_ThemeInfo extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $useContainerId = $element->getData('use_container_id');
        return sprintf('
            <tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5" class="ves-description">
                <h3><a href="#"><b>Magento - Ves Template Control Panel Block</b></a></h3>
                            The Theme Configuration is not avariable, because may be you forgot set a theme from VenusTheme.Com as default theme of front-office, Please try to check again 
                            <br\><br\>
                </td></tr>', $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );
    }

}

?>