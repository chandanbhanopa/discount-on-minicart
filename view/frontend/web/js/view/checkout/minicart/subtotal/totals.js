define([
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (ko, Component, customerData, quote) {
    'use strict';

    return Component.extend({
       
        /**
         * @override
         */
        initialize: function () {
            this._super();
            this.cart = customerData.get('cart');
        },
        getDiscountAmount: function() {
            this.cart = customerData.get('cart');
        	return this.cart().discount_amount
        },
        getGrandTotalWithTax: function() {
            this.cart = customerData.get('cart');
            return this.cart().grand_total_with_tax;
        },
        getGrandTotalWithoutTax :function() {
            this.cart = customerData.get('cart');
            return this.cart().grand_total_without_tax;
        }
    });
});
