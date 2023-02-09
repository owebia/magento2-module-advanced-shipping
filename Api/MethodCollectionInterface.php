<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Api;

use Countable;

interface MethodCollectionInterface extends Countable
{
    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set(string $name, $value): self;

    /**
     * @param string $value
     * @return $this
     */
    public function setTitle($value): self;

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnabled($value): self;

    /**
     * @param float $value
     * @return $this
     */
    public function setPrice($value): self;

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription($value): self;
}
