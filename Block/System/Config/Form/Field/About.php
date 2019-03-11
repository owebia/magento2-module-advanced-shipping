<?php
/**
 * Copyright © 2016-2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Owebia\AdvancedShipping\Block\System\Config\Form\Field;

class About extends \Owebia\AdvancedSettingCore\Block\System\Config\Form\Field\AbstractField
{

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Module\PackageInfoFactory $packageInfoFactory
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Module\PackageInfoFactory $packageInfoFactory
    ) {
        parent::__construct($context);
        $this->packageInfo = $packageInfoFactory->create();
    }
        
    /**
     * Retrieve element HTML markup
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return "";
    }
}
