<?php

/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Owebia\AdvancedShipping\Model;

use Magento\Framework\Exception\LocalizedException;
use Owebia\AdvancedShipping\Api\MethodInterface;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult\Error;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult\Method;
use Owebia\AdvancedShipping\Model\Wrapper\RateResult\MethodCollection;
use Owebia\SharedPhpConfig\Api\FunctionProviderInterface;
use Owebia\SharedPhpConfig\Api\ParserContextInterface;
use Owebia\SharedPhpConfig\Api\RequiresParserContextInterface;
use Owebia\SharedPhpConfig\Model\Wrapper;

class MainFunctionProvider implements FunctionProviderInterface, RequiresParserContextInterface
{
    /**
     * @var array
     */
    private array $parsingResult = [];

    /**
     * @var array
     */
    private ParserContextInterface $parserContext;

    /**
     * @param ParserContextInterface $parserContext
     * @return $this
     */
    public function setParserContext(ParserContextInterface $parserContext): void
    {
        $this->parserContext = $parserContext;
    }

    /**
     * @return string[]
     */
    public function getFunctions(): array
    {
        return [
            'addMethod',
            'addError',
            'getMethods',
        ];
    }

    /**
     * @return MethodInterface
     * @throws LocalizedException
     */
    public function addMethod(): MethodInterface
    {
        $args = func_get_args();
        if (count($args) != 1 && count($args) != 2) {
            throw new LocalizedException(
                __("Invalid arguments count for %1 FuncCall", 'addMethod')
            );
        }

        $methodId = array_shift($args);
        if (!is_string($methodId) || !preg_match('#^[a-z][a-z0-9_]*$#', $methodId)) {
            throw new LocalizedException(
                // phpcs:ignore Generic.Files.LineLength.TooLong
                __("Invalid first argument for %1 FuncCall: the first argument must be a string and match the following pattern: %2", 'addMethod', '^[a-z][a-z0-9_]*$')
            );
        }

        $methodOptions = array_shift($args);
        if (empty($methodOptions)) {
            $methodOptions = [
                'title' => 'Method Title',
                'enabled' => true,
            ];
        } else {
            if (!is_array($methodOptions)) {
                throw new LocalizedException(
                    __("Invalid second argument for addMethod FuncCall: the second argument must be an array")
                );
            }

            if (isset($this->parsingResult[$methodId])) {
                throw new LocalizedException(
                    __("The method %1 already exists", $methodId)
                );
            }

            $methodOptions = $methodOptions + [ 'enabled' => true ];
            $price = $methodOptions['price'] ?? null;
            if ($price === null | $price === false) {
                throw new LocalizedException(
                    __("Invalid price")
                );
            }
        }

        /** @var MethodInterface $method */
        $method = $this->createMethod($methodOptions);
        $method->setId($methodId);
        $this->addResult($method, $methodId);

        return $method;
    }

    /**
     * @param string $message
     * @return Error
     */
    public function addError(string $message): Error
    {
        /** @var Wrapper\RateResult\Error $error */
        $error = $this->createWrapper(Error::class, ['data' => ['error' => $message]]);
        $this->addResult($error);
        return $error;
    }

    /**
     * @param callable|string|bool $filter
     * @return MethodCollection
     */
    public function getMethods($filter = false): MethodCollection
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
            fn($item) => $item instanceof MethodInterface && $filterFn($item)
        );
        return $this->createWrapper(MethodCollection::class, ['data' => $methods]);
    }

    /**
     * @return array
     */
    public function getParsingResult(): array
    {
        return $this->parsingResult;
    }

    /**
     * @param string $type
     * @param array $arguments
     * @return mixed
     */
    protected function createWrapper(string $type, array $arguments)
    {
        return $this->parserContext->getWrapperContext()->create($type, $arguments);
    }

    /**
     * @param array $methodOptions
     * @return MethodInterface
     */
    protected function createMethod(array $methodOptions): MethodInterface
    {
        return $this->createWrapper(Method::class, ['data' => $methodOptions]);
    }

    /**
     * @param Wrapper\AbstractWrapper $wrapper
     * @param string|null $id
     */
    private function addResult(Wrapper\AbstractWrapper $wrapper, ?string $id = null): void
    {
        if (isset($id)) {
            $this->parsingResult[$id] = $wrapper;
        } else {
            $this->parsingResult[] = $wrapper;
        }
    }
}
