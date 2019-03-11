<?php
/**
 * Copyright © 2016-2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Controller\Adminhtml\Help;

class Test extends \Magento\Backend\App\Action
{

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $helper = $this->_objectManager->create(\Owebia\AdvancedSettingCore\Helper\Config::class);
        /** @var \Owebia\AdvancedSettingCore\Helper\Registry $registry */
        $registry = $this->_objectManager->create(\Owebia\AdvancedSettingCore\Helper\Registry::class);
        $config = <<<'EOT'

// First shipping method
addMethod('method1', [
    'title' => "Standard Shipping",
    'price' => 10,
]);
// Second shipping method
addMethod('method2', [
    'title' => "Express Shipping",
    'price' => 20,
]);

addMethod('method3', [
    // Dynamic title using the variable $request->dest_country_id
    'title' => "Delivery to " . $request->dest_country_id,
    // Dynamic price using the variable $quote->subtotal
    'price' => 0.2 * $quote->subtotal,
]);
addMethod('method4', [
    'title'   => "Free Shipping",
    // Dynamic enabled using the function substr() and the variable $quote->coupon_code
    'enabled' => substr($quote->coupon_code, 0, 4) == 'free',
    'price'   => 0,
]);

addMethod('id_000', [
    'title'      => "If at least one product has the attribute 'name' equal to 'Cat'",
    'enabled'    => count(
                        array_filter($request->all_items, function ($item) {
                            return $item->product->name == 'Cat';
                        })
                    ) > 0,
    'price'      => 10
]);

addMethod('id_001', [
    'title'      => "Free shipping",
    'price'      => 0,
]);

addMethod('id_002', [
    'title'      => "France, Germany, Switzerland, Spain, Italy",
    'enabled'    => in_array($request->dest_country_id, ['FR', 'DE', 'CH', 'ES', 'IT']),
    'price'      => 10,
]);
addMethod('id_003', [
    'title'      => "Postcode starting with 25",
    'enabled'    => $request->dest_country_id == 'FR' && substr($request->dest_postcode, 0, 2) == '25',
    'price'      => 10,
]);
addMethod('id_004', [
    'title'      => "Regular expressions allowing postal codes beginning with 'PO' (case insensitive)",
    'enabled'    => $request->dest_country_id == 'GB' && preg_match('/^PO.*$/i', $request->dest_postcode),
    'price'      => 10,
]);

// Regular Expression refusing delivery to France with postal codes
// beginning with 97 and 98 (with or without interspersed zeros and spaces)
addMethod('id_005', [
    'title'      => "France excluding DOM/TOM",
    'enabled'    => $request->dest_country_id == 'FR'
                        && !preg_match('/^[0\\s]*9\\s*[78]/', $request->dest_postcode),
    'price'      => 10,
]);


addMethod('id_006', [
    'title'      => "Retailer group",
    'enabled'    => $customer_group->code == "Retailer",
    'price'      => 10,
]);
addMethod('id_007', [
    'title'      => "NOT LOGGED IN and General groups",
    'enabled'    => in_array($customer_group->code, ['NOT LOGGED IN', 'General']),
    'price'      => 10,
]);
addMethod('id_008', [
    'title'      => "NOT LOGGED IN and General groups by their ID",
    'enabled'    => in_array($customer_group->id, [0, 1]),
    'price'      => 10,
]);


addMethod('id_009', [
    'title'      => "Shipping",
    'price'      => 0.1 * $quote->subtotal + 10.00,
]);

$standard = addMethod('standard', [
    'title'      => "Standard Shipping",
    'enabled'    => $quote->subtotal < 1000.00,
    'price'      => 10,
]);
addMethod('express', [
    'title'      => "Express Shipping",
    'enabled'    => $standard->enabled && $request->package_weight < 10,
    'price'      => 12,
]);

// If the package weight is lower or equal to 0.5, the price will be 5.30
// If the package weight is lower or equal to 1.0, the price will be 6.50
// In others cases, the price will be 7.50
addMethod('id_010', [
    'title'      => "Shipping",
    'price'      => array_reduce([ [0.7, 5.30], [1.0, 6.50], ['*', 7.50] ], function ($carry, $item) {
                        global $request;
                        if (isset($carry)) return $carry;
                        if (isset($item[0]) && ($request->package_weight <= $item[0] || $item[0] == '*')) {
                            $carry = $item[1];
                        }
                        return $carry;
                    }),
]);
// You can exclude the upper limit value by adding an argument false
addMethod('id_011', [
    'title'      => "Shipping",
    'price'      => array_reduce([ [0.7, 5.30], [1.0, 6.50], ['*', 7.50] ], function ($carry, $item) {
                        global $request;
                        if (isset($carry)) return $carry;
                        if (isset($item[0]) && ($request->package_weight < $item[0] || $item[0] == '*')) {
                            $carry = $item[1];
                        }
                        return $carry;
                    }),
]);

// If the coupon code is equal to "coupon1", the price will be 5.30
// If the coupon code is equal to "coupon2", the price will be 6.50
// In others cases, the price will be 7.50
addMethod('id_012', [
    'title'      => "Shipping",
    'price'      => [ 'coupon1' => 5.30, 'coupon2' => 6.50 ][$quote->coupon_code] ?? 7.50,
]);

addMethod('id_013', [
    'title'      => "If at least one product has the attribute 'name' equal to 'Cat'",
    'enabled'    => count(
                        array_filter($request->all_items, function ($item) {
                            return $item->product->name == 'Cat';
                        })
                    ) > 0,
    'price'      => 10
]);
addMethod('id_014', [
    'title'      => "If all items have the option 'Size' greater or equal to 1",
    'enabled'    => count(
                        array_filter($request->all_items, function ($item) {
                            return isset($item->options['Size']) && $item->options['Size']['value'] >= 1;
                        })
                    ) == $request->package_qty,
    'price'      => 10
]);
addMethod('id_015', [
    'title'      => "More than 3 sku differents",
    'enabled'    => count(
                        array_unique(
                            array_map(function ($item) {
                                return $item->product->sku;
                            }, $request->all_items)
                        )
                    ) > 3,
    'price'      => 10
]);
addMethod('id_016', [
    'title'      => "Sum of all options 'Size' is greater than 30",
    'enabled'    => array_sum(
                        array_map(
                            function ($item) {
                                return $item->options['Size']['value'] ?? 0;
                            },
                            $request->all_items
                        )
                    ) > 30,
    'price'      => 10
]);
addMethod('id_017', [
    'title'      => "Minimum price excluding tax without discount is greater to 10",
    'enabled'    => min(
                        array_map(
                            function ($item) {
                                return $item->base_original_price;
                            },
                            $request->all_items
                        )
                    ) > 10,
    'price'      => 10
]);
addMethod('id_018', [
    'title'      => "Maximum value of the option 'Size' is lower than 50",
    'enabled'    => max(
                        array_map(
                            function ($item) {
                                return $item->options['Size']['value'] ?? 0;
                            },
                            $request->all_items
                        )
                    ) < 50,
    'price'      => 10
]);
// shipping is a custom product attribute
addMethod('id_019', [
    'title'      => "Calculate shipping fees by product",
    'price'      => array_sum(
                        array_map(
                            function ($item) {
                                return $item->product->shipping * $item->qty;
                            },
                            $request->all_items
                        )
                    ),
]);
addMethod('id_020', [
    'title'      => "5 x weight of products that are in categories 2 or 3",
    'price'      => array_sum(
                        array_map(
                            function ($item) {
                                return count(array_intersect($item->product->category_ids, [2, 3]))>= 1
                                    ? $item->product->weight * $item->qty
                                    : 0;
                            },
                            $request->all_items
                        )
                    ) * 5.0,
]);
addMethod('id_021', [
    'title'      => "5 x weight of products that are in both categories 2 and 3",
    'price'      => array_sum(
                        array_map(
                            function ($item) {
                                return count(array_intersect($item->product->category_ids, [2, 3])) == 2
                                    ? $item->product->weight * $item->qty
                                    : 0;
                            },
                            $request->all_items
                        )
                    ) * 5.0,
]);

addMethod('id_022', [
    'title'      => "Sum of weight attributes of products in category 12",
    'price'      => array_sum(
                        array_map(
                            function ($item) {
                                return in_array(12, $item->product->category_ids)
                                    ? $item->product->weight : 0;
                            },
                            $request->all_items
                        )
                    ),
]);
addMethod('id_023', [
    'title'      => "Sum of weight attributes of products in categories whose id is 11 and 12",
    'price'      => array_sum(
                        array_map(
                            function ($item) {
                                return count(array_intersect($item->product->category_ids, [11, 12])) >= 1
                                    ? $item->product->weight : 0;
                            },
                            $request->all_items
                        )
                    ),
]);

addMethod('id_024', [
    'title'      => "Sum of weights of products having for first category id 12",
    'price'      => array_sum(
                        array_map(
                            function ($item) {
                                return $item->product->category_id == 12
                                    ? $item->product->weight : 0;
                            },
                            $request->all_items
                        )
                    ),
]);

EOT;
        
        $carrier = $this->_objectManager->create(\Owebia\AdvancedShipping\Model\Carrier::class);
        $request = $this->_objectManager->create(\Magento\Quote\Model\Quote\Address\RateRequest::class, [
            'data' => [
                'package_value' => 10,
                'dest_country_id' => 'FR',
                'dest_postcode' => '96000',
                'package_weight' => 0.7,
                'items' => [],
            ]
        ]);
        $carrier->initRegistry($request);
        
        $result = $helper->parse($config, $registry);
        return $this->resultRawFactory->create()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Content-type', 'text/html; charset=UTF-8', true)
            ->setContents(
                var_export(array_keys($request->getData()), true)
                . '<pre>'
                . var_export($result, true)
            );
    }
}
