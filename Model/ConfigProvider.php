<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

use Magento\Store\Model\ScopeInterface;

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
    /**
     * @param string $key
     * @return mixed
     */
    private function getConfigData(string $key)
    {
        return $this->scopeConfig->getValue('carriers/owsh1/' . $key, ScopeInterface::SCOPE_STORE);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $validationRules = [];
        $requiredFields = explode(',', (string) $this->getConfigData('required_fields'));
        foreach ($requiredFields as $field) {
            $validationRules[Carrier::CODE][$field] = [ 'required' => true ];
        }
        return [
            'owebia' => [
                'advancedShipping' => [
                    'validationRules' => $validationRules,
                ],
            ],
        ];
    }
}
