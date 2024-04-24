<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Owebia\AdvancedShipping\Block\System\Config\Form\Field;

class Config extends \Owebia\SharedPhpConfig\Block\System\Config\Form\Field\Config
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function getFullscreenTitle(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return __("Advanced Shipping Configuration");
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function getHelpUrl(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->getUrl('owebia_advancedshipping/help/display');
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function getFooterContent(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return '';
    }
}
