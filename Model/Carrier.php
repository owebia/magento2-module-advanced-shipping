<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateRequestFactory;
use Magento\Quote\Model\Quote\Address\RateResult;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Tracking;
use Owebia\AdvancedShipping\Model\CarrierContext;
use Owebia\AdvancedShipping\Model\RegistryFactory;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult as RateResultWrapper;
use Owebia\SharedPhpConfig\Api\ParserInterface;
use Owebia\SharedPhpConfig\Api\ParserContextInterfaceFactory;

class Carrier extends AbstractCarrier implements CarrierInterface
{
    public const CODE = 'owsh1';

    /**
     * Do not change variable name
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @var RateRequestFactory
     */
    protected RateRequestFactory $rateRequestFactory;

    /**
     * @var RateResult\MethodFactory
     */
    protected RateResult\MethodFactory $rateMethodFactory;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected \Magento\Shipping\Model\Rate\ResultFactory $rateFactory;

    /**
     * @var Tracking\ResultFactory
     */
    protected Tracking\ResultFactory $trackFactory;

    /**
     * @var Tracking\Result\ErrorFactory
     */
    protected Tracking\Result\ErrorFactory $trackErrorFactory;

    /**
     * @var Tracking\Result\StatusFactory
     */
    protected Tracking\Result\StatusFactory $trackStatusFactory;

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
     * @param CarrierContext $carrierContext
     * @param array $data
     */
    public function __construct(
        CarrierContext $carrierContext,
        array $data = []
    ) {
        $this->rateFactory = $carrierContext->getRateFactory();
        $this->rateRequestFactory = $carrierContext->getRateRequestFactory();
        $this->rateMethodFactory = $carrierContext->getRateMethodFactory();
        $this->trackFactory = $carrierContext->getTrackFactory();
        $this->trackErrorFactory = $carrierContext->getTrackErrorFactory();
        $this->trackStatusFactory = $carrierContext->getTrackStatusFactory();
        $this->trackStatusFactory = $carrierContext->getTrackStatusFactory();
        $this->mainFunctionProviderFactory = $carrierContext->getMainFunctionProviderFactory();
        $this->registryFactory = $carrierContext->getRegistryFactory();
        $this->parser = $carrierContext->getParser();
        $this->parserContextFactory = $carrierContext->getParserContextFactory();
        parent::__construct(
            $carrierContext->getScopeConfig(),
            $carrierContext->getRateErrorFactory(),
            $carrierContext->getLogger(),
            $data
        );
    }

    /**
     *
     * @param RateRequest $request
     * @return bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->isActive()) {
            return false;
        }

        $config = $this->getConfig($request);

        if (!isset($config) || !is_array($config)) {
            $this->_logger->debug("Owebia_AdvancedShipping : Invalid config");
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateFactory->create();

        foreach ($config as $index => $item) {
            if ($item instanceof RateResultWrapper\Error) {
                $this->appendError($result, $item, $item->error);
            } elseif ($item instanceof RateResultWrapper\Method) {
                if ($item->enabled) {
                    $rate = $this->createMethod($index, $item);
                    $result->append($rate);
                }
            } else {
                $this->appendError($result, $item, "Invalid parsing result");
            }
        }

        return $result;
    }

    /**
     * @param string $methodId
     * @param RateResultWrapper\Method $method
     * @return RateResult\Method
     */
    protected function createMethod(string $methodId, RateResultWrapper\Method $method): RateResult\Method
    {
        /** @var RateResult\Method $rate */
        $rate = $this->rateMethodFactory->create();
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($methodId);
        $rate->setMethodTitle($method->title ?: 'N/A');
        $rate->setMethodDescription($method->description ?: null);
        $rate->setCost($method->price);
        $rate->setPrice($method->price);
        $rate->setCustomData($method->getCustomData());
        return $rate;
    }

    /**
     * @param \Magento\Shipping\Model\Rate\Result $result
     * @param mixed $wrapper
     * @param string $msg
     * @return $this
     */
    protected function appendError(
        \Magento\Shipping\Model\Rate\Result $result,
        $wrapper,
        string $msg
    ): self {
        if (empty($wrapper->id) || $this->getConfigFlag('showmethod')) {
            $error = $this->_rateErrorFactory->create();
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $methodTitle = !empty($wrapper->title)
                ? $wrapper->title
                : (!empty($wrapper->id) ? "Method `{$wrapper->id}` - " : '');
            $error->setErrorMessage("$methodTitle $msg");
            $result->append($error);
        }

        return $this;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array @api
     */
    public function getAllowedMethods()
    {
        $config = $this->getConfig(
            $this->rateRequestFactory->create()
        );
        if (!isset($config) || !is_array($config)) {
            $this->_logger->debug("Owebia_AdvancedShipping : Invalid config");
            return [];
        }

        $allowedMethods = [];
        foreach ($config as $index => $item) {
            if ($item instanceof RateResultWrapper\Method) {
                $allowedMethods[$index] = $item->title ?? 'N/A';
            }
        }

        return $allowedMethods;
    }

    /**
     * @param RateRequest|null $request
     * @return mixed|null
     */
    public function getConfig(?RateRequest $request = null)
    {
        /** @var MainFunctionProvider $mainFunctionProvider */
        $mainFunctionProvider = $this->mainFunctionProviderFactory->create();
        $this->parser->parse(
            $this->parserContextFactory->create([
                'mainFunctionProvider' => $mainFunctionProvider,
                'registry' => $this->registryFactory->createFromRateRequest(
                    $request,
                    ['carrierCode' => $this->getCarrierCode()]
                ),
                'debugPrefix' => "Carrier[{$this->_code}].getConfig",
                'debug' => $this->getConfigFlag('debug'),
            ]),
            $this->getConfigData('config') ?? ''
        );
        return $mainFunctionProvider->getParsingResult();
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Get tracking information
     *
     * @param string $tracking
     * @return string|false
     * @api
     */
    public function getTrackingInfo($tracking)
    {
        $result = $this->getTracking($tracking);
        if ($result instanceof Tracking\Result) {
            return $result->getAllTrackings()[0] ?? false;
        } elseif (is_string($result) && !empty($result)) {
            return $result;
        }

        return false;
    }

    /**
     * Get tracking information
     *
     * @param string $tracking
     * @return string|false
     * @api
     */
    public function getTracking($tracking)
    {
        return $this->trackFactory->create()
            ->append(
                $this->trackStatusFactory->create()
                    ->setCarrier(self::CODE)
                    ->setCarrierTitle($this->getConfigData('title'))
                    ->setTracking($tracking)
                    ->setUrl(
                        str_replace(
                            '{{trackingNumber}}',
                            $tracking,
                            (string) $this->getConfigData('tracking_url')
                        )
                    )
            );
    }
}
