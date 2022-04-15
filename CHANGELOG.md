
# Changelog

Module: `owebia/magento2-module-advanced-shipping`

### 2.8.11 (15 Apr, 2022)
- apply PSR-12 coding standard
- apply Magento2 coding standard
- use `bool` instead of `boolean` in PHPDoc
- fix constant declaration
- remove support for PHP versions 5.5, 5.6 & 7.0
- add support for PHP versions 8.0 & 8.1
- update dependencies
  - `owebia/magento2-module-shared-php-config:3.0.6`:
    - apply PSR-12 coding standard
    - use `bool` instead of `boolean` in PHPDoc
    - fix constant declaration and usage
    - fix signature mismatch errors while keeping backward compatibility
    - remove support for PHP versions 5.5, 5.6 & 7.0
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
