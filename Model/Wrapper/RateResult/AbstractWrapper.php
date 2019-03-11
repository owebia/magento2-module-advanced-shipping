<?php
/**
 * Copyright © 2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Model\Wrapper\RateResult;

class AbstractWrapper extends \Owebia\AdvancedSettingCore\Model\Wrapper\ArrayWrapper
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritDoc}
     * @see \Owebia\AdvancedSettingCore\Model\Wrapper\AbstractWrapper::loadData()
     */
    protected function loadData($key)
    {
        switch ($key) {
            case 'id':
                return $this->id;
            default:
                return isset($this->data[$key]) ? $this->data[$key] : null;
        }
    }
}
