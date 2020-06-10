/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

/*global define*/
define([
    'jquery',
    'mageUtils',
    'mage/translate'
], function (
    $,
    utils,
    $t
) {
    "use strict";

    return function (carrierCode, rules) {
        return {
            carrierCode: carrierCode,
            validationErrors: [],

            validate: function (address) {
                var self = this;
                this.validationErrors = [];
                $.each(rules, function (field, rule) {
                    if (rule.required && (typeof address[field] !== 'undefined') && utils.isEmpty(address[field])) {
                        var message = $t('Field ') + field + $t(' is required.');
                        self.validationErrors.push(message);
                    }
                });
                return !Boolean(this.validationErrors.length);
            }
        };
    };
});
