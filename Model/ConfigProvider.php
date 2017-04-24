<?php
/**
 * Copyright © 2016-2017 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShippingSetting\Model;

class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    private function getConfigValue($key)
    {
        return $this->scopeConfig->getValue('carriers/owsh1/' . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [
            'owebia' => [
                'advanced_shipping_setting' => [
                    'validation_rules' => [],
                ],
            ],
        ];
        $requiredFields = explode(',', (string) $this->getConfigValue('required_fields'));
        foreach ($requiredFields as $field) {
            $config['owebia']['advanced_shipping_setting']['validation_rules'][$field] = [ 'required' => true ];
        }
        return $config;
    }
}
