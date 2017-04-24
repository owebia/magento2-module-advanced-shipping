<?php
/**
 * Copyright © 2016-2017 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShippingSetting\Model;

use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Carrier extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{

    const CODE = 'owsh1';

    /**
     * Do not change variable name
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $rateFactory = null;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $rateMethodFactory = null;

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
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Owebia\AdvancedSettingCore\Helper\Registry $registryHelper
     * @param \Owebia\AdvancedSettingCore\Helper\Config $configHelper
     * @param \Owebia\AdvancedSettingCore\Logger\Logger $debugLogger
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Owebia\AdvancedSettingCore\Helper\Registry $registryHelper,
        \Owebia\AdvancedSettingCore\Helper\Config $configHelper,
        \Owebia\AdvancedSettingCore\Logger\Logger $debugLogger,
        array $data = []
    ) {
        parent::__construct($scopeInterface, $rateErrorFactory, $logger, $data);
        $this->objectManager = $objectManager;
        $this->rateFactory = $rateFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->registryHelper = $registryHelper;
        $this->configHelper = $configHelper;
        $this->debugLogger = $debugLogger;
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
            $this->_logger->debug("Owebia_AdvancedShippingSetting : Invalid config");
            return false;
        }

        foreach ($config as $methodId => $method) {
            if (isset($method->error)) {
                $this->appendError($result, $methodId, $method, $method->error);
            } elseif (!isset($method->price)) {
                $this->appendError($result, $methodId, $method, "Invalid price: null");
            } elseif ($method->price === false) {
                $this->appendError($result, $methodId, $method, "Invalid price: false");
            } elseif (isset($method->enabled) && !$method->enabled) {
                // $this->appendError($result, $methodId, $method, "Method disabled");
            } elseif (isset($method->debug)) {
                $this->appendError($result, $methodId, $method, $method->debug);
            } else {
                $rate = $this->createMethod($methodId, $method);
                $result->append($rate);

                // if ($stopToFirstMatch) break;
            }
        }

        return $result;
    }

    /**
     * @param string $methodId
     * @param \stdClass $method
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    protected function createMethod($methodId, $method)
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $rate */
        $rate = $this->rateMethodFactory->create();
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($methodId);
        $title = isset($method->title) ? $method->title : 'N/A';
        $rate->setMethodTitle($title);
        $description = isset($method->description) ? $method->description : null;
        $rate->setMethodDescription($description);
        $rate->setCost($method->price);
        $rate->setPrice($method->price);
        return $rate;
    }

    /**
     * @param \Magento\Shipping\Model\Rate\Result $result
     * @param string $methodId
     * @param \stdClass $method
     * @param string $msg
     * @return \Owebia\AdvancedShippingSetting\Model\Carrier
     */
    protected function appendError(\Magento\Shipping\Model\Rate\Result $result, $methodId, \stdClass $method, $msg)
    {
        if ($this->getConfigData('showmethod') != 0) {
            $error = $this->_rateErrorFactory->create();
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $methodTitle = isset($method->title) ? $method->title
                : (!empty($methodId) ? "Method `$methodId` - " : '');
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
        $config = $this->getConfig($this->objectManager->create(RateRequest::class));
        if (!isset($config) || !is_array($config)) {
            $this->_logger->debug("Owebia_AdavancedShippingSetting : Invalid config");
            return [];
        }
        $allowedMethods = [];
        foreach ($config as $methodId => $method) {
            $allowedMethods[$methodId] = isset($method->title) ? $method->title : 'N/A';
        }
        return $allowedMethods;
    }

    public function initRegistry(RateRequest $request = null)
    {
        $this->registryHelper->init($request);
        $this->registryHelper->register('info', $this->registryHelper->create('Info', [
            'carrierCode' => $this->getCarrierCode()
        ]));
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
            $callbackHandler = $this->objectManager->create('Owebia\AdvancedShippingSetting\Model\CallbackHandler');
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
}
