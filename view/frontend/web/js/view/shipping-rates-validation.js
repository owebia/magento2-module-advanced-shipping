/**
 * Copyright © 2016-2017 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        '../model/shipping-rates-validator',
        '../model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        currentShippingRatesValidator,
        currentShippingRatesValidationRules
    ) {
        "use strict";
        defaultShippingRatesValidator.registerValidator('owsh1', currentShippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('owsh1', currentShippingRatesValidationRules);
        return Component;
    }
);
