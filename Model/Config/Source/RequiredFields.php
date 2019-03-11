<?php
/**
 * Copyright © 2016-2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Model\Config\Source;

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
            'firstname'     => __('First Name'),
            'lastname'      => __('Last Name'),
            'company'       => __('Company'),
            'street'        => __('Street Address'),
            'city'          => __('City'),
            'region_id'     => __('Region/State'),
            'postcode'      => __('ZIP/Postal Code'),
            'country_id'    => __('Country'),
            'telephone'     => __('Phone Number'),
            'vat_id'        => __('VAT number'),
        ];
    }
}
