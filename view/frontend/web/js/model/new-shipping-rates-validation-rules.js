/**
 * Copyright © Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

/*global define*/
define([

], function (

) {
    "use strict";

    return function (carrierCode, rules) {
        return {
            carrierCode: carrierCode,

            getRules: function () {
                return rules;
            }
        };
    };
});
