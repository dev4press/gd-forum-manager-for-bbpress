/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdfar_manager_data, ajaxurl*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdfar = window.wp.gdfar || {};

    window.wp.gdfar.manager = {
        init: function () {
            $(document).on("change", ".gdfar-ctrl-topic .gdfar-ctrl-checkbox", function(e){
                var checked = $(".gdfar-ctrl-topic .gdfar-ctrl-checkbox:checked").length;

                if (checked === 0) {
                    $(".gdfar-ctrl-topic .gdfar-ctrl-checkbox").removeClass("gdfar-is-on");
                } else {
                    $(".gdfar-ctrl-topic .gdfar-ctrl-checkbox").addClass("gdfar-is-on");
                }
            });

            $(document).on("change", ".gdfar-ctrl-forum .gdfar-ctrl-checkbox", function(e){
                var checked = $(".gdfar-ctrl-forum .gdfar-ctrl-checkbox:checked").length;

                if (checked === 0) {
                    $(".gdfar-ctrl-forum .gdfar-ctrl-checkbox").removeClass("gdfar-is-on");
                } else {
                    $(".gdfar-ctrl-forum .gdfar-ctrl-checkbox").addClass("gdfar-is-on");
                }
            });

            $(document).on("click", "#gdfar-modal-edit-submit", function(e){
                if ($("#gdfar-modal-edit").hasClass("is-open")) {
                    $("#gdfar-manager-form-edit").ajaxSubmit({
                        success: function(json) {
                            // location.reload(false);
                        },
                        type: "post", dataType: "json",
                        url: ajaxurl + "?action=gdfar_process_edit"
                    });
                }
            });

            $(document).on("click", ".gdfar-ctrl-wrapper .gdfar-ctrl-edit", function(e){
                e.preventDefault();

                var wrapper = $(this).closest(".gdfar-ctrl-wrapper"),
                    type = wrapper.data("type"), id = wrapper.data("id");

                $("#gdfar-modal-edit-content").html('<div class="gdfar-dialog-message">' + gdfar_manager_data.message.please_wait + '</div>');
                $("#gdfar-modal-edit-title").html(gdfar_manager_data.titles.edit[type]);

                MicroModal.show("gdfar-modal-edit", {
                    onShow: function() {}
                });

                $.ajax({
                    success: function(html) {
                        $("#gdfar-modal-edit-content").html(html);
                    },
                    type: "post", dataType: "html", data: {
                        is: gdfar_manager_data.bbpress.is,
                        forum: gdfar_manager_data.bbpress.forum_id,
                        type: type,
                        id: id
                    },
                    url: gdfar_manager_data.ajaxurl + "?action=gdfar_request_edit&_ajax_nonce=" + gdfar_manager_data.nonce
                });
            });
        }
    };

    wp.gdfar.manager.init();
})(jQuery, window, document);
