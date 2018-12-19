<?php
/**
 * Copyright © 2016-2018 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Owebia\AdvancedShippingSetting\Model;

class CallbackHandler extends \Owebia\AdvancedSettingCore\Model\CallbackHandler
{
    /**
     * @var array
     */
    protected $parsingResult = [];

    /**
     * @var string|null
     */
    protected $currentMethodId = null;

    /**
     * @return \Owebia\AdvancedSettingCore\Model\Wrapper\ArrayWrapper
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addMethodCallback()
    {
        $args = func_get_args();
        if (count($args) != 2) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __("Invalid arguments count for addMethod FuncCall")
            );
        }

        $methodId = array_shift($args);
        if (!is_string($methodId) || !preg_match('#^[a-z][a-z0-9_]*$#', $methodId)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __("Invalid first argument for addMethod FuncCall: the first argument must be a string and match the following pattern : ^[a-z][a-z0-9_]*$")
            );
        }
        $this->currentMethodId = $methodId;

        $methodOptions = array_shift($args);
        if (!is_array($methodOptions)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __("Invalid second argument for addMethod FuncCall: the second argument must be an array")
            );
        }
        if (isset($this->parsingResult[$methodId])) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __("The method %1 already exists", $methodId)
            );
        }
        $this->parsingResult[$methodId] = (object) $methodOptions;

        $this->currentMethodId = null;
        return $this->registry->create('ArrayWrapper', [ 'data' => $methodOptions ]);
    }

    /**
     * @return string
     */
    public function appendParsingError($msg)
    {
        if (isset($this->currentMethodId)) {
            $this->parsingResult[$this->currentMethodId] = (object) [ 'error' => $msg ];
        } else {
            $this->parsingResult[] = (object) [ 'error' => $msg ];
        }
        return $msg;
    }

    /**
     * @return array
     */
    public function getParsingResult()
    {
        return $this->parsingResult;
    }
}
