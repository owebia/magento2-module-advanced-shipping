# Description

Advanced Shipping Module for Magento 2

## Add-ons for Advanced Shipping

You can find [add-ons for Advanced Shipping on Owebia Store](https://en.store.owebia.com/magento2-module-advanced-shipping.html).

## Installation

:warning: _Please note that you can only install the extension using composer._

* Backup your store database and web directory
* Open a terminal and move to Magento root directory
* Run these commands in your terminal

```shell
# You must be in Magento root directory
composer require owebia/magento2-module-advanced-shipping-setting:^2.0.8
php bin/magento cache:clean
php bin/magento module:enable Owebia_AdvancedSettingCore
php bin/magento module:enable Owebia_AdvancedShippingSetting
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

* If you are logged to Magento backend, logout from Magento backend and login again

## Documentation

[See the documentation](http://htmlpreview.github.io/?https://github.com/owebia/magento2-module-advanced-shipping-setting/blob/master/view/doc_en_US.html)

## License

Copyright © 2016-2017 Owebia. All rights reserved.

No warranty, explicit or implicit, provided.

Files can not be copied and/or distributed without the express permission of Owebia.


Icons:

https://fortawesome.github.io/Font-Awesome/

## Contributing

By contributing to this project, you grant a world-wide, royalty-free, perpetual, irrevocable, non-exclusive, transferable license to all users under the terms of the license(s) under which this project is distributed.
