<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequestFactory;
use Magento\Quote\Model\Quote\Address\RateResult;
use Magento\Shipping\Model\Rate;
use Magento\Shipping\Model\Tracking;
use Owebia\AdvancedShipping\Model\MainFunctionProviderFactory;
use Owebia\AdvancedShipping\Model\RegistryFactory;
use Owebia\SharedPhpConfig\Api\ParserInterface;
use Owebia\SharedPhpConfig\Api\ParserContextInterfaceFactory;
use Psr\Log\LoggerInterface;

class CarrierContext
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var RateRequestFactory
     */
    private RateRequestFactory $rateRequestFactory;

    /**
     * @var RateResult\ErrorFactory
     */
    private RateResult\ErrorFactory $rateErrorFactory;

    /**
     * @var RateResult\MethodFactory
     */
    private RateResult\MethodFactory $rateMethodFactory;

    /**
     * @var Rate\ResultFactory
     */
    private Rate\ResultFactory $rateFactory;

    /**
     * @var Tracking\ResultFactory
     */
    private Tracking\ResultFactory $trackFactory;

    /**
     * @var Tracking\Result\ErrorFactory
     */
    private Tracking\Result\ErrorFactory $trackErrorFactory;

    /**
     * @var Tracking\Result\StatusFactory
     */
    private Tracking\Result\StatusFactory $trackStatusFactory;

    /**
     * @var MainFunctionProviderFactory
     */
    private MainFunctionProviderFactory $mainFunctionProviderFactory;

    /**
     * @var RegistryFactory
     */
    private RegistryFactory $registryFactory;

    /**
     * @var ParserInterface
     */
    private ParserInterface $parser;

    /**
     * @var ParserContextInterfaceFactory
     */
    private ParserContextInterfaceFactory $parserContextFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param RateRequestFactory $rateRequestFactory
     * @param RateResult\ErrorFactory $rateErrorFactory
     * @param RateResult\MethodFactory $rateMethodFactory
     * @param Rate\ResultFactory $rateFactory
     * @param Tracking\ResultFactory $trackFactory
     * @param Tracking\Result\ErrorFactory $trackErrorFactory
     * @param Tracking\Result\StatusFactory $trackStatusFactory
     * @param MainFunctionProviderFactory $mainFunctionProviderFactory
     * @param RegistryFactory $registryFactory
     * @param ParserInterface $parser
     * @param ParserContextInterfaceFactory $parserContextFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        RateRequestFactory $rateRequestFactory,
        RateResult\ErrorFactory $rateErrorFactory,
        RateResult\MethodFactory $rateMethodFactory,
        Rate\ResultFactory $rateFactory,
        Tracking\ResultFactory $trackFactory,
        Tracking\Result\ErrorFactory $trackErrorFactory,
        Tracking\Result\StatusFactory $trackStatusFactory,
        MainFunctionProviderFactory $mainFunctionProviderFactory,
        RegistryFactory $registryFactory,
        ParserInterface $parser,
        ParserContextInterfaceFactory $parserContextFactory,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->rateRequestFactory = $rateRequestFactory;
        $this->rateErrorFactory = $rateErrorFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->rateFactory = $rateFactory;
        $this->trackFactory = $trackFactory;
        $this->trackErrorFactory = $trackErrorFactory;
        $this->trackStatusFactory = $trackStatusFactory;
        $this->mainFunctionProviderFactory = $mainFunctionProviderFactory;
        $this->registryFactory = $registryFactory;
        $this->parser = $parser;
        $this->parserContextFactory = $parserContextFactory;
        $this->logger = $logger;
    }

    /**
     * @return ScopeConfigInterface
     */
    public function getScopeConfig(): ScopeConfigInterface
    {
        return $this->scopeConfig;
    }

    /**
     * @return RateRequestFactory
     */
    public function getRateRequestFactory(): RateRequestFactory
    {
        return $this->rateRequestFactory;
    }

    /**
     * @return RateResult\ErrorFactory
     */
    public function getRateErrorFactory(): RateResult\ErrorFactory
    {
        return $this->rateErrorFactory;
    }

    /**
     * @return RateResult\MethodFactory
     */
    public function getRateMethodFactory(): RateResult\MethodFactory
    {
        return $this->rateMethodFactory;
    }

    /**
     * @return Rate\ResultFactory
     */
    public function getRateFactory(): Rate\ResultFactory
    {
        return $this->rateFactory;
    }

    /**
     * @return Tracking\ResultFactory
     */
    public function getTrackFactory(): Tracking\ResultFactory
    {
        return $this->trackFactory;
    }

    /**
     * @return Tracking\Result\ErrorFactory
     */
    public function getTrackErrorFactory(): Tracking\Result\ErrorFactory
    {
        return $this->trackErrorFactory;
    }

    /**
     * @return Tracking\Result\StatusFactory
     */
    public function getTrackStatusFactory(): Tracking\Result\StatusFactory
    {
        return $this->trackStatusFactory;
    }

    /**
     * @return MainFunctionProviderFactory
     */
    public function getMainFunctionProviderFactory(): MainFunctionProviderFactory
    {
        return $this->mainFunctionProviderFactory;
    }

    /**
     * @return RegistryFactory
     */
    public function getRegistryFactory(): RegistryFactory
    {
        return $this->registryFactory;
    }

    /**
     * @return ParserInterface
     */
    public function getParser(): ParserInterface
    {
        return $this->parser;
    }

    /**
     * @return ParserContextInterfaceFactory
     */
    public function getParserContextFactory(): ParserContextInterfaceFactory
    {
        return $this->parserContextFactory;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
