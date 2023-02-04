<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model\Wrapper\RateResult;

class AbstractWrapper extends \Owebia\SharedPhpConfig\Model\Wrapper\ArrayWrapper
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function loadData(string $key)
    {
        switch ($key) {
            case 'id':
                return $this->id;
            default:
                return $this->data[$key] ?? null;
        }
    }
}
