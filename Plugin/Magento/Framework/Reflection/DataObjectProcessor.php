<?php
/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Plugin\Magento\Framework\Reflection;

class DataObjectProcessor
{
    /**
     * @param \Magento\Framework\Reflection\DataObjectProcessor $subject
     * @param callable $proceed
     * @param mixed $dataObject
     * @param string $dataObjectType
     * @return array
     */
    public function aroundBuildOutputDataArray(
        \Magento\Framework\Reflection\DataObjectProcessor $subject,
        callable $proceed,
        $dataObject,
        $dataObjectType
    ) {
        if ($dataObjectType === 'string[]'
            && $dataObject instanceof \Magento\Framework\DataObject
        ) {
            return array_map(
                'strval',
                $dataObject->toArray()
            );
        } else {
            return $proceed($dataObject, $dataObjectType);
        }
    }
}
