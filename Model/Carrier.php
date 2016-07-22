<?php
/**
 * Copyright © 2016 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShippingSetting\Model;

use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Carrier extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements \Magento\Shipping\Model\Carrier\CarrierInterface
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
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $rateMethodFactory = null;

    /**
     * @var \Owebia\ShippingCore\Helper\Registry
     */
    protected $registryHelper = null;

    /**
     * @var \Owebia\ShippingCore\Helper\Config
     */
    protected $configHelper = null;

    /**
     * @var \Owebia\ShippingCore\Logger\Logger
     */
    protected $debugLogger = null;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Owebia\ShippingCore\Helper\Registry $registryHelper
     * @param \Owebia\ShippingCore\Helper\Config $configHelper
     * @param \Owebia\ShippingCore\Logger\Logger $debugLogger
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Owebia\ShippingCore\Helper\Registry $registryHelper,
        \Owebia\ShippingCore\Helper\Config $configHelper,
        \Owebia\ShippingCore\Logger\Logger $debugLogger,
        array $data = []
    ) {
        parent::__construct($scopeInterface, $rateErrorFactory, $logger, $data);
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
            $this->_logger->debug("OWEBIA SHIPPING : Invalid config");
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
                $result->append($rate);

                // if ($stopToFirstMatch) break;
            }
        }

        return $result;
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
            $methodTitle = isset($method->title) ? $method->title : "Method `$methodId`";
            $error->setErrorMessage("$methodTitle - $msg");
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
        $config = $this->getConfig();
        if (!isset($config) || !is_array($config)) {
            $this->_logger->debug("OWEBIA SHIPPING : Invalid config");
            return array();
        }
        $allowedMethods = array();
        foreach ($config as $methodId => $method) {
            $allowedMethods[$methodId] = isset($method->title) ? $method->title : 'N/A';
        }
        return $allowedMethods;
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
            $this->registryHelper->init($this, $request);
            $configString = $this->getConfigData('config');
            $config = $this->configHelper->parse(
                $configString,
                $this->registryHelper,
                (bool) $this->getConfigData('debug')
            );
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
