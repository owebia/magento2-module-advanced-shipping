<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model\Wrapper\RateResult;

use Owebia\AdvancedShipping\Api\Data\MethodCollectionInterface;
use Owebia\SharedPhpConfig\Model\Wrapper\ArrayWrapper;

class MethodCollection extends ArrayWrapper implements MethodCollectionInterface
{
    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set(string $name, $value): self
    {
        foreach ($this->data as $method) {
            $method->set($name, $value);
        }
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTitle($value): self
    {
        foreach ($this->data as $method) {
            $method->setTitle($value);
        }
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnabled($value): self
    {
        foreach ($this->data as $method) {
            $method->setEnabled($value);
        }
        return $this;
    }

    /**
     * @param double $value
     * @return $this
     */
    public function setPrice($value): self
    {
        foreach ($this->data as $method) {
            $method->setPrice($value);
        }
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription($value): self
    {
        foreach ($this->data as $method) {
            $method->setDescription($value);
        }
        return $this;
    }

    /**
     * @param mixed $value
     * @param string|null $variableName
     * @return mixed
     */
    protected function convertToString($value, $variableName = null)
    {
        if (is_object($value) && $value instanceof Method) {
            return $value->__toString();
        } else {
            return parent::convertToString($value, $variableName);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            $className = get_class($this);
            $varName = lcfirst(($pos = strrpos($className, '\\')) ? substr($className, $pos + 1) : $className);
            $output = "/** @var \\$className \${$varName}"
                . " */\n\${$varName} ";
            return $output . $this->help();
        } catch (\Exception $e) {
            if (isset($output)) {
                return $output . $e->getMessage();
            }
            return $e->getMessage();
        }
    }
}
