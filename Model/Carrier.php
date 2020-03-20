<?php
/**
 * Copyright © 2016-2019 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShipping\Model;

use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Owebia\AdvancedSettingCore\Model\Wrapper;
use Owebia\AdvancedShipping\Model\CallbackHandler;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult as RateResultWrapper;

class Carrier extends AbstractCarrier implements CarrierInterface
{

    const CODE = 'owsh1';

    /**
     * Do not change variable name
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $rateFactory = null;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateRequestFactory
     */
    protected $rateRequestFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $rateMethodFactory = null;

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
     * @var \Owebia\AdvancedSettingCore\Helper\Registry
     */
    protected $registryHelper = null;

    /**
     * @var \Owebia\AdvancedSettingCore\Helper\Config
     */
    protected $configHelper = null;

    /**
     * @var \Owebia\AdvancedSettingCore\Logger\Logger
     */
    protected $debugLogger = null;

    /**
     * @var \Owebia\AdvancedShipping\Model\CallbackHandlerFactory
     */
    protected $callbackHandlerFactory;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateFactory
     * @param \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory
     * @param \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory
     * @param \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory
     * @param \Magento\Quote\Model\Quote\Address\RateRequestFactory $rateRequestFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Owebia\AdvancedSettingCore\Helper\Registry $registryHelper
     * @param \Owebia\AdvancedSettingCore\Helper\Config $configHelper
     * @param \Owebia\AdvancedSettingCore\Logger\Logger $debugLogger
     * @param \Owebia\AdvancedShipping\Model\CallbackHandlerFactory $callbackHandlerFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateRequestFactory $rateRequestFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Owebia\AdvancedSettingCore\Helper\Registry $registryHelper,
        \Owebia\AdvancedSettingCore\Helper\Config $configHelper,
        \Owebia\AdvancedSettingCore\Logger\Logger $debugLogger,
        \Owebia\AdvancedShipping\Model\CallbackHandlerFactory $callbackHandlerFactory,
        array $data = []
    ) {
        parent::__construct($scopeInterface, $rateErrorFactory, $logger, $data);
        $this->rateFactory = $rateFactory;
        $this->rateRequestFactory = $rateRequestFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->trackFactory = $trackFactory;
        $this->trackErrorFactory = $trackErrorFactory;
        $this->trackStatusFactory = $trackStatusFactory;
        $this->registryHelper = $registryHelper;
        $this->configHelper = $configHelper;
        $this->debugLogger = $debugLogger;
        $this->callbackHandlerFactory = $callbackHandlerFactory;
    }

    /**
     *
     * @param RateRequest $request
     * @return boolean
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->isActive()) {
            return false;
        }

        $config = $this->getConfig($request);

        // $stopToFirstMatch = (boolean)$this->getConfigData('stop_to_first_match');

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
        if (empty($wrapper->id) || $this->getConfigData('showmethod') != 0) {
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
                $allowedMethods[$index] = isset($item->title) ? $item->title : 'N/A';
            }
        }

        return $allowedMethods;
    }

    public function initRegistry(RateRequest $request = null)
    {
        $this->registryHelper->init($request);
        $this->registryHelper->register(
            'info',
            $this->registryHelper->create(
                Wrapper\Info::class,
                [
                    'carrierCode' => $this->getCarrierCode()
                ]
            )
        );
    }

    /**
     * @param RateRequest|null $request
     * @return mixed|null
     */
    public function getConfig(RateRequest $request = null)
    {
        if ($this->isDebugEnabled()) {
            $this->debugLogger->collapseOpen("Carrier[{$this->_code}].getConfig", 'panel-primary');
        }
        $config = null;
        try {
            $this->initRegistry($request);
            $configString = $this->getConfigData('config');
            $callbackHandler = $this->callbackHandlerFactory->create();
            $callbackHandler->setRegistry($this->registryHelper);
            $this->configHelper->parse(
                $configString,
                $this->registryHelper,
                $callbackHandler,
                (bool) $this->getConfigData('debug')
            );
            $config = $callbackHandler->getParsingResult();
        } catch (\Exception $e) {
            $this->_logger->debug($e);
            if ($this->isDebugEnabled()) {
                $this->debugLogger->debug("Carrier[{$this->_code}].getConfig - Error - " . $e->getMessage());
            }
        }
        if ($this->isDebugEnabled()) {
            $this->debugLogger->collapseClose();
        }
        return $config;
    }

    /**
     * @return boolean
     */
    protected function isDebugEnabled()
    {
        return (bool) $this->getConfigData('debug');
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
