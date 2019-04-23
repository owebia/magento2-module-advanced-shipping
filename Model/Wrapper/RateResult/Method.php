<?php
/**
 * Copyright © 2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Model\Wrapper\RateResult;

class Method extends AbstractWrapper
{
    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value)
    {
        return $this->setData('custom/' . $name, $value);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function setData($name, $value)
    {
        $this->data[$name] = $value;
        $this->cache->setData($name, $value);
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->getData('custom/' . $name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getData($name)
    {
        return $this->$name;
    }

    /**
     * @param string $name
     * @return \Magento\Framework\DataObject
     */
    public function getCustomData()
    {
        $dataObject = $this->objectManager->create(\Magento\Framework\DataObject::class);
        foreach ($this->data as $name => $value) {
            if (substr($name, 0, 7) === 'custom/') {
                $dataObject->setData(substr($name, 7), $value);
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
    public function setTitle($value)
    {
        return $this->setData('title', $value);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnabled($value)
    {
        return $this->setData('enabled', $value);
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->getData('enabled');
    }

    /**
     * @param double $value
     * @return $this
     */
    public function setPrice($value)
    {
        return $this->setData('price', $value);
    }

    /**
     * @return double|null
     */
    public function getPrice()
    {
        return $this->getData('price');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription($value)
    {
        return $this->setData('description', $value);
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getData('description');
    }
}
