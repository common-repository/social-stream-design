'use strict';
jQuery(document).ready(
    function() {
        (function($) {
            "use strict";
            $(document).on(
                "click",
                ".ssd-share-button",
                function() {
                    var sslw = $(".ssd-share-link-wrapper");
                    if ($(this).parents(".ssd-action-row").find(".ssd-share-link-wrapper").hasClass("ssd-show-share")) {
                        sslw.removeClass("ssd-show-share");
                    } else {
                        sslw.removeClass("ssd-show-share");
                        $(this).parents(".ssd-action-row").find(".ssd-share-link-wrapper").addClass("ssd-show-share");
                    }
                }
            );
            $(document).on(
                "click",
                ".ssd-close-button",
                function() {
                    $(".ssd-share-link-wrapper").removeClass("ssd-show-share");
                }
            );
            var spsmh = function() {
                if ($(".ssd-scrollbox").length) {
                    var ssd_maxHeight = $(window).height() - 100;
                    $(".ssd-scrollbox").css("max-height", ssd_maxHeight + "px");
                }
            };
            $(window).resize(
                function() {
                    clearTimeout(this.id);
                    this.id = setTimeout(spsmh, 100);
                }
            );
            document.onkeydown = function(evt) {
                evt = evt || window.event;
                if (evt.keyCode == 27) {
                    ssd_removeModal();
                }
            };
            var slilod = false;
            var ssd_load_image = '<div class="ssd_lmp_products_loading"><i class="fas fa-spinner fa-spin ssd_lmp_rotate"></i></div>';
            var ssd_load_img_class = ".ssd_lmp_products_loading";
            if ($(".ssd-pgn-bar").length > 0) {
                var ssd_container = ".ssd_social_stream_listing";
                var pagination = ".ssd-pgn-bar";
                var ssd_next_page = ".ssd-pgn-bar a.next";
            }
            ssd_all_loadmore(ssd_container, pagination, ssd_next_page);

            function ssd_all_loadmore(ssd_container, pagination, ssd_next_page) {
                if ($(ssd_container).length > 0) {
                    var ssd_parent = $(ssd_container).closest(".ssd-wrapper-inner");
                    if ($(ssd_parent).hasClass("ssd_load_more_btn")) {
                        $(".ssd_social_stream_load_more_btn").on(
                            "click",
                            function() {
                                ssd_load_next_page(pagination, ssd_next_page);
                                if( $('.ssd-col-item').length <= $('#ssd_total_posts').val() ) {
                                    $(".ssd_social_stream_load_more_btn").hide();
                                }
                            }
                        );
                    }
                }
            }

            function ssd_load_next_page(pagination, ssd_next_page) {
                var user_next_page = false;
                var $next_page = $(ssd_next_page);
                if ($next_page.length > 0 || user_next_page !== false) {
                    ssd_start_ajax_loading();
                    var next_page;
                    if (user_next_page !== false) {
                        next_page = user_next_page;
                    } else {
                        next_page = $next_page.attr("href");
                    }
                    $.get(
                        next_page,
                        function(data) {
                            var $data = $(data);
                            var $feeds = $data.find(ssd_container).html();
                            $(ssd_container).append($feeds);
                            var $pagination = $data.find(pagination);
                            $(pagination).html($pagination.html());
                            ssd_end_ajax_loading(pagination, ssd_next_page);
                        }
                    );
                }
                if ($next_page.length == 0) {
                    $(".ssd_social_stream_load_more_btn").hide();
                }
            }

            function ssd_start_ajax_loading() {
                slilod = true;
                $(ssd_container).after($(ssd_load_image));
            }

            function ssd_end_ajax_loading(pagination, ssd_next_page) {
                var ssd_parent = $(ssd_container).closest(".ssd-wrapper-inner");
                $(ssd_load_img_class).remove();
                // if ($(".ssd-cnt-prnt").hasClass("ssd_social_stream_listing")) {
                //     new AnimOnScroll(document.getElementById("ssd_social_stream_listing"), { minDuration: 0.4, maxDuration: 0.7, viewportFactor: 0.2 });
                // }
            }

            function ssd_removeModal() {
                $("body").find(".ssd-dialog-widget").remove();
                $("body").find(".ssd-dialog-popup-cover").remove();
                $("body").removeClass("noscroll");
            }
        })(jQuery);

        jQuery(".slickslider .slides").slick({
            autoplay: true,
            autoplaySpeed: 500,
            arrows: true,
            speed: 3000,
            slidesToShow: 1,
            centerPadding: '0px',
            centerMode: true,
            slidesToScroll:1,
            // adaptiveHeight:true,
            prevArrow:'<i class="fa fa-angle-left"></i>',
            nextArrow:'<i class="fa fa-angle-right"></i>',
            dots: true,
        }).on('setPosition', function (event, slick) {
            slick.$slides.css('height', slick.$slideTrack.height() + 'px');
        });
    }
);