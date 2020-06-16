/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdfar_manager_data, ajaxurl*/

;(function ($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdfar = window.wp.gdfar || {};

    window.wp.gdfar.manager = {
        shared: {
            process: function(json) {
                $(".gdfar-action").removeClass("gdfar-is-error");
                $(".gdfar-error-message").remove();

                $(".gdfar-modal__footer button").attr("disabled", false);

                if (json.status === "ok") {
                    if (json.errors > 0) {
                        $.each(json.elements, function (name, message) {
                            var el = $(".gdfar-action.gdfar-action-" + name);

                            el.addClass("gdfar-is-error");

                            $(".gdfar-conent-wrapper", el).append("<p class='gdfar-error-message'>" + message + '</p>');
                        });
                    } else {
                        location.reload(false);
                    }
                } else if (json.status === "error") {
                    alert(json.error);
                }
            }
        },
        edit: {
            dialog: function(e) {
                e.preventDefault();

                var wrapper = $(this).closest(".gdfar-ctrl-wrapper"),
                    type = wrapper.data("type"), id = wrapper.data("id");

                $("#gdfar-modal-edit-content").html('<div class="gdfar-dialog-message">' + gdfar_manager_data.message.please_wait + '</div>');
                $("#gdfar-modal-edit-title").html(gdfar_manager_data.titles.edit[type]);
                $(".gdfar-modal__footer button").attr("disabled", false);

                MicroModal.show("gdfar-modal-edit", {});

                $.ajax({
                    success: function (html) {
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
            },
            submit: function() {
                $(".gdfar-modal__footer button").attr("disabled", true);

                if ($("#gdfar-modal-edit").hasClass("is-open")) {
                    $("#gdfar-manager-form-edit").ajaxSubmit({
                        success: wp.gdfar.manager.shared.process,
                        type: "post", dataType: "json",
                        url: ajaxurl + "?action=gdfar_process_edit"
                    });
                }
            }
        },
        bulk: {
            dialog: function(e) {
                e.preventDefault();

                var wrapper = $(this).closest(".gdfar-bulk-control"), ids = [], i,
                    type = wrapper.data("type"), key = wrapper.data("key"),
                    sel = ".gdfar-ctrl-wrapper[data-key=" + key + "] input[type=checkbox]:checked";

                $(sel).each(function(){
                    ids.push($(this).parent().data("id"));
                });

                $("#gdfar-modal-bulk-content").html('<div class="gdfar-dialog-message">' + gdfar_manager_data.message.please_wait + '</div>');
                $("#gdfar-modal-bulk-title").html(gdfar_manager_data.titles.bulk[type]);
                $(".gdfar-modal__footer button").attr("disabled", false);

                MicroModal.show("gdfar-modal-bulk", {});

                $.ajax({
                    success: function (html) {
                        $("#gdfar-modal-bulk-content").html(html);

                        for (i = 0; i < ids.length; i++) {
                            $("#gdfar-modal-bulk-content form").prepend("<input type='hidden' name='gdfar[id][]' value='" + ids[i] + "' />");
                        }
                    },
                    type: "post", dataType: "html", data: {
                        is: gdfar_manager_data.bbpress.is,
                        forum: gdfar_manager_data.bbpress.forum_id,
                        type: type
                    },
                    url: gdfar_manager_data.ajaxurl + "?action=gdfar_request_bulk&_ajax_nonce=" + gdfar_manager_data.nonce
                });
            },
            submit: function() {
                $(".gdfar-modal__footer button").attr("disabled", true);

                if ($("#gdfar-modal-bulk").hasClass("is-open")) {
                    $("#gdfar-manager-form-bulk").ajaxSubmit({
                        success: wp.gdfar.manager.shared.process,
                        type: "post", dataType: "json",
                        url: ajaxurl + "?action=gdfar_process_bulk"
                    });
                }
            },
            update: function(bulk, selected, total) {
                $(".__selected", bulk).html(selected);
                $(".__total", bulk).html(total);
            },
            select: function(e) {
                e.preventDefault();

                var all = $(this).hasClass("__all"),
                    key = $(this).closest(".gdfar-bulk-control").data("key"),
                    sel = ".gdfar-ctrl-wrapper[data-key=" + key + "] input[type=checkbox]";

                $(sel).prop("checked", all).trigger("change");
            },
            forum: function() {
                var selector = ".gdfar-ctrl-forum .gdfar-ctrl-checkbox",
                    wrapper = $(this).parent(),
                    table = $(this).closest(".bbp-forums"),
                    bulk = $(".gdfar-bulk-forum-" + wrapper.data("key")),
                    checked = $(selector + ":checked", table).length,
                    total = $(selector, table).length;

                if (checked === 0) {
                    $(selector, table).removeClass("gdfar-is-on");
                    bulk.hide();
                } else {
                    $(selector, table).addClass("gdfar-is-on");
                    bulk.show();

                    wp.gdfar.manager.bulk.update(bulk, checked, total);
                }
            },
            topic: function() {
                var selector = ".gdfar-ctrl-topic .gdfar-ctrl-checkbox",
                    wrapper = $(this).parent(),
                    table = $(this).closest(".bbp-topics"),
                    bulk = $(".gdfar-bulk-topic-" + wrapper.data("key")),
                    checked = $(selector + ":checked", table).length,
                    total = $(selector, table).length;

                if (checked === 0) {
                    $(selector, table).removeClass("gdfar-is-on");
                    bulk.hide();
                } else {
                    $(selector, table).addClass("gdfar-is-on");
                    bulk.show();
                    wp.gdfar.manager.bulk.update(bulk, checked, total);
                }
            }
        },
        init: function () {
            $(document).on("change", ".gdfar-ctrl-topic .gdfar-ctrl-checkbox", wp.gdfar.manager.bulk.topic);
            $(document).on("change", ".gdfar-ctrl-forum .gdfar-ctrl-checkbox", wp.gdfar.manager.bulk.forum);

            $(document).on("click", "#gdfar-modal-edit-submit", wp.gdfar.manager.edit.submit);
            $(document).on("click", "#gdfar-modal-bulk-submit", wp.gdfar.manager.bulk.submit);
            $(document).on("click", ".gdfar-ctrl-wrapper .gdfar-ctrl-edit", wp.gdfar.manager.edit.dialog);
            $(document).on("click", ".gdfar-bulk-control .gdfar-ctrl-bulk", wp.gdfar.manager.bulk.dialog);

            $(document).on("click", ".gdfar-bulk-control a.__all, .gdfar-bulk-control a.__none", wp.gdfar.manager.bulk.select);
        }
    };

    wp.gdfar.manager.init();
})(jQuery, window, document);
