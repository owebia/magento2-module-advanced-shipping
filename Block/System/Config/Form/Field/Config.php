<?php
/**
 * Copyright © 2016-2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Owebia\AdvancedShipping\Block\System\Config\Form\Field;

class Config extends \Owebia\AdvancedSettingCore\Block\System\Config\Form\Field\Config
{
    protected function getFullscreenTitle(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return __("Advanced Shipping Configuration");
    }

    protected function getHelpUrl(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->getUrl('owebia_advancedshipping/help/display');
    }

    protected function getFooterContent(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return '';
    }
}
