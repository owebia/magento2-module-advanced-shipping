/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

/*browser:true*/
/*global define*/
define([
    'jquery',
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    '../model/new-shipping-rates-validator',
    '../model/new-shipping-rates-validation-rules'
], function (
    $,
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    newShippingRatesValidator,
    newShippingRatesValidationRules,
) {
    "use strict";

    var nested = function (o) {
        var _=function(o){this.o=o};
        _.prototype={
            _:function(n){var o=this.o;return new _(typeof o==='undefined'||o===null||typeof o[n]==='undefined'?null:o[n])},
            val:function(){return this.o}
        };
        return new _(o);
    };
    var validationRules = nested(window.checkoutConfig)._('owebia')._('advancedShipping')._('validationRules').val() || [];

    $.each(validationRules, function (carrierCode, rules) {
        defaultShippingRatesValidator.registerValidator(carrierCode, newShippingRatesValidator(carrierCode, rules));
        defaultShippingRatesValidationRules.registerRules(carrierCode, newShippingRatesValidationRules(carrierCode, rules));
    });
    return Component;
});
