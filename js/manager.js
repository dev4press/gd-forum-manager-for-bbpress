/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdfar_manager_data, ajaxurl*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdfar = window.wp.gdfar || {};

    window.wp.gdfar.manager = {
        storage: {
            checked: 0
        },
        init: function () {
            $(document).on("change", ".gdfar-ctrl-wrapper .gdfar-ctrl-checkbox", function(e){
                var checked = $(".gdfar-ctrl-checkbox:checked").length;

                if (checked === 0) {
                    $(".gdfar-ctrl-checkbox").removeClass("gdfar-is-on");
                } else {
                    $(".gdfar-ctrl-checkbox").addClass("gdfar-is-on");
                }
            });

            $(document).on("click", ".gdfar-ctrl-wrapper .gdfar-ctrl-edit", function(e){
                e.preventDefault();

                $("#gdfar-modal-edit-content").html(gdfar_manager_data.message.please_wait);

                MicroModal.show("gdfar-modal-edit", {
                    onShow: function() {
                        console.info("shown");
                    }
                });
            });
        }
    };

    wp.gdfar.manager.init();
})(jQuery, window, document);
