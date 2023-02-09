<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model\Wrapper\RateResult;

use Owebia\AdvancedShipping\Api\MethodInterface;

class Method extends AbstractWrapper implements MethodInterface
{
    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set(string $name, $value): self
    {
        return $this->setData(MethodInterface::CUSTOM_DATA_PREFIX . $name, $value);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function setData(string $name, $value): self
    {
        $this->data[$name] = $value;
        $this->cache->setData($name, $value);
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->getData(MethodInterface::CUSTOM_DATA_PREFIX . $name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getData(string $name)
    {
        return $this->$name;
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getCustomData()
    {
        $dataObject = $this->wrapperContext->create(\Magento\Framework\DataObject::class);
        foreach ($this->data as $name => $value) {
            if (substr($name, 0, strlen(MethodInterface::CUSTOM_DATA_PREFIX)) === MethodInterface::CUSTOM_DATA_PREFIX) {
                $dataObject->setData(substr($name, strlen(MethodInterface::CUSTOM_DATA_PREFIX)), $value);
            } elseif ($name == 'description') {
                $dataObject->setData($name, $value);
            }
        }

        return $dataObject;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTitle($value): self
    {
        return $this->setData('title', (string)$value);
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        $value = $this->data->getData('title');
        return isset($value) ? (string)$value : true;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnabled($value): self
    {
        return $this->setData('enabled', (bool)$value);
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        $value = $this->data->getData('enabled');
        return isset($value) ? (bool)$value : true;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setPrice($value): self
    {
        return $this->setData('price', (float)$value);
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        $value = $this->data->getData('price');
        return isset($value) ? (float)$value : null;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription($value): self
    {
        return $this->setData('description', (string)$value);
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        $value = $this->data->getData('description');
        return isset($value) ? (string)$value : null;
    }
}
