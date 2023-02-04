<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Owebia\AdvancedShipping\Model\CarrierContext;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult as RateResultWrapper;

class Carrier extends AbstractCarrier implements CarrierInterface
{
    public const CODE = 'owsh1';

    /**
     * Do not change variable name
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateRequestFactory
     */
    protected $rateRequestFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $rateFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\ResultFactory
     */
    protected $trackFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\Result\ErrorFactory
     */
    protected $trackErrorFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\Result\StatusFactory
     */
    protected $trackStatusFactory;

    /**
     * @var \Owebia\AdvancedShipping\Model\ParserContextFactory
     */
    private $parserContextFactory;

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

        // $stopToFirstMatch = $this->getConfigFlag('stop_to_first_match');

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateFactory->create();

        if (!isset($config) || !is_array($config)) {
            $this->_logger->debug("Owebia_AdvancedShipping : Invalid config");
            return false;
        }

        foreach ($config as $index => $item) {
            if ($item instanceof RateResultWrapper\Error) {
                $this->appendError($result, $item, $item->error);
            } elseif ($item instanceof RateResultWrapper\Method) {
                if ($item->enabled) {
                    $rate = $this->createMethod($index, $item);
                    $result->append($rate);
                }

                // if ($stopToFirstMatch) break;
            } else {
                $this->appendError($result, $item, "Invalid parsing result");
            }
        }

        return $result;
    }

    /**
     * @param string $methodId
     * @param RateResultWrapper\Method $method
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    protected function createMethod($methodId, RateResultWrapper\Method $method)
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $rate */
        $rate = $this->rateMethodFactory->create();
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($methodId);
        $title = $method->title;
        $rate->setMethodTitle($title ? $title : 'N/A');
        $description = $method->description ? $method->description : null;
        $rate->setMethodDescription($description);
        $rate->setCost($method->price);
        $rate->setPrice($method->price);

        $rate->setCustomData($method->getCustomData());

        return $rate;
    }

    /**
     * @param \Magento\Shipping\Model\Rate\Result $result
     * @param mixed $wrapper
     * @param string $msg
     * @return \Owebia\AdvancedShipping\Model\Carrier
     */
    protected function appendError(
        \Magento\Shipping\Model\Rate\Result $result,
        $wrapper,
        $msg
    ) {
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
    public function getConfig(RateRequest $request = null)
    {
        /** @var ParserContext $parserContext */
        $parserContext = $this->parserContextFactory->create([
            'request' => $request,
            'debugPrefix' => "Carrier[{$this->_code}].getConfig",
        ]);
        return $parserContext->parse(
            $this->getConfigData('config'),
            $this->getConfigFlag('debug'),
            [
                'carrierCode' => $this->getCarrierCode()
            ]
        );
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

        if ($result instanceof \Magento\Shipping\Model\Tracking\Result) {
            $trackings = $result->getAllTrackings();
            if ($trackings) {
                return $trackings[0];
            }
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
                            $this->getConfigData('tracking_url')
                        )
                    )
            );
    }
}
