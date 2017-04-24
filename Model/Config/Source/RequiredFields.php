<?php
/**
 * Copyright © 2016-2017 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShippingSetting\Model\Config\Source;

class RequiredFields implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->toArray() as $value => $label) {
            $options[] = [ 'value' => $value, 'label' => $label ];
        }
        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'country_id' => __('Country'),
            'region_id'  => __('Region/State'),
            'postcode'   => __('ZIP/Postal Code'),
            'city'       => __('City'),
            'street'     => __('Street Address'),
        ];
    }
}
