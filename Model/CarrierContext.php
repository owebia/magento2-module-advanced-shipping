<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

class CarrierContext
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateRequestFactory
     */
    private $rateRequestFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory
     */
    private $rateErrorFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $rateFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\ResultFactory
     */
    private $trackFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\Result\ErrorFactory
     */
    private $trackErrorFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\Result\StatusFactory
     */
    private $trackStatusFactory;

    /**
     * @var \Owebia\AdvancedShipping\Model\ParserContextFactory
     */
    private $parserContextFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateRequestFactory $rateRequestFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateFactory
     * @param \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory
     * @param \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory
     * @param \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory
     * @param \Owebia\AdvancedShipping\Model\ParserContextFactory $parserContextFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateRequestFactory $rateRequestFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Owebia\AdvancedShipping\Model\ParserContextFactory $parserContextFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->rateRequestFactory = $rateRequestFactory;
        $this->rateErrorFactory = $rateErrorFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->rateFactory = $rateFactory;
        $this->trackFactory = $trackFactory;
        $this->trackErrorFactory = $trackErrorFactory;
        $this->trackStatusFactory = $trackStatusFactory;
        $this->parserContextFactory = $parserContextFactory;
        $this->logger = $logger;
    }

    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig()
    {
        return $this->scopeConfig;
    }

    /**
     * @return \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory
     */
    public function getRateErrorFactory()
    {
        return $this->rateErrorFactory;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return \Magento\Shipping\Model\Rate\ResultFactory
     */
    public function getRateFactory()
    {
        return $this->rateFactory;
    }

    /**
     * @return \Magento\Quote\Model\Quote\Address\RateRequestFactory
     */
    public function getRateRequestFactory()
    {
        return $this->rateRequestFactory;
    }

    /**
     * @return \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    public function getRateMethodFactory()
    {
        return $this->rateMethodFactory;
    }

    /**
     * @return \Magento\Shipping\Model\Tracking\ResultFactory
     */
    public function getTrackFactory()
    {
        return $this->trackFactory;
    }

    /**
     * @return \Magento\Shipping\Model\Tracking\Result\ErrorFactory
     */
    public function getTrackErrorFactory()
    {
        return $this->trackErrorFactory;
    }

    /**
     * @return \Magento\Shipping\Model\Tracking\Result\StatusFactory
     */
    public function getTrackStatusFactory()
    {
        return $this->trackStatusFactory;
    }

    /**
     * @return \Owebia\AdvancedShipping\Model\ParserContextFactory
     */
    public function getParserContextFactory()
    {
        return $this->parserContextFactory;
    }
}
