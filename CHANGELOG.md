
# Changelog

Module: `owebia/magento2-module-advanced-shipping`

### 6.1.1 (07 Feb, 2025)
- add `How to start?` section in the documentation
- fix Magento2 coding standard error: Avoid using self-closing tag with non-void html element
- update dependencies
  - `owebia/magento2-module-shared-php-config:6.1.1`: fix display help icon on configuration field only when available
  - `owebia/magento2-module-shared-php-config:6.1.0`:
    - fix compatibility with magento 2.4.7-p1
    - add support for nikic/php-parser ^5.0.0
    - drop support for nikic/php-parser <4.18

### 6.0.3 (24 Apr, 2024)
- remove PHP version dependency from composer.json, rely on owebia/magento2-module-shared-php-config
- fix TypeError: str_replace(): Argument #3 ($subject) must be of type array|string, null given (thanks to @Tomasz-Silpion)
- fix PHP 8.4 compatibility warnings: Implicitly marking a parameter as nullable is deprecated since PHP 8.4
- fix Magento2 coding standard warnings: Comment block is missing
- update dependencies
  - `owebia/magento2-module-shared-php-config:6.0.3`:
    - add support for PHP 8.3 & PHP 8.4
    - fix PHP 8.4 compatibility warnings: Implicitly marking a parameter as nullable is deprecated since PHP 8.4
    - fix Magento2 coding standard warnings: Comment block is missing

### 6.0.2 (02 Aug, 2023)
- fix TypeError: Owebia\SharedPhpConfig\Model\Parser::parse(): Argument #2 ($configuration) must be of type string, null given
- update dependencies
  - `owebia/magento2-module-shared-php-config:6.0.2`: fix TypeError: Owebia\SharedPhpConfig\Model\Wrapper\ArrayWrapper::loadData(): Argument #1 ($key) must be of type string, int given

### 6.0.1 (26 May, 2023)
- add support for PHP 8.2
- ⚠️ breaking changes: internal classes refactored
- ✨ new api:
  - `Api\MethodCollectionInterface`
  - `Api\MethodInterface`
- improve code quality
- use `\Owebia\SharedPhpConfig\Api\FunctionProviderInterface`
- update documentation
- update dependencies
  - `owebia/magento2-module-shared-php-config:6.0.1`:
    - add support for PHP 8.2
    - ⚠️ breaking changes:
      - drop support for PHP < 7.4
      - drop support for Magento < 2.2
      - internal classes refactored
    - ✨ new api:
      - `Api\FunctionProviderInterface`
      - `Api\FunctionProviderPoolInterface`
      - `Api\ParserContextInterface`
      - `Api\ParserInterface`
      - `Api\RegistryInterface`
      - `Api\RequiresParserContextInterface`
    - improve code quality:
      - add php doc
      - type enforced
      - use modern syntax
      - reduce class dependencies

### 2.8.14 (28 Apr, 2023)
- add support for PHP 8.2
- update dependencies
  - `owebia/magento2-module-shared-php-config:3.0.8`: add support for PHP 8.2

### 2.8.13 (30 Nov, 2022)
- fix custom data retrieval issue
- improve handling of custom data in `\Magento\Framework\Reflection\DataObjectProcessor` to avoid errors with unsupported types
- improve code quality

### 2.8.12 (13 May, 2022)
- update dependencies
  - `owebia/magento2-module-shared-php-config:3.0.7`: apply Magento2 coding standard

### 2.8.11 (15 Apr, 2022)
- apply PSR-12 coding standard
- apply Magento2 coding standard
- use `bool` instead of `boolean` in PHPDoc
- fix constant declaration
- drop support for PHP versions 5.5, 5.6 & 7.0
- add support for PHP versions 8.0 & 8.1
- update dependencies
  - `owebia/magento2-module-shared-php-config:3.0.6`:
    - apply PSR-12 coding standard
    - use `bool` instead of `boolean` in PHPDoc
    - fix constant declaration and usage
    - fix signature mismatch errors while keeping backward compatibility
    - drop support for PHP versions 5.5, 5.6 & 7.0
    - add support for PHP versions 8.0 & 8.1
    - fix phpunit compatibility

### 2.8.10 (17 Sep, 2021)
- use `present` as copyright ending year
- update documentation
- update dependencies
  - `owebia/magento2-module-shared-php-config:3.0.5`: use `present` as copyright ending year

### 2.8.9 (14 May, 2021)
- add changelog
- remove promotional links in `etc/adminhtml/system.xml`
- update documentation
- update copyright year
- update translations
- update dependencies
  - `owebia/magento2-module-shared-php-config:3.0.4`: add changelog

### 2.8.8 (21 Oct, 2020)
- fix config scope

### 2.8.7 (13 Oct, 2020)
- fix issue when `\Magento\Framework\DataObject` is overriden

### 2.8.6 (02 Oct, 2020)
- fix JS trailing comma issue with IE 11

### 2.8.5 (09 Sep, 2020)
- update dependencies
  - `owebia/magento2-module-shared-php-config:3.0.3`: fix misspelled variables
  - `owebia/magento2-module-shared-php-config:3.0.2`: fix [#84](https://github.com/owebia/magento2-module-advanced-shipping/issues/84): category name retrieval issue
  - `owebia/magento2-module-shared-php-config:3.0.1`: add Magento 2.4.0 & PHP 7.4 support

### 2.8.4 (10 Jun, 2020)
- fix refresh on shipping address change

### 2.8.3 (29 May, 2020)
- add sequence in `etc/module.xml`

### 2.8.2 (28 May, 2020)
- fix mandatory steps in production in README.md and the documentation

### 2.8.1 (22 May, 2020)
- add `etc/csp_whitelist.xml`
- update license section in `etc/adminhtml/system.xml`
- remove the copyright year from file headers
- improve translations
- improve code quality
- update README.md
- update documentation
- update dependencies
  - add support for PHP 7.4
  - remove `magento2-module-advanced-setting-core:^2.8.0`
  - `owebia/magento2-module-shared-php-config:3.0.0`:
    - add experimental subtotal incl and excl tax calculation
    - remove the copyright year from file headers
    - improve translations
    - improve code quality
    - fix [#78](https://github.com/owebia/magento2-module-advanced-shipping/issues/78): can't access to `$app` methods
    - rename module from `Owebia_AdvancedSettingCore` to `Owebia_SharedPhpConfig`

### 2.8.0 (20 Mar, 2020)
- update documentation
- update dependencies
  - `owebia/magento2-module-advanced-setting-core:2.8.0`:
    - update copyright year on modified files
    - improve code quality
    - use `\Magento\Framework\Escaper` in Model wrappers instead of `htmlspecialchars`
    - add unit tests
    - refactor `\Owebia\AdvancedSettingCore\Helper\Evaluator` class
    - fix class checking in `\Owebia\AdvancedSettingCore\Helper\Evaluator`
    - parse configuration as PHP7
    - add support for array item assignment
    - add support for assignment operators and incrementing/decrementing operators
    - add support for the `use` language construct in anonymous functions
    - add support for `array_key_exists`, `json_decode` and `json_encode` functions

### 2.7.0 (05 Feb, 2020)
- update documentation
- update dependencies
  - `owebia/magento2-module-advanced-setting-core:2.7.0`: no change

### 2.6.5 (05 Feb, 2020)
- update copyright year
- update documentation
- update dependencies
  - `owebia/magento2-module-advanced-setting-core:2.6.3`:
    - update copyright year
    - add support for Inventory Management (Magento >= 2.3)

### 2.6.4 (10 Oct, 2019)
- ...
