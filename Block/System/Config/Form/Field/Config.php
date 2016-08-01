<?php
/**
 * Copyright © 2016 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Owebia\AdvancedShippingSetting\Block\System\Config\Form\Field;

class Config extends \Owebia\AdvancedSettingCore\Block\System\Config\Form\Field\Config
{
    protected function getFullscreenTitle(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return __("Advanced Shipping Setting Configuration");
    }

    protected function getHelpUrl(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->getUrl('owebia_advancedshippingsetting/help/display');
    }

    protected function getToolbarContent(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return parent::getToolbarContent($element)
            . <<<EOD
<!--<a target="_blank" class="icon icon-check" href="{$this->getUrl('owebia_advancedshippingsetting/help/test')}" title="{$this->escapeHtml(__('Test'))}"><span>{$this->escapeHtml(__('Test'))}</span></a>-->
EOD;
    }

    protected function getFooterContent(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return <<<EOD
<!--<a href="http://www.owebia.com/os2/fr/configurations" target=_blank>{$this->escapeHtml(__('Download configuration templates'))}</a>-->
EOD;
    }
}
