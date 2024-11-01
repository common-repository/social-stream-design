/**
 * Script file for admin side
 *
 * @version 1.0
 * @package WP Social Stream Designer
 */
'use-strict';
jQuery(document).ready(
    function() {
        /*Sticky Sidebar.*/
        (function() {
            "use strict";
            var $, win;
            $ = this.jQuery || window.jQuery;
            win = $(window);
            $.fn.stick_in_parent = function(opts) {
                var doc, elm, enable_bottoming, fn, i, inner_scrolling, len, manual_spacer, offset_top, parent_selector, recalc_every, sticky_class;
                if (opts == null) {
                    opts = {}
                }
                sticky_class = opts.sticky_class, inner_scrolling = opts.inner_scrolling, recalc_every = opts.recalc_every, parent_selector = opts.parent, offset_top = opts.offset_top, manual_spacer = opts.spacer, enable_bottoming = opts.bottoming;
                if (offset_top == null) {
                    offset_top = 0
                }
                if (parent_selector == null) {
                    parent_selector = void 0
                }
                if (inner_scrolling == null) {
                    inner_scrolling = true
                }
                if (sticky_class == null) {
                    sticky_class = "is_stuck"
                }
                doc = $(document);
                if (enable_bottoming == null) {
                    enable_bottoming = true
                }
                fn = function(elm, padding_bottom, parent_top, parent_height, top, height, el_float, detached) {
                    var bottomed, detach, fixed, last_pos, last_scroll_height, offset, parent, recalc, recalc_and_tick, recalc_counter, spacer, tick;
                    if (elm.data("sticky_kit")) {
                        return
                    }
                    elm.data("sticky_kit", true);
                    last_scroll_height = doc.height();
                    parent = elm.parent();
                    if (parent_selector != null) {
                        parent = parent.closest(parent_selector)
                    }
                    if (!parent.length) {
                        throw "failed to find stick parent";
                    }
                    fixed = false;
                    bottomed = false;
                    spacer = manual_spacer != null ? manual_spacer && elm.closest(manual_spacer) : $("<div />");
                    if (spacer) {
                        spacer.css('position', elm.css('position'))
                    }
                    recalc = function() {
                        var border_top, padding_top, restore;
                        if (detached) {
                            return
                        }
                        last_scroll_height = doc.height();
                        border_top = parseInt(parent.css("border-top-width"), 10);
                        padding_top = parseInt(parent.css("padding-top"), 10);
                        padding_bottom = parseInt(parent.css("padding-bottom"), 10);
                        parent_top = parent.offset().top + border_top + padding_top;
                        parent_height = parent.height();
                        if (fixed) {
                            fixed = false;
                            bottomed = false;
                            if (manual_spacer == null) {
                                elm.insertAfter(spacer);
                                spacer.detach();
                            }
                            elm.css({ position: "", top: "", width: "", bottom: "" }).removeClass(sticky_class);
                            restore = true;
                        }
                        top = elm.offset().top - offset_top - 110;
                        height = elm.outerHeight(true);
                        el_float = elm.css("float");
                        if (spacer) {
                            spacer.css({ width: elm.outerWidth(true), height: height, display: elm.css("display"), "vertical-align": elm.css("vertical-align"), "float": el_float });
                        }
                        if (restore) {
                            return tick();
                        }
                    };
                    recalc();
                    if (height === parent_height) {
                        return;
                    }
                    last_pos = void 0;
                    offset = offset_top;
                    recalc_counter = recalc_every;
                    tick = function() {
                        var css, delta, recalced, scroll, will_bottom, win_height;
                        if (detached) {
                            return;
                        }
                        recalced = false;
                        if (recalc_counter != null) {
                            recalc_counter -= 1;
                            if (recalc_counter <= 0) {
                                recalc_counter = recalc_every;
                                recalc();
                                recalced = true;
                            }
                        }
                        if (!recalced && doc.height() !== last_scroll_height) {
                            recalc();
                            recalced = true;
                        }
                        scroll = win.scrollTop();
                        if (last_pos != null) {
                            delta = scroll - last_pos;
                        }
                        last_pos = scroll;
                        if (fixed) {
                            if (enable_bottoming) {
                                will_bottom = scroll + height + offset > parent_height + parent_top;
                                if (bottomed && !will_bottom) {
                                    bottomed = false;
                                    elm.css({ position: "fixed", bottom: "", top: offset }).trigger("sticky_kit:unbottom");
                                }
                            }
                            if (scroll < top) {
                                fixed = false;
                                offset = offset_top;
                                if (manual_spacer == null) {
                                    if (el_float === "left" || el_float === "right") {
                                        elm.insertAfter(spacer);
                                    }
                                    spacer.detach();
                                }
                                css = { position: "", width: "", top: "40px" };
                                elm.css(css).removeClass(sticky_class).trigger("sticky_kit:unstick");
                                jQuery('.ssd-screen').removeClass('ssdst_cls');
                            }
                            if (inner_scrolling) {
                                win_height = win.height();
                                if (height + offset_top > win_height) {
                                    if (!bottomed) {
                                        offset -= delta;
                                        offset = Math.max(win_height - height, offset);
                                        offset = Math.min(offset_top, offset);
                                        if (fixed) {
                                            elm.css({ top: offset + "px" })
                                        }
                                    }
                                }
                            }
                        } else {
                            if (scroll > top) {
                                fixed = true;
                                css = { position: "fixed", top: offset, top: "40px" };
                                css.width = elm.css("box-sizing") === "border-box" ? elm.outerWidth() + "px" : elm.width() + "px";
                                elm.css(css).addClass(sticky_class);
                                jQuery('.ssd-screen').addClass('ssdst_cls');
                                if (manual_spacer == null) {
                                    elm.after(spacer);
                                    if (el_float === "left" || el_float === "right") {
                                        spacer.append(elm);
                                    }
                                }
                                elm.trigger("sticky_kit:stick");
                            }
                        }
                        if (fixed && enable_bottoming) {
                            if (will_bottom == null) {
                                will_bottom = scroll + height + offset > parent_height + parent_top;
                            }
                            if (!bottomed && will_bottom) {
                                bottomed = true;
                                if (parent.css("position") === "static") {
                                    parent.css({ position: "relative" })
                                }
                                return elm.css({ position: "absolute", bottom: padding_bottom, top: "auto" }).trigger("sticky_kit:bottom");
                            }
                        }
                    };
                    recalc_and_tick = function() {
                        recalc();
                        return tick();
                    };
                    detach = function() {
                        detached = true;
                        win.off("touchmove", tick);
                        win.off("scroll", tick);
                        win.off("resize", recalc_and_tick);
                        $(document.body).off("sticky_kit:recalc", recalc_and_tick);
                        elm.off("sticky_kit:detach", detach);
                        elm.removeData("sticky_kit");
                        elm.css({ position: "", bottom: "", top: "40px", width: "" });
                        parent.position("position", "");
                        if (fixed) {
                            if (manual_spacer == null) {
                                if (el_float === "left" || el_float === "right") {
                                    elm.insertAfter(spacer);
                                }
                                spacer.remove();
                            }
                            return elm.removeClass(sticky_class);
                        }
                    };
                    win.on("touchmove", tick);
                    win.on("scroll", tick);
                    win.on("resize", recalc_and_tick);
                    $(document.body).on("sticky_kit:recalc", recalc_and_tick);
                    elm.on("sticky_kit:detach", detach);
                    return setTimeout(tick, 0);
                };
                for (i = 0, len = this.length; i < len; i++) {
                    elm = this[i];
                    fn($(elm));
                }
                return this;
            };

        }).call(this);

        // popup for pro version
        jQuery('.disable_div_div, .disable_li_r .disable_li_r_d, .disable_li, .show_preview, .disable_li input, .disable_li td div.wp-picker-container ').on(
            'click',
            function(e) {
                e.preventDefault();
                jQuery("#ssd-advertisement-popup").dialog({
                    resizable: false,
                    draggable: false,
                    modal: true,
                    height: "auto",
                    width: 'auto',
                    maxWidth: '100%',
                    dialogClass: 'ssd-advertisement-ui-dialog',
                    buttons: [{
                        text: 'x',
                        "class": 'ssd-btn ssd-btn-gray',
                        click: function() {
                            jQuery(this).dialog("close");
                        }
                    }],
                    open: function(event, ui) {
                        jQuery(this).parent().children('.ui-dialog-titlebar').hide();
                    },
                    hide: {
                        effect: "fadeOut",
                        duration: 500
                    },
                    close: function(event, ui) {
                        jQuery("#ssd-advertisement-popup").dialog('close');
                    },
                });
            }
        );

        (function($) {
            "use strict";
            $(".ssds-mn-set").stick_in_parent();
            $(document.body).on("click", ".detach", function(e) { $(".ssds-mn-set").trigger("sticky_kit:detach") });
            ssd_grid_style_change();
            $(document).on(
                'click',
                '.ssd-upload-image-button',
                function(event) {
                    event.preventDefault();
                    var frame;
                    var $el = $(this);
                    var _parentTD = $el.closest('div');
                    if (frame) {
                        frame.open();
                        return
                    }
                    frame = wp.media({ title: $el.data('choose'), button: { text: $el.data('update'), close: false }, multiple: false, library: { type: 'image' } });

                    // When an image is selected, run a callback.
                    frame.on(
                        'select',
                        function() {
                            var attachment = frame.state().get('selection').first();
                            frame.close(attachment);
                            _parentTD.find('span.ssd-default-image-holder').empty().hide().append('<img src="' + attachment.attributes.url + '">').slideDown('fast');
                            _parentTD.find('#ssd_default_image_id').val(attachment.attributes.id);
                            _parentTD.find('#ssd_default_image_src').val(attachment.attributes.url);
                            $el.removeClass('ssd-upload-image-button');
                            $el.addClass('ssd-remove-image-button');
                            $el.val('');
                            $el.val('Remove Image');
                        }
                    );

                    frame.open();
                }
            );
            $(document).on(
                'click',
                '.ssd-remove-image-button',
                function(event) {
                    event.preventDefault();
                    var $el = $(this);
                    $('.ssd-default-image-holder > img').slideDown().remove();
                    $('#ssd_default_image_id').val('');
                    $('#ssd_default_image_src').val('');
                    $el.addClass('ssd-upload-image-button');
                    $el.removeClass('ssd-remove-image-button');
                    $el.val('');
                    $el.val('Upload Image');
                }
            );
            $(document).on(
                'change',
                '.ssd_load_more_layout',
                function() {
                    var ssd_template_no = $(this).val();
                    $('.ssds-cntr .ssd_load_more_btn button').removeClass('template-1');
                    $('.ssds-cntr .ssd_load_more_btn button').removeClass('template-2');
                    $('.ssds-cntr .ssd_load_more_btn button').removeClass('template-3');

                    $('.ssds-cntr .ssd_load_more_btn button').addClass(ssd_template_no);

                }
            );

            if ($('input[name="ssd[ssd_design_layout]"]').val() == 'layout-2') {
                $('.ssd-author-margin-bottom').hide();
                $('.ssd-media-margin-bottom').hide();
            } else {
                $('.ssd-author-margin-bottom').show();
                $('.ssd-media-margin-bottom').show();
            }
            /**
             * Admin Restore Default js
             */
            $('#ssd_reset_layout').click(
                function() {
                    if (confirm(ssdadminObj.reset_data)) {
                        var id = $(this).attr('data-id');
                        var data = {
                            action: 'ssd_reset_layout_settings',
                            layout_id: id,
                            ssd_nonce: ssd_ajax_var.ssd_nonce
                        };
                        $.post(
                            ajaxurl,
                            data,
                            function(response) {
                                if (response == 'success') {
                                    $("html, body").animate({ scrollTop: 0 }, 1000);
                                    window.location.href = window.location.href + '&reset=1';
                                }
                            }
                        );
                    } else {
                        return false;
                    }
                }
            );
            $('.ssd_feed_setting_btn').click(
                function() {
                    var ssd_feed_name = $('input[name="feed_stream[feed_name]"]').val();
                    var ssd_feed_limit = $('input[name="feed_stream[feed_limit]"]').val();
                    var ssd_feed_refresh_time = $('input[name="feed_stream[refresh_feed_on_number]"]').val();
                    var ssd_url_pattern = /^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/;
                    var required = true;
                    $('.required').each(
                        function() {
                            if ($(this).val() == '') {
                                required = false;
                            }
                        }
                    );

                    $('.ssd_input_url').each(
                        function() {
                            if ($(this).val() == '' || !ssd_url_pattern.test($(this).val())) {
                                required = false;
                            }
                        }
                    );

                    if (ssd_feed_name != '' && (ssd_feed_limit >= 0 && ssd_feed_limit <= 500) && ssd_feed_limit != '' && (ssd_feed_refresh_time > 0) && required == true) {
                        $('.ssd-loader-wrapper').css('display', 'flex');
                    }
                }
            );

            $('.ssd_layouts .ssd_stream-setting-handle li').click(
                function() {
                    if ($(this).hasClass('ssd_click_disable')) {
                        ssd_click_disable();
                    } else {
                        var data_href = $(this).attr('data-show');
                        if (window.localStorage) {
                            localStorage.setItem("ssd_lastcontenttab", data_href);
                        }
                        $('.ssd_layouts .ssd_stream-setting-handle li').removeClass('ssd_stream-active-tab');
                        $(this).addClass('ssd_stream-active-tab');
                        $('.ssd_layouts .ssds-set-box').removeClass('ssd_active_tab');
                        $('.ssd_layouts #' + data_href).addClass('ssd_active_tab');
                        $('.ssd_layouts .ssds-set-box').hide();
                        $('.ssd_layouts #' + data_href).show();
                    }

                }
            );
            var $ssd_content_tab = ['ssd_generalsettings', 'selectcardsettings', 'layoutsettings', 'cardsettings', 'mediasettings', 'sharelabelsettings', 'titlesettings', 'contentsettings', 'authorsettings', 'ssd_countsettings', 'paginationsettings', 'ssd_popupsettings'];

            var new_layout = $('#ssd_edit_id').val();
            if (new_layout == '' || window.localStorage.getItem("ssd_lastcontenttab") == null ||
                ($.inArray(window.localStorage.getItem("ssd_lastcontenttab"), $ssd_content_tab) == -1)) {
                window.localStorage.setItem("ssd_lastcontenttab", 'ssd_generalsettings');
                $('.ssd_layouts .ssd_stream-setting-handle li').removeClass('ssd_stream-active-tab');
                $('.ssd_layouts .ssd_generalsettings').addClass('ssd_stream-active-tab');
                $('.ssd_layouts .ssds-set-box').hide();
                $('.ssd_layouts #ssd_generalsettings').show();
                $('.ssd_layouts .ssds-set-box').removeClass('ssd_active_tab');
                $('.ssd_layouts #ssd_generalsettings').addClass('ssd_active_tab');
            } else {
                if (new_layout != '') {
                    $('.ssd_layouts .ssd_stream-setting-handle li').removeClass('ssd_stream-active-tab');
                    $('.ssd_layouts .' + window.localStorage.getItem("ssd_lastcontenttab")).addClass('ssd_stream-active-tab');
                    $('.ssd_layouts .ssds-set-box').hide();
                    $('.ssd_layouts #' + window.localStorage.getItem("ssd_lastcontenttab")).show();
                    $('.ssd_layouts .ssds-set-box').removeClass('ssd_active_tab');
                    $('.ssd_layouts #' + window.localStorage.getItem("ssd_lastcontenttab")).addClass('ssd_active_tab');
                }
            }
            $('.ssd_feeds .ssds-set-box').hide();
            $('.ssd_feeds .ssds-set-box.ssd_active_tab').show();
            $('.ssd_feeds .ssd_stream-setting-handle li').click(
                function() {
                    var data_href = $(this).attr('data-show');
                    if (window.localStorage) {
                        localStorage.setItem("ssd_feeds_lastcontenttab", data_href);
                    }
                    $('.ssd_feeds .ssd_stream-setting-handle li').removeClass('ssd_stream-active-tab');
                    $(this).addClass('ssd_stream-active-tab');
                    $('.ssd_feeds .ssds-set-box').removeClass('ssd_active_tab');
                    $('.ssd_feeds #' + data_href).addClass('ssd_active_tab');
                    $('.ssd_feeds .ssds-set-box').hide();
                    $('.ssd_feeds #' + data_href).show();
                }
            );

            $(".ssds-set-box #sortable").sortable({
                containment: "parent"
            });
            $('.ssd_cpa-color-picker').wpColorPicker();
            $(".ssds-set-box .ssd_stream_select").select2();
            $(".ssdf-set-bx .ssd_feed_select").select2();

            $("#ssdsstmly").on(
                'change',
                function() {
                    ssd_grid_style_change();
                }
            );

            $("#ssd_social_stream_grid_style").on(
                'change',
                function() {
                    ssd_grid_style_change();
                }
            );

            $('.ssd_feed_status_radio').change(
                function() {
                    var ssd_feed_id = $(this).data('id');
                    var ssd_feed_status = $('.feed_status_' + ssd_feed_id + ':checked').val();
                    var ajaxData = {
                        'action': 'ssd_update_feed_status',
                        'feed_id': ssd_feed_id,
                        'feed_status': ssd_feed_status,
                        'ssd_nonce': ssd_ajax_var.ssd_nonce
                    }
                    $.post(ajaxurl, ajaxData, function() {});
                }
            );
            $('.ssd_feed_display_status_radio').change(
                function() {
                    var ssd_feed_id = $(this).data('id');
                    var ssd_feed_status_live = $('.feed_display_status_' + ssd_feed_id + ':checked').val();
                    var ajaxData = {
                        'action': 'ssd_update_feed_live_status',
                        'feed_id': ssd_feed_id,
                        'feed_status_live': ssd_feed_status_live,
                        'ssd_nonce': ssd_ajax_var.ssd_nonce
                    }
                    $.post(ajaxurl, ajaxData, function() {});
                }
            );
            $('.ssddimg').change(
                function() {
                    if ($(this).val() == 1) {
                        $('.ssd_image_layout_tr').show();
                    } else {
                        $('.ssd_image_layout_tr').hide();
                    }
                }
            );
            var layout = $('#ssd_item_data .ssd_item-content.selected').attr('for');
            if (layout == 'ssd_design_layout_4') {
                $('.ssd-media-sortable').attr('data-order', '');
                $('.ssd-media-sortable').hide();
            }

            $('.ssd_item-content').click(
                function(event) {
                    event.preventDefault();
                    if (!$(this).hasClass('disable_div_div')) {
                        $('.ssd_item-template .ssd_item-content').removeClass('selected');
                        $(this).addClass('selected');
                        var layout = $(this).attr('for');
                        $("#" + layout).prop("checked", true);

                        var ajaxData = {
                            'action': 'ssd_update_drag_drop_builder',
                            'layout': layout,
                            'ssd_nonce': ssd_ajax_var.ssd_nonce
                        }
                        $.post(
                            ajaxurl,
                            ajaxData,
                            function(response) {
                                $('.ssd-card ul#sortable').html(response);
                            }
                        );
                        ssd_layout_selector();
                        ssd_grid_style_change();
                        if($('.ssd_item-template[data-id="2"] > .selected').length > 0){
                            $('#ssd_icon_alignment').select2().val('center');
                            $('#ssd_icon_position').select2().val('bottom');
                        }
                    }
                }
            );

            $('body').on('click', '.ssdily_pst span', function() { $('.ssdily_pst span').removeClass('selected');
                $(this).addClass('selected') });

            var selected_val = $("#ssdssstrm").val();
            show_hide_tr(selected_val);
            ssd_get_feed_type();
            ssd_stream_type();
            ssd_display_corner_icon();

            $(".ssdstm-ly-hdr #submit").on(
                'click',
                function() {
                    if ($('#ssdssstrm').val() == '' || $('#ssdssstrm').val() == null) {
                        $(this).addClass('ssd_error_input');
                        alert(ssdadminObj.select_social_stream);
                    }

                    var error = 'N';
                    $('.ssd_number_field').each(
                        function() {
                            var x = $(this).val();
                            if (!$.isNumeric(x)) {
                                $(this).addClass('ssd_error_input');
                                error = 'Y';
                            }
                        }
                    );

                    if (error == 'Y') {
                        alert(ssdadminObj.validation_error);
                        return false;
                    }
                }
            );

            $("#ssdssstrm").on(
                'change',
                function() {
                    var selected_val = $("#ssdssstrm").val();
                    show_hide_tr(selected_val);
                }
            );

            $('#ssd_social_share_type').on(
                'change',
                function() {
                    ssd_stream_type();
                }
            );

            $(".ssd_display_corner_icon").on(
                'click',
                function() {
                    var ssd_social_share_type = $('#ssd_social_share_type').val();
                    if (ssd_social_share_type != 'text') {
                        ssd_display_corner_icon();
                    }
                    if ($('#ssd_display_corner_icon_no').prop('checked')) {
                        ssd_corner_icon_false();
                        $('.ssd_show_sticky_icon_tr').show();
                    } else {
                        ssd_corner_icon_true();
                    }
                }
            );

            $(".ssd_display_sticky").on(
                'click',
                function() {
                    ssd_sticky_option();
                }
            );

            $("#ssd_display_sticky_on").on(
                'change',
                function() {
                    ssd_display_sticky_on();
                }
            );

            $(".ssddimg").on(
                'click',
                function() {
                    ssd_display_image();
                }
            );

            ssd_pagination_layout();
            $('#ssd_pagination_type').on(
                'change',
                function() {
                    ssd_pagination_layout();
                }
            );
            $('#ssdsstmly').on(
                'change',
                function() {
                    ssd_pagination_layout();
                }
            );
            $('.ssd_display_title').on(
                'change',
                function() {
                    if ($('#ssd_display_title_yes').prop('checked')) {
                        $('.ssd_title_settings_tr').show();
                        if ($('#ssd_display_title_link_yes').prop('checked')) {
                            $('.ssd_title_hover_settings_tr').show();
                        } else {
                            $('.ssd_title_hover_settings_tr').hide();
                        }
                    } else {
                        $('.ssd_title_settings_tr').hide();
                    }
                }
            );

            $('.ssd_display_title_link').on(
                'change',
                function() {

                    if ($('#ssd_display_title_link_yes').prop('checked')) {
                        $('.ssd_title_hover_settings_tr').show();
                    } else {
                        $('.ssd_title_hover_settings_tr').hide();
                    }

                }
            );
            if ($('#ssd_display_title_yes').prop('checked')) {
                $('.ssd_title_settings_tr').show();
                if ($('#ssd_display_title_link_yes').prop('checked')) {
                    $('.ssd_title_hover_settings_tr').show();
                } else {
                    $('.ssd_title_hover_settings_tr').hide();
                }
            } else {
                $('.ssd_title_settings_tr').hide();
            }

            $('.ssd_display_content').on(
                'change',
                function() {
                    scstr = $('.ssd_content_settings_tr');
                    sppst = $('.ssd_popupsettings');
                    if ($('#ssd_display_content_yes').prop('checked')) {
                        scstr.show();
                        sppst.css('pointer-events', '');
                        sppst.css('background', '');
                    } else {
                        scstr.hide();
                        sppst.css('pointer-events', 'none');
                        sppst.css('background', 'rgb(174, 171, 171) none repeat scroll 0% 0%');
                    }
                }
            );
            var scstr = $('.ssd_content_settings_tr');
            if ($('#ssd_display_content_yes').prop('checked')) {
                scstr.show()
            } else {
                scstr.hide()
            }

            $('.ssd_display_author_box').on(
                'change',
                function() {
                    satrw = $('.ssd_author_row');
                    if ($('#ssd_display_author_box_yes').prop('checked')) {
                        satrw.show()
                    } else {
                        satrw.hide()
                    }
                }
            );
            var satrw = $('.ssd_author_row');
            if ($('#ssd_display_author_box_yes').prop('checked')) {
                satrw.show()
            } else {
                satrw.hide()
            }

            $('.ssd_display_social_icon').on(
                'change',
                function() {
                    if ($('#ssd_display_social_icon_yes').prop('checked')) {
                        $('.ssd_social_row').show();
                        ssd_stream_type();
                    } else {
                        $('.ssd_social_row').hide();
                    }
                }
            );
            if ($('#ssd_display_social_icon_yes').prop('checked')) {
                $('.ssd_social_row').show();
                ssd_stream_type();
            } else {
                $('.ssd_social_row').hide();
            }
            var spsht = $('.ssd-popup-show-hide-tr');
            spsht.show();
            var sditr = $('.ssd-default-image-tr');
            var sfwmt = $('.ssd-feeds-without-media-tr');
            if ($('#ssd_display_feed_without_media_no').prop('checked')) {
                $('.ssd_label_fit_content').parent().show();
                sfwmt.show();
                $('.ssd-feeds-without-media-tr.ssd-default-image-tr').show();
            } else {
                sfwmt.hide();
                $('.ssd-feeds-without-media-tr.ssd-default-image-tr').hide();
                $('.ssd_label_fit_content').parent().hide();
                if ($('#ssd_display_default_image_no').prop('checked')) {
                    sditr.hide()
                } else {
                    sditr.show()
                }
            }
            $('input[name="ssd[ssd_display_feed_without_media]"]').on("change", function() {
                if ($(this).val() == '1') {
                    $('.ssd-default-image-tr').hide();
                } else {
                    $('ssd-default-image-tr').show();
                }
            });

            $('.ssd_display_feed_without_media').on(
                'click',
                function() {
                    sfwmt = $('.ssd-feeds-without-media-tr');
                    sditr = $('.ssd-default-image-tr');
                    if ($('#ssd_display_feed_without_media_no').prop('checked')) {
                        $('.ssd_label_fit_content').parent().show();
                        sfwmt.show();
                        $('.ssd-default-image-tr').show();
            if ($( '#ssd_display_default_image_no' ).prop( 'checked' )) {
                        sditr.hide()} else {
                        sditr.show()}
                    }
                    
                    else {		
                        sfwmt.hide();
                        $('.ssd-default-image-tr').hide();
                        $('.ssd_label_fit_content').parent().hide();
                        if ($('#ssd_display_default_image_no').prop('checked')) {
                            sditr.hide()
                        } else {
                            sditr.show()
                        }
                    }
                }
            );

            $('.display_filter').on(
                'change',
                function() {
                    var value = $(this).val();
                    if (value == 1) {
                        $('.ssd_display_search_tr').hide();
                    } else {
                        $('.ssd_display_search_tr').show();
                    }
                }
            );
            if ($('#display_filter_yes').prop('checked')) {
                $('.ssd_display_search_tr').hide();
            } else {
                if ($('#social_stream_layout').val() == 'listing') {
                    $('.ssd_display_search_tr').show();
                }

            }
            // $('.ssd_display_sticky').on(
            //     'change',
            //     function() {
            //         var sitpt = $('.ssd_icon_text_position_tr');
            //         if ($(this).prop('checked') && $(this).val() == '1') {
            //             sitpt.show();
            //         } else {
            //             sitpt.hide();
            //         }
            //     }
            // );

            $('.ssd_display_content').on(
                'change',
                function() {
                    var sitpt = $('.ssd_icon_text_position_tr');
                    if ($(this).prop('checked') && $(this).val() == '1') {
                        sitpt.show();
                    } else {
                        sitpt.hide();
                    }
                }
            );
            var sppst = $('.ssd_popupsettings');
            if ($('.ssd_display_content').prop('checked')) {
                sppst.removeAttr('disable');
                sppst.css('pointer-events', '');
                sppst.css('background', '');
            } else {
                sppst.attr('disable', '');
                sppst.css('pointer-events', 'none');
                sppst.css('background', '#aeabab');
            }

       if ($('#ssd_display_default_image_no').prop('checked')) {
                $('.ssd-default-image-tr').hide();
            } else {
                $('.ssd-default-image-tr').show();
            }
            $('.ssd_display_default_image').on('click', function() {
                if ($('#ssd_display_default_image_no').prop('checked')) {
                    $('.ssd-default-image-tr').hide();
                } else {
                    $('.ssd-default-image-tr').show();
                }
            });

            if ($('#ssd_display_feed_without_media_yes').prop('checked')) {
                $('.ssd-default-image-tr').hide();

            }
            $('#ssd_slider_direction').on('change', function() { ssd_grid_style_change() });
            var sobtr = $('.ssd_order_by_tr');
            if ($('#ssd_social_stream_order_by').val() == 'rand') {
                sobtr.hide();
            } else {
                sobtr.show();
            }
            $('#ssd_social_stream_order_by').on(
                'change',
                function() {
                    var sobtr = $('.ssd_order_by_tr');
                    if ($('#ssd_social_stream_order_by').val() == 'rand') {
                        sobtr.hide();
                    } else {
                        sobtr.show();
                    }
                }
            );
            $('#ssd_social_stream_grid_style').on(
                'change',
                function() {
                    var ssd_value = $(this).val();
                    var sdc = $('.ssd_display_columns');
                    if (ssd_value == 'masonry') {
                        sdc.show()
                    } else {
                        sdc.hide()
                    }
                }
            );
            $('.ssd_display_post_meta').on(
                'change',
                function() {
                    var ssd_value = $(this).val();
                    var scst = $('.ssd_countsettings');
                    var sdsit = $('.ssd_display_share_icon_tr');
                    if (ssd_value == 0) {
                        scst.addClass('ssd_click_disable');
                        sdsit.hide();
                        ssd_click_disable()
                    } else {
                        sdsit.show();
                        scst.removeClass('ssd_click_disable')
                    }
                }
            );
            var scst = $('.ssd_countsettings');
            var sdsit = $('.ssd_display_share_icon_tr');
            if ($('#ssd_display_meta_no').prop('checked')) {
                sdsit.hide();
                scst.addClass('ssd_click_disable');
                ssd_click_disable()
            } else {
                sdsit.show();
                scst.removeClass('ssd_click_disable')
            }

        }(jQuery));

    }
);

function ssd_pagination_layout() {
    var sssl = $('#ssdsstmly').val();
    var sspt = $('#ssd_pagination_type').val();
    a = $('.ssd_post_per_page_tr');
    b = $('.ssd_pagination_template_preview');
    c = $('.ssd_pagination_template_tr');
    d = $('.ssd_load_more_template_tr');
    e = $('.ssd_load_more_template_preview');
    f = $('#ssd_display_filter_no');
    g = $('.ssd_load_more_effect_tr');
    if ((sssl == 'listing') && sspt == 'no_pagination') {
        a.hide()
    } else {
        a.show()
    }
    if ((sssl == 'listing') && f.prop('checked')) {
        b.show();
        c.show()
    } else {
        b.hide();
        c.hide()
    }
    if ((sssl == 'listing') && sspt == 'load_more_btn' && f.prop('checked')) {
        d.show();
        e.show()
    } else {
        d.hide();
        e.hide()
    }
    if ((sssl == 'listing') && (sspt == 'load_more_btn') && f.prop('checked')) {
        g.show()
    } else {
        g.hide()
    }
    if (sspt == 'no_pagination') {
        c.hide();
        d.hide();
        b.hide();
        e.hide()
    } else if (sspt == 'load_more_btn') {
        d.show();
        e.show()
    }
}

function ssd_layout_selector() {
    $ = jQuery;
    b = $('.ssd-author-sortable');
    c = $('.ssd-author-margin-bottom');
    d = $('.ssd-media-margin-bottom');
    e = $('.ssd-media-sortable');
    f = $('.ssd-social-share-sortable');
    g = $('#ssd_social_share_type');
    h = $('input[name="ssd[ssd_title_color]');
    i = $('input[name="ssd[ssd_title_hover_color]');
    j = $('input[name="ssd[ssd_content_color]');
    k = $('input[name="ssd[ssd_count_meta_color]');
    l = $('input[name="ssd[ssd_author_title_color]');
    m = $('input[name="ssd[ssd_author_title_hover_color]');
    n = $('input[name="ssd[ssd_author_meta_color]');
    o = $("#ssd_display_sticky_yes");
    p = $("#ssd_display_corner_icon_no");
    s = $('#ssd_display_sticky_on');
    t = $("#ssd_display_sticky_no");
    u = $('.ssd_icon_text_position_tr');
    v = $('.ssd-layout-4');
    w = $('.ssd-box-shadow');
    x = $("#ssdsstmly");
    y = $('.ssd_display_grid_style');
    var layout = $('input[name="ssd[ssd_design_layout]"]:checked').val();
    if (layout == 'layout-1') {
        b.attr('data-order', 'author');
        b.show();
        c.show();
        d.show();
        e.attr('data-order', 'media');
        e.show();
        f.attr('data-order', 'social-share');
        f.show();
        g.select2().val('text');
        ssd_common_style();
        h.val('#333333');
        i.val('#f93d66');
        j.val('#333333');
        k.val('#666666');
        l.val('#f93d66');
        m.val('#333333');
        n.val('#666666');
        o.prop("checked", true);
        p.prop("checked", true);
        var ajaxData = { 'action': 'ssd_update_sticky_on', 'author': 'false', 'ssd_nonce': ssd_ajax_var.ssd_nonce }
        $.post(ajaxurl, ajaxData, function(response) { s.html();
            s.html(response);
            s.select2() });
    } else if (layout == 'layout-2') {
        f.attr('data-order', '');
        f.hide();
        e.attr('data-order', 'media');
        e.show();
        b.attr('data-order', '');
        b.hide();
        ssd_common_style();
        h.val('#333333');
        i.val('#f93d66');
        j.val('#333333');
        k.val('#666666');
        l.val('#f93d66');
        m.val('#333333');
        n.val('#666666');
        $('.ssd_text_border_radius_tr').hide();
        g.select2().val('icon');
        o.prop("checked", true);
        p.prop("checked", true);
        c.hide();
        d.hide();
        $('#ssd_icon_alignment').select2().val('center');
        if (layout == 'layout-2') {
            s.val('author');
            var ajaxData = { 'action': 'ssd_update_sticky_on', 'author': 'true', 'ssd_nonce': ssd_ajax_var.ssd_nonce }
            $.post(ajaxurl, ajaxData, function(response) { s.html();
                s.html(response);
                s.select2() });
            s.val('author');
            if (t.prop("checked")) {
                u.hide();
            } else {
                if (s.val() == 'media') {
                    u.show()
                } else {
                    u.hide()
                }
            }
            $('.ssd_icon_text_alignment_tr').hide();
        } else {
            $("#ssd_display_share_with_no").prop("checked", true);
            $(".ssd_image_layout_1").prop("checked", true);
            $("#ssd_icon_alignment").val('right');
            $('input[name="ssd[ssd_author_border_radius]"]').val('100');
            $('#ssd_author_border_radius_type').val('%');
            $("#ssddimg_yes").prop("checked", true);
            $("#ssd_user_follower_count_no").prop("checked", true);
            $("#ssd_user_friend_count_no").prop("checked", true);
            $("#ssd_retweet_count_no").prop("checked", true);
            $("#ssd_reply_count_no").prop("checked", true);
            $("#ssd_favorite_count_no").prop("checked", true);
            $("#ssd_view_count_no").prop("checked", true);
            $("#ssd_like_count_no").prop("checked", true);
            $("#ssd_dislike_count_no").prop("checked", true);
            $("#ssd_comment_count_no").prop("checked", true);
        }
    }
    v.hide();
    w.show();
    $('.ssd-bg-color').show();
    if (x.val() == 'listing') {
        y.hide()
    } else {
        y.show()
    }
}

function ssd_get_feed_type() {
    $ = jQuery;
    var ssd_feed = $('#ssd_feed').val();
    var sftf = $('select[name="feed_stream[feed_type_facebook]"]').val();
    var sftt = $('select[name="feed_stream[feed_type_twitter]"]').val();
    var sfti = $('select[name="feed_stream[feed_type_instagram]"]').val();
    var sfttk = $('select[name="feed_stream[feed_type_tiktok]"]').val();
    var ssdn = 'ssd_' + ssd_feed;
    a = $('.ssd_feed_type_location');
    b = $('.ssd_feed_type_tag');
    c = $('.ssd_feed_type_search');
    d = $('.ssd_feed_type_channel');
    e = $('.ssd_feed_type_playlist');
    h = $('.ssd_feed_type_posts');
    i = $('.ssd_feed_type_comments');
    j = $('.ssd_feed_type_user');
    k = $('.ssd_feed_type_user_listname');
    l = $('.ssd_user_feed_text');
    m1 = $('.ssd_feed_type_user_list');
    m = $('.ssd_user_list_text');
    n = $('.ssd_user_like_text');
    o = $('.ssd_user_listname_text');
    p = $('.ssd_feed_type_fb_page');
    q = $('.ssd_feed_type_fb_album');
    s = $('.ssd_feed_type_flickr_tag');
    t = $('.ssd_feed_type_insta_user');
    u = $('.ssd_inner_field');
    j1 = $('.ssd_feed_type_user input');
    x = $('.ssd_feed_type_tiktok_hashtag');
    y = $('.ssd_feed_type_tiktok_user');
    x1 = $('.ssd_feed_type_tiktok_hashtag input');
    y1 = $('.ssd_feed_type_tiktok_user input');
    $('.ssd_feed_type').hide();
    $('.' + ssdn).show();
    a.hide();
    b.hide();
    c.hide();
    d.hide();
    e.hide();
    h.hide();
    i.hide();
    j.hide();
    k.hide();
    l.hide();
    m.hide();
    n.hide();
    o.hide();
    p.hide();
    q.hide();
    s.hide();
    t.hide();
    u.hide();
    m1.hide();
    x.hide();
    y.hide();
    $('.required').each(function() { $(this).removeAttr('required', '');
        $(this).removeClass('required') });
    if (ssd_feed == 'facebook-stream') {
        if (sftf == 'page') {
            p.show();
            p1 = $('.ssd_feed_type_fb_page input');
            p1.addClass('required');
            p1.attr('required', "")
        }
        if (sftf == 'album') {
            q.show();
            q1 = $('.ssd_feed_type_fb_album input');
            q1.addClass('required');
            q1.attr('required', "")
        }
    }
    if (ssd_feed == 'instagram-stream') {
        if (sfti == 'location') {
            a.show();
            a1 = $('.ssd_feed_type_location input');
            a1.addClass('required');
            a1.attr('required', "")
        }
        if (sfti == 'hashtag') {
            b.show();
            b1 = $('.ssd_feed_type_tag input');
            b1.addClass('required');
            b1.attr('required', "")
        }
        if (sfti == 'user_feed' || sfti == 'users_like') {
            t.show();
            t1 = $('.ssd_feed_type_insta_user input');
            t1.addClass('required');
            t1.attr('required', "")
        }
    }
    if (ssd_feed == 'twitter-stream') {
        if (sftt == 'tweets_by_search') {
            c.show();
            c1 = $('.ssd_feed_type_search input');
            c1.addClass('required');
            c1.attr('required', "")
        }
        if (sftt == 'user_feed') {
            j.show();
            l.show();
            j1.addClass('required');
            j1.attr('required', "")
        }
        if (sftt == 'user_list') {
            j2 = $('.ssd_feed_type_user_list input');
            j.show();
            m.show();
            k.show();
            o.show();
            j1.addClass('required');
            j1.attr('required', "");
            j2.addClass('required');
            j2.attr('required', "");
            m1.show();
        }
        if (sftt == 'users_like') {
            j.show();
            n.show();
            j1.addClass('required');
            j1.attr('required', "")
        }
        if (sftt == 'home_timeline') {
            j.show();
            n.show();
            j1.addClass('required');
            j1.attr('required', "")
        }
    }
    if (ssd_feed == 'tiktok-stream') {
        if (sfttk == 'hashtag') {
            x.show();
            x1.addClass('required');
            x1.attr('required', "")
        }
        if (sfttk == 'username') {
            y.show();
            y1.addClass('required');
            y1.attr('required', "")
        }
        i
    }
    if (ssd_feed == 'pinterest-stream') {
        pr = $('.ssd_feed_type_pinterest_boardname input');
        ps = $('.ssd_feed_type_pinterest_userid input');
        $('.ssd_feed_type_pinterest_userid').show();
        $('.ssd_feed_type_pinterest_boardname').show();
        ps.addClass('required');
        ps.attr('required', "");
        pr.addClass('required');
        pr.attr('required', "");
    }
}

function ssd_save_order() {
    $ = jQuery;
    var order = '';
    var shortcode_id = $('.ssds-set-box #sortable').attr('data-shortcode-id');
    $('.ssds-set-box #sortable').find('li').each(function() { order = order + $(this).attr('data-order') + ',' });
    var data = { action: 'ssd_set_order', order: order, shortcode_id: shortcode_id, 'ssd_nonce': ssd_ajax_var.ssd_nonce };
    $.post(ajaxurl, data, function(response) { return true });
}

function ssd_delele() {
    if (!confirm(ssdadminObj.delete_confirm)) {
        return false
    };
    return true
}

function ssd_stream_type() {
    $ = jQuery;
    var type = $("#ssd_social_share_type").val();
    a = $('.ssd_show_corner_tr');
    b = $('.ssd_text_border_radius_tr');
    c = $("#ssd_display_corner_icon_no");
    d = $("#ssd_display_sticky_no");
    e = $('.ssd_icon_text_alignment_tr');
    f = $('#ssd_display_sticky_on');
    g = $("#ssd_display_corner_icon_yes");
    h = $('.ssd_icon_text_position_tr');
    i = $('.ssd_show_image_for_icon_tr');
    j = $('.ssd_show_sticky_icon_tr');
    if (type == 'icon') {
        a.show();
        b.hide();
        if (c.prop("checked") || d.prop("checked")) {
            e.hide()
        } else {
            e.show()
        }
        e.hide();
        if ((f.val() == 'media') || g.prop("checked")) {
            h.show()
        }
        i.show();
        $('input[name="ssd[ssd_text_border_radius]"]').val('0');
        ssd_display_image();

    } else if (type == 'icon_text') {
        a.hide();
        j.show();
        b.show();
        e.hide();
        if (d.prop("checked")) {
            h.hide()
        } else {
            if (f.val() == 'media') {
                h.show()
            } else {
                h.hide()
            }
        }
        i.hide();
        c.prop("checked", true);
        ssd_sticky_option();
        ssd_display_image();
    } else if (type == 'text') {
        i.hide();
        $('.ssd_image_layout_tr').hide();
        a.hide();
        $('.ssd_icon_border_radius_tr').hide();
        e.show();
        if (d.prop("checked")) {
            h.hide()
        } else {
            if (f.val() == 'media') {
                h.show()
            } else {
                h.hide()
            }
        }
        $('.ssd_icon_text_color_tr').show();
        $('.ssd_icon_text_bg_color_tr').show();
        b.show();
        j.show();
        $("#ssddimg_no").prop("checked", true);
        c.prop("checked", true);
        $('input[name="ssd[ssd_icon_border_radius]"]').val('0');
        ssd_sticky_option();
    }
}

function show_hide_tr(selected_val) {
    $ = jQuery;
    a = $("tr.ssd_retweet_count_tr");
    b = $("tr.ssd_reply_count_tr");
    c = $("tr.ssd_view_count_tr");
    d = $("tr.ssd_favorite_count_tr");
    e = $("tr.ssd_like_count_tr");
    f = $("tr.ssd_pin_count_tr");
    g = $("tr.ssd_dislike_count_tr");
    h = $("tr.ssd_comment_count_tr");
    j = $("tr.ssd_share_count_tr");
    $("tr.ssd_user_follower_count_tr").hide();
    $("tr.ssd_user_friend_count_tr").hide();
    a.hide();
    b.hide();
    c.hide();
    d.hide();
    e.hide();
    f.hide();
    g.hide();
    h.hide();
    j.hide();
    if (selected_val != '' && selected_val != null) {
        var ssd_selected_val_length = selected_val.length;
        for (var i = 0; i < ssd_selected_val_length; i++) {
            var ft = $('#ssdssstrm option[value="' + selected_val[i] + '"]').attr('data-feed_type');
            if (ft == 'twitter-stream') {
                a.show();
                b.show();
                d.show()
            } else if (ft == 'facebook-stream') {
                e.show();
                h.show()
            } else if (ft == 'pinterest-stream') {
                e.hide();
                h.hide();
                f.show()
            } else if (ft == 'instagram-stream') {
                e.show();
                h.show()
            } else if (ft == 'tiktok-stream') {
                d.show();
                h.show();
                j.show();
                c.show();
            }
        }
    }
}

function ssd_corner_icon_true() {
    var data = { action: 'ssd_corner_icon', value: 'true', ssd_nonce: ssd_ajax_var.ssd_nonce };
    $.post(ajaxurl, data, function(response) { $('#ssd_icon_alignment').html(response);
        $('#ssd_icon_alignment').select2(); return true });
}

function ssd_corner_icon_false() {
    $ = jQuery;
    var data = { action: 'ssd_corner_icon', value: 'false', ssd_nonce: ssd_ajax_var.ssd_nonce };
    $.post(ajaxurl, data, function(response) { $('#ssd_icon_alignment').html(response);
        $('#ssd_icon_alignment').select2(); return true });
}

function ssd_sticky_option() {
    $ = jQuery;
    a = $('.ssd_show_sticky_icon_on_tr');
    b = $('.ssd-social-share-sortable');
    c = $('#ssd_social_share_type');
    d = $('.ssd_show_corner_tr');
    if ($("#ssd_display_sticky_yes").prop("checked")) {
        a.show();
        b.attr('data-order', '');
        d.hide();
        ssd_display_sticky_on();
    } else {
        if ((c.val() == 'icon')) {
            $('.ssd_show_image_for_icon_tr').show();
            if ($("#ssddimg_no").prop("checked")) {
                d.show()
            }
        }
        b.show();
        b.attr('data-order', 'social-share');
        if ((c.val() != 'icon') && ($("#ssd_display_corner_icon_no").prop("checked"))) {
            $('.ssd_icon_text_position_tr').hide()
        }
        a.hide();
    }
}

function ssd_display_corner_icon() {
    $ = jQuery;
    a = $('#ssd_social_share_type');
    b = $('.ssd-social-share-sortable');
    c = $('.ssd_icon_border_radius_tr');
    d = $('.ssd_icon_text_alignment_tr');
    e = $('.ssd_icon_text_position_tr');
    f = $("#ssd_display_sticky_no");
    if (a.val() == 'icon') {
        if ($("#ssd_display_corner_icon_yes").prop("checked")) {
            b.attr('data-order', '');
            b.hide();
            c.hide();
            $('.ssd_show_sticky_icon_tr').hide();
            $('.ssd_show_sticky_icon_on_tr').hide();
            d.show();
            if (($('#ssd_display_sticky_on').val() == 'media')) {
                e.show()
            }
            f.prop("checked", true);
            $('input[name="ssd[ssd_icon_border_radius]"]').val('0');
        } else {
            d.show();
            b.attr('data-order', 'social-share');
            b.show();
            if (f.prop("checked")) {
                e.hide()
            } else {
                if ($("#ssddimg_yes").prop("checked")) {
                    c.hide()
                } else {
                    c.show()
                }
            }
        }
        ssd_sticky_option();
    } else if (a.val() == 'text') {
        d.show();
    }
}

function ssd_display_image() {
    $ = jQuery;
    if ($('#ssd_social_share_type').val() == 'icon') {
        a = $('.ssd_image_layout_tr');
        b = $('.ssd_icon_text_color_tr');
        c = $('.ssd_icon_text_bg_color_tr');
        if ($("#ssddimg_yes").prop("checked")) {
            a.show();
            $('.ssd_icon_border_radius_tr').hide();
            b.hide();
            c.hide();
            $('.ssd_icon_text_alignment_tr').show();
            $('.ssd_show_corner_tr').hide();
            $('input[name="ssd[ssd_icon_border_radius]"]').val('0');
            $('input[name="ssd[ssd_icon_color]').val('');
            $('input[name="ssd[ssd_icon_bg_color]').val('');
            $("#ssd_display_corner_icon_no").prop("checked", true);
            ssd_sticky_option();
        } else {
            ssd_display_corner_icon();
            a.hide();
            b.show();
            c.show()
        }
    } else {
        a.hide()
    }
}

function ssd_grid_style_change() {
    $ = jQuery;
    var aa = $('input[name="ssd[ssd_design_layout]"]:checked').val();
    var bb = $("#ssdsstmly").val();
    a = $('.ssd_display_filter_tr');
    b = $('.ssd-media-setting-margin-tr');
    c = $('.ssd-layout-4');
    d = $('.ssd-box-shadow');
    e = $('.ssd-bg-color');
    f = $('.ssd_autoplay_display_slider_tr');
    g = $('.ssd_social_stream_pagination_tr');
    h = $('.ssd_display_grid_style');
    i = $('.ssd_display_columns');
    j = $('#ssd_display_autoplay_no');
    k = $('.ssd_justified_grid_height');
    l = $('#ssd_display_filter_no');
    m = $('.ssd_display_slider_tr');
    n = $('.ssd_display_rows');
    o = $('.ssd_display_horizontal_rows');
    p = $('.ssd_display_max_width');
    q = $('.ssd_display_search_tr');
    if (bb == 'listing') {
        q.show();
    } else {
        q.hide();
    }
    b.show();
    c.hide();
    d.show();
    e.show()
    if (bb == 'listing') {
        a.show()
    } else {
        a.hide()
    }
    if (bb == 'listing') {
        if (l.prop('checked')) {
            g.show();
            ssd_pagination_layout()
        } else {
            g.hide()
        };
        g.show();
        ssd_pagination_layout()
    } else {
        g.hide()
    }
    $(".ssd_display_filter").on(
        'click',
        function() {
            if (l.prop('checked')) {
                g.show();
                ssd_pagination_layout()
            } else {
                g.hide()
            };
            g.show();
            ssd_pagination_layout()
        }
    );
    if (bb == 'listing') {
        h.hide();
        k.hide();
        i.show()
    } else {
        h.hide();
        k.hide();
        i.hide()
    }
    a.show();
    m.hide();
    p.hide();
    n.hide();
    o.hide();
    ssd_stream_type();
}

function ssd_display_sticky_on() {
    $ = jQuery;
    var ssd_sticky_on = $('#ssd_display_sticky_on').val();
    if (ssd_sticky_on == 'author') {
        $('.ssd_icon_text_alignment_tr').hide();
        $('.ssd_icon_text_position_tr').hide()
    } else {
        if ($("#ssd_display_corner_icon_yes").prop("checked")) {
            $('.ssd_icon_text_position_tr').show()
        };
        $('.ssd_icon_text_alignment_tr').show()
    }
}

function ssd_common_style() {
    $ = jQuery || window.jQuery;
    $('input[name="ssd[ssd_overlay_bg_color]"]').val('#ffffff');
    $('input[name="ssd[ssd_icon_border_radius]"]').val('0');
    $('input[name="ssd[ssd_text_border_radius]"]').val('3');
    $('input[name="ssd[ssd_card_box_hoffset]"]').val('0');
    $('input[name="ssd[ssd_card_box_voffset]"]').val('0');
    $('input[name="ssd[ssd_card_box_blur]"]').val('4');
    $('input[name="ssd[ssd_card_box_spread]"]').val('0');
    $('input[name="ssd[ssd_card_box_shadow]"]').val('#cccccc');
    $("#ssd_display_social_icon_yes").prop("checked", true);
    $('#ssd_icon_alignment').select2().val('left');
    $('#ssd_icon_position').select2().val('top');
    $('input[name="ssd[ssd_icon_color]"]').val('');
    $('input[name="ssd[ssd_icon_bg_color]"]').val('');
    $("#ssd_display_feed_without_media_yes").prop("checked", true);
    $("#ssd_display_title_yes").prop("checked", true);
    $('#ssd_title_font_weight').val('bold');
    $("#ssd_display_content_yes").prop("checked", true);
    $('input[name="ssd[ssd_content_limit]').val('50');
    $("#ssd_display_author_box_yes").prop("checked", true);
    $("#ssd_view_user_name_yes").prop("checked", true);
    $("#ssd_view_date_yes").prop("checked", true);
    $('input[name="ssd[ssd_author_border_radius]').val('0');
    $('input[name="ssd[ssd_author_bg_color]').val('');
    $('input[name="ssd[ssd_author_margin_top]"]').val('0');
    $('input[name="ssd[ssd_author_margin_bottom]"]').val('0');
    $('input[name="ssd[ssd_count_bg_color]').val('');
    $('input[name="ssd[ssd_count_border_top_width]').val('1');
    $('input[name="ssd[ssd_count_border_top_type]').val('solid');
    $('input[name="ssd[ssd_count_border_bottom_type]').val('solid');
    $('input[name="ssd[ssd_count_border_right_type]').val('solid');
    $('input[name="ssd[ssd_count_border_left_type]').val('solid');
    $('input[name="ssd[ssd_count_border_top_color]').val('#e5e5e5');
    $('input[name="ssd[ssd_count_border_bottom_width]').val('0');
    $('input[name="ssd[ssd_count_border_left_width]').val('0');
    $('input[name="ssd[ssd_count_border_right_width]').val('0');
    $('input[name="ssd[ssd_count_border_left_color]').val('');
    $('input[name="ssd[ssd_count_border_right_color]').val('');
    $('input[name="ssd[ssd_count_border_bottom_color]').val('');
    $("#ssd_user_follower_count_yes").prop("checked", true);
    $("#ssd_user_friend_count_yes").prop("checked", true);
    $("#ssd_retweet_count_yes").prop("checked", true);
    $("#ssd_reply_count_yes").prop("checked", true);
    $("#ssd_favorite_count_yes").prop("checked", true);
    $("#ssd_view_count_yes").prop("checked", true);
    $("#ssd_like_count_yes").prop("checked", true);
    $("#ssd_dislike_count_yes").prop("checked", true);
    $("#ssd_comment_count_yes").prop("checked", true);
    if ($('#ssd_display_sticky_yes').prop("checked")) {
        $('.ssd_show_corner_tr').hide();
        $('.ssd_image_layout_tr').hide();
        $('.ssd_show_image_for_icon_tr').hide()
    }
}

function ssd_click_disable() {
    $ = jQuery;
    $('.ssd_click_disable').click(function(e) { e.stopPropagation();
        e.preventDefault();
        e.stopImmediatePropagation(); return false });
}
jQuery(document).ready(function() {
    if(jQuery('input[name="ssd[ssd_display_image]"]:checked').val() == 0 || jQuery('input[name="ssd[ssd_display_social_icon]"]:checked').val() == 0) {
        jQuery('.ssd_image_layout_tr').hide();
    } else {
        jQuery('.ssd_image_layout_tr').show();
    }
    jQuery('.disable_li td div.wp-picker-container').on(
        'click',
        function(e) {
            e.preventDefault();
            jQuery("#ssd-advertisement-popup").dialog({
                resizable: false,
                draggable: false,
                modal: true,
                height: "auto",
                width: 'auto',
                maxWidth: '100%',
                dialogClass: 'ssd-advertisement-ui-dialog',
                buttons: [{
                    text: 'x',
                    "class": 'ssd-btn ssd-btn-gray',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }],
                open: function(event, ui) {
                    jQuery(this).parent().children('.ui-dialog-titlebar').hide();
                },
                hide: {
                    effect: "fadeOut",
                    duration: 500
                },
                close: function(event, ui) {
                    jQuery("#ssd-advertisement-popup").dialog('close');
                },
            });
        }
    );

    jQuery('.disable_li input').on(
        'keypress',
        function(e) {
            e.preventDefault();
            jQuery("#ssd-advertisement-popup").dialog({
                resizable: false,
                draggable: false,
                modal: true,
                height: "auto",
                width: 'auto',
                maxWidth: '100%',
                dialogClass: 'ssd-advertisement-ui-dialog',
                buttons: [{
                    text: 'x',
                    "class": 'ssd-btn ssd-btn-gray',
                    click: function() {
                        jQuery(this).dialog("close");
                    }
                }],
                open: function(event, ui) {
                    jQuery(this).parent().children('.ui-dialog-titlebar').hide();
                },
                hide: {
                    effect: "fadeOut",
                    duration: 500
                },
                close: function(event, ui) {
                    jQuery("#ssd-advertisement-popup").dialog('close');
                },
            });
        }
    );

    if (jQuery( 'input[name="ssd[ssd_design_layout]"]:checked' ).val() == 'layout-2' ) {
		jQuery( '#ssd_icon_alignment option[value="left"]' ).remove();
		jQuery( '#ssd_icon_alignment option[value="right"]' ).remove();
		jQuery( '#ssd_icon_alignment' ).select2().trigger("change");
	}
});
