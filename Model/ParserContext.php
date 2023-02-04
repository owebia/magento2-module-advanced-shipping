<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

use Owebia\AdvancedShipping\Api\Data\MethodInterface;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult\Error;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult\Method;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult\MethodCollection;
use Owebia\SharedPhpConfig\Model\Wrapper;

class ParserContext extends \Owebia\SharedPhpConfig\Model\ParserContext
{
    /**
     * @var array
     */
    private $parsingResult;

    /**
     * @var string|null
     */
    private $currentMethodId = null;

    /**
     * @param string $configuration
     * @param bool $debug
     * @return array
     */
    protected function doParse(string $configuration, bool $debug): array
    {
        $this->parsingResult = [];

        /** @var Parser $parser */
        $parser = $this->getParserFactory()->create(['parserContext' => $this]);
        $parser->parse($configuration, $debug);

        return $this->parsingResult;
    }

    /**
     * @param string $error
     */
    public function addParsingError(string $error): void
    {
        $this->addError($error, $this->currentMethodId);
    }

    /**
     * @return string[]
     */
    public function getFunctionMap(): array
    {
        return [
            'addMethod' => 'addMethodFunction',
            'addError' => 'addErrorFunction',
            'getMethods' => 'getMethodsFunction',
        ];
    }

    /**
     * @return Method
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addMethodFunction(): Method
    {
        $args = func_get_args();
        if (count($args) != 1 && count($args) != 2) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __("Invalid arguments count for %1 FuncCall", 'addMethod')
            );
        }

        $methodId = array_shift($args);
        if (!is_string($methodId) || !preg_match('#^[a-z][a-z0-9_]*$#', $methodId)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                // phpcs:ignore Generic.Files.LineLength.TooLong
                __("Invalid first argument for %1 FuncCall: the first argument must be a string and match the following pattern: %2", 'addMethod', '^[a-z][a-z0-9_]*$')
            );
        }

        $this->currentMethodId = $methodId;

        $methodOptions = array_shift($args);
        if (empty($methodOptions)) {
            $methodOptions = [
                'title' => 'Method Title',
                'enabled' => true,
            ];
        } else {
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

            $methodOptions = $methodOptions + [ 'enabled' => true ];
            $price = $methodOptions['price'] ?? null;
            if ($price === null | $price === false) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __("Invalid price")
                );
            }
        }

        /** @var MethodInterface $method */
        $method = $this->createMethod($methodOptions);
        $method->setId($methodId);
        $this->parsingResult[$methodId] = $method;
        $this->currentMethodId = null;
        return $method;
    }

    /**
     * @param string $message
     * @return Error
     */
    public function addErrorFunction($message): Error
    {
        return $this->addError($message);
    }

    /**
     * @param callable|string|bool $filter
     * @return MethodCollection
     */
    public function getMethodsFunction($filter = false): MethodCollection
    {
        if (is_callable($filter)) {
            $filterFn = $filter;
        } elseif (is_string($filter)) {
            $codePattern = $filter;
            $filterFn = fn($item) => $item->id === $codePattern;
        } elseif (is_bool($filter)) {
            $filterFn = fn($item) => !$filter || $item->enabled;
        } else {
            $filterFn = fn($item) => true;
        }

        $methods = array_filter(
            $this->parsingResult,
            fn($item) => $item instanceof Method && $filterFn($item)
        );
        return $this->getWrapperContext()->createWrapper(MethodCollection::class, ['data' => $methods]);
    }

    /**
     * @param array $methodOptions
     * @return MethodInterface
     */
    protected function createMethod(array $methodOptions): MethodInterface
    {
        return $this->getWrapperContext()->createWrapper(Method::class, ['data' => $methodOptions]);
    }

    /**
     * @param string $message
     * @param string|null $id
     * @return Error
     */
    private function addError(string $message, $id = null): Error
    {
        /** @var Wrapper\RateResult\Error $error */
        $error = $this->getWrapperContext()->createWrapper(Error::class, ['data' => ['error' => $message]]);
        if (!empty($id)) {
            $error->setId($id);
            $this->parsingResult[$id] = $error;
        } else {
            $this->parsingResult[] = $error;
        }

        return $error;
    }
}
