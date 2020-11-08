/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global ajaxurl*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdfar = window.wp.gdfar || {};

    window.wp.gdfar.admin = {
        init: function() {
            this.toggle();
        },
        toggle: function() {
            $(document).on("click", ".gdfar-option-toggle", function(e) {
                e.preventDefault();

                var name = $(this).data("name"),
                    nonce = $(this).data("nonce"),
                    cell = $(this).parent();

                $.ajax({
                    type: "POST",
                    dataType: "html",
                    data: {
                        option: name
                    },
                    url: ajaxurl + "?action=gdfar_toggle_option&_ajax_nonce=" + nonce,
                    success: function(html) {
                        cell.replaceWith(html);
                    }
                });
            });
        }
    };

    $(document).ready(function() {
        wp.gdfar.admin.init();
    });
})(jQuery, window, document);
