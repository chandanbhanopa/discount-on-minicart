console.log("My module file loaded");
define([
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (ko, Component, customerData, quote) {
    'use strict';

    return Component.extend({
        displaySubtotal: ko.observable(true),

        /**
         * @override
         */
        initialize: function () {
            this._super();
            this.cart = customerData.get('cart');
            console.log("Discount my amount",this.cart().discount_amount);
        },
        getDiscountAmount: function() {
        	this.cart = customerData.get('cart');
        	return  "$" + parseFloat(this.cart().discount_amount).toFixed(2);
        }
    });
});
