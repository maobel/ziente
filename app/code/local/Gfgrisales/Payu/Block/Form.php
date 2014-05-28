<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Gfgrisales
 * @package    Gfgrisales_Payu
 * @copyright  Copyright (c) 2013 gfranco.info [modified from vnphpexpert.com]
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Gfgrisales_Payu_Block_Form extends Mage_Payment_Block_Form
{
	protected function _construct()
    {
        parent::_construct();
		
		$mark = Mage::getConfig()->getBlockClassName('core/template');
        $mark = new $mark;
        $mark->setTemplate('payu/mark.phtml');
		
        $this->setTemplate('payu/form.phtml')->setMethodTitle('') // Output payu mark, omit title
            ->setMethodLabelAfterHtml($mark->toHtml());
    }
}
