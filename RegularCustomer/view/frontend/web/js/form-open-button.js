define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('Oleksandrk.RegularDiscount_formOpenButton', {
        /**
         * Constructor
         * @private
         */
        _create: function () {

            $(this.element).on('click.oleksandrk_personal_discount_form_open', this.openRequestForm.bind(this));
        },

        /**
         * Generate event to open the form
         */
        openRequestForm: function () {
            $(document).trigger('oleksandrk_personal_discount_form_open');
        }
    });

    return $.Oleksandrk.RegularDiscount_formOpenButton;
});
