<?php
/**
 * Copyright © 2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Plugin\Magento\Quote\Model\Quote\Address;

class Rate
{
    /**
     * @param \Magento\Quote\Model\Quote\Address\Rate $quoteAddressRate
     * @param \Magento\Quote\Model\Quote\Address\RateResult\AbstractResult $rate
     * @return array
     */
    public function beforeImportShippingRate(
        \Magento\Quote\Model\Quote\Address\Rate $quoteAddressRate,
        \Magento\Quote\Model\Quote\Address\RateResult\AbstractResult $rate
    ) {
        if ($rate instanceof \Magento\Quote\Model\Quote\Address\RateResult\Method) {
            if ($customData = $rate->getCustomData()) {
                $quoteAddressRate->setCustomData($customData);
            }
        }

        return [ $rate ];
    }
}
