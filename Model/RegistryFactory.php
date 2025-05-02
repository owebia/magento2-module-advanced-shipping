<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Owebia\SharedPhpConfig\Api\RegistryInterface;
use Owebia\SharedPhpConfig\Api\RegistryInterfaceFactory;
use Owebia\SharedPhpConfig\Model\Wrapper;
use Owebia\SharedPhpConfig\Model\WrapperContext;

class RegistryFactory
{
    /**
     * @var RegistryInterfaceFactory
     */
    private RegistryInterfaceFactory $registryInterfaceFactory;

    /**
     * @var WrapperContext
     */
    private WrapperContext $wrapperContext;

    /**
     * @param RegistryInterfaceFactory $registryInterfaceFactory
     * @param WrapperContext $wrapperContext
     */
    public function __construct(
        RegistryInterfaceFactory $registryInterfaceFactory,
        WrapperContext $wrapperContext
    ) {
        $this->registryInterfaceFactory = $registryInterfaceFactory;
        $this->wrapperContext = $wrapperContext;
    }

    /**
     * @param RateRequest $request
     * @param array $data
     * @return RegistryInterface
     */
    public function createFromRateRequest(RateRequest $request, array $data = []): RegistryInterface
    {
        $registry = $this->registryInterfaceFactory->create();

        $wrap = fn(string $type, array $args = []) => $this->wrapperContext->create($type, $args);
        $requestWrapper = $wrap(Wrapper\Request::class, ['request' => $request]);
        $withRequest = ['requestWrapper' => $requestWrapper];
        $registry->register('request', $requestWrapper);
        $registry->register('info', $wrap(Wrapper\Info::class, ['data' => $data]));
        $registry->register('app', $wrap(Wrapper\App::class));
        $registry->register('quote', $wrap(Wrapper\Quote::class, $withRequest));
        $registry->register('customer', $wrap(Wrapper\Customer::class, $withRequest));
        $registry->register('customer_group', $wrap(Wrapper\CustomerGroup::class, $withRequest));
        $registry->register('variable', $wrap(Wrapper\Variable::class, $withRequest));
        $registry->register('store', $wrap(Wrapper\Store::class, $withRequest));

        return $registry;
    }
}
