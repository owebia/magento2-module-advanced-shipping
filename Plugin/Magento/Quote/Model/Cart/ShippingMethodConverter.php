<?php
/**
 * Copyright © 2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Plugin\Magento\Quote\Model\Cart;

use Magento\Quote\Api\Data\ShippingMethodInterface;

class ShippingMethodConverter
{
    /**
     * @var \Magento\Quote\Api\Data\ShippingMethodExtensionFactory
     */
    protected $shippingMethodExtensionFactory;

    /**
     * Product constructor.
     *
     * @param \Magento\Quote\Api\Data\ShippingMethodExtensionFactory $shippingMethodExtensionFactory
     */
    public function __construct(
        \Magento\Quote\Api\Data\ShippingMethodExtensionFactory $shippingMethodExtensionFactory
    ) {
        $this->shippingMethodExtensionFactory = $shippingMethodExtensionFactory;
    }

    /**
     * @param \Magento\Quote\Model\Cart\ShippingMethodConverter $subject
     * @param callable $proceed
     * @param \Magento\Quote\Model\Quote\Address\Rate $rateModel
     * @param string $quoteCurrencyCode
     * @return \Magento\Quote\Api\Data\ShippingMethodInterface
     */
    public function aroundModelToDataObject(
        \Magento\Quote\Model\Cart\ShippingMethodConverter $subject,
        callable $proceed,
        \Magento\Quote\Model\Quote\Address\Rate $rateModel,
        $quoteCurrencyCode
    ) {
        /** @var \Magento\Quote\Api\Data\ShippingMethodInterface $shippingMethod */
        $shippingMethod = $proceed($rateModel, $quoteCurrencyCode);

        if ($customData = $rateModel->getCustomData()) {
            $extensionAttributes = $shippingMethod->getExtensionAttributes();
            if (!$extensionAttributes) {
                $extensionAttributes = $this->shippingMethodExtensionFactory->create();
            }

            $customDataArray = $customData->toArray();
            $extensionAttributes->setCustom($customData);
            foreach ($customDataArray as $name => $value) {
                $extensionAttributes->setData($name, $value);
            }

            $shippingMethod->setExtensionAttributes($extensionAttributes);
        }

        return $shippingMethod;
    }
}
