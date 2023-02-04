<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Api\Data;

interface MethodInterface
{
    public const CUSTOM_DATA_PREFIX = 'custom.'; // Do not use '/' to avoid path access issue

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set(string $name, $value): self;

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name);

    /**
     * @param string $value
     * @return $this
     */
    public function setTitle($value): self;

    /**
     * @return string
     */
    public function getTitle(): ?string;

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnabled($value): self;

    /**
     * @return bool
     */
    public function getEnabled(): bool;

    /**
     * @param float $value
     * @return $this
     */
    public function setPrice($value): self;

    /**
     * @return float|null
     */
    public function getPrice(): ?float;

    /**
     * @param string $value
     * @return $this
     */
    public function setDescription($value): self;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;
}
