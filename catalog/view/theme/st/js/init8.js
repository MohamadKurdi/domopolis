$(document).ready(function () {
    initPopups();

    $(".tooltip").tooltipster({
        contentCloning: false,
        plugins: ["follower"],
        trigger: "custom",
        triggerOpen: {
            click: true,
            tap: true,
            mouseenter: true,
            touchstart: true,
        },
        triggerClose: {
            mouseleave: true,
            originClick: true,
            touchleave: true,
        },
        maxWidth: 320,
        animationDuration: 0,
        delay: 1,
        delayTouch: [1, 1],
        trackerInterval: 50,
        distance: 3,
        side: ["left"],
    });

    // прокрутка свайпом
    function dragscroll_wrap(){
        let M = document.querySelector(".js-dragscroll-wrap");
        if (M) {
            let e = M.querySelector(".js-dragscroll");
            new ScrollBooster({
                viewport: M,
                content: e,
                // emulateScroll: true,
                emulateScroll: false,
                mode: "x",
                direction: "horizontal",
                bounceForce: 0.2,
                onUpdate: (t) => {
                    e.style.transform = `translate(\n                  ${-t.position.x}px, 0px                )`;
                },
            });
        }
    }
    dragscroll_wrap();

    let M2 = document.querySelector(".js-dragscroll-wrap2");
    if (M2) {
        let e2 = M2.querySelector(".js-dragscroll2");
        new ScrollBooster({
            viewport: M2,
            content: e2,
            emulateScroll: false,
            mode: "x",
            direction: "horizontal",
            bounceForce: 0.2,
            onUpdate: (t2) => {
                e2.style.transform = `translate(\n                  ${-t2.position.x}px, 0px                )`;
            },
        });
    }

    // Клонирование
    if (document.documentElement.clientWidth < 825) {
        //$("header .icons-link").clone(true).appendTo(".social-contact"); //это уберем
        
        $('header .mob-menu__btn').prependTo("header .head"); //перенос бургера на мобе

        /*$('header .menu-horizontal ul').clone().prependTo('.mobile-menu__list');*/
    }

    // Change city

    // $(".popup-city__close").click(function () {
    //     $(this).parents(".popup-city").hide();
    //     return false;
    // });

    // $(".change-city__btn, .popup-city__btn-other").click(function () {
    //     $(this).parents(".change-city").find('[rel="your-city"]').hide();
    //     $(this).parents(".change-city").find('[rel="choose-city"]').show();
    //     return false;
    // });

    $(".product__delete").click(function () {
        $(this).parents(".cart__item").find(".popup-delete").css("display", "flex");
        return false;
    });

    $(".popup-delete__cancel").click(function () {
        $(this).parents(".cart__item").find(".popup-delete").css("display", "none");
        return false;
    });

    // SumoSelect

    // $('select').SumoSelect();

    // $('.catalog__btn').click(function () {
    //   $(this).find('.burger').toggleClass('open');
    //   return false;
    // });

    // SumoSelect2

    // $('select').SumoSelect();

    // $('select.lang_select_ru')[0].sumo.selectItem(1);
    // $('select.lang_select_ua')[0].sumo.selectItem(2);
    // $('select.lang_select_kz')[0].sumo.selectItem(index);
    // $('select.lang_select_by')[0].sumo.selectItem(4);

    $(".catalog__btn").click(function () {
        $(this).find(".burger").toggleClass("open");
        $(this).parent().find(".menu-list").toggleClass("open");
        if ($(".menu-list").hasClass("open")) {
            $(".overlay_popup").show().css("z-index", "101");
            $(".tab-product-wrap.show").css("background", "rgba(0, 0, 0, 0.1)");
        } else {
            $(".overlay_popup").hide().css("z-index", "999");
            $(".tab-product-wrap.show").css("background", "rgba(255, 255, 255, 1)");
        }
        return false;
    });

    // readmore

    $(".read-more").readmore({
        speed: 3000,
        embedCSS: false,
        moreLink:
            '<a href="#"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M7 11L11 15M11 15L15 11M11 15L11 7M11 21C5.47715 21 0.999999 16.5228 1 11C1 5.47715 5.47715 0.999999 11 1C16.5228 1 21 5.47715 21 11C21 16.5228 16.5228 21 11 21Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n' +
            "</svg>\nПоказать все</a>",
        lessLink: "",
    });

    function ibg() {
        $.each($(".ibg"), function (index, val) {
            if ($(this).find("img.ibg-img").length > 0) {
                $(this).css("background-image", 'url("' + $(this).find("img.ibg-img").attr("src") + '")');
            }
        });
    }

    ibg();

    // accordion

    $(".accordion__title").click(function () {
        var accordion__item = $(this).closest(".accordion__item");
        $(this).siblings(".accordion__content").slideToggle(200);
        accordion__item.toggleClass("opened");
    });

    //

    $("[data-count-item]").each(function (index, value) {
        var dataCountItem = $(this).attr("data-count-item");
        var countItem = $(this).children("div");
        var countItemLength = $(this).children("div").length;
        if (countItemLength > dataCountItem) {
            $(this).append('<div class="show-all-item">Показать все</div>');
            countItem.slice(dataCountItem, countItemLength).hide();
        }
    });

    $("[data-count-item] .show-all-item").click(function () {
        $(this).closest("[data-count-item]").children("div").slideDown(300);
        $(this).hide();
    });

    $(".filter-btn").click(function () {
        $(this).toggleClass("opened");
        $(".filter").slideToggle(200);
    });

    var activeSort = $(".catalog__sort-dropdown li.active a").text();
    $(".catalog__sort-btn > span").text(activeSort);

    $(".catalog__sort-btn > span").click(function () {
        $(this).toggleClass("opened");
        $(".catalog__sort-dropdown").slideToggle(200);
    });

    // Переключатель вида материала

    function templateSwitcher(target) {
        var targetClass = $(target).attr("class");

        function setTpl(tpl) {
            if (tpl) {
                $(target).attr("class", targetClass).addClass(tpl);
                $("[data-tpl=" + tpl + "]")
                    .addClass("current")
                    .siblings()
                    .removeClass("current");
            }
        }

        setTpl(localStorage.getItem("short_tpl"));

        $(document).on("click", ".display-type a", function (e) {
            e.preventDefault();
            if (!$(this).hasClass("current")) {
                var tpl = $(this).data("tpl");
                setTpl(tpl);
                localStorage.setItem("short_tpl", tpl);
            }
        });
    }

    $(function () {
        templateSwitcher(".catalog__content .product__item");
    });

    // Количество товара

    $(".minus").click(function () {
        var $input = $(this).parent().find("input");
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $(".plus").click(function () {
        var $input = $(this).parent().find("input");
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });

    $(".minus, .plus").click(function () {});

    // Анимация чисел

    /*  function calcCount() {
      for (var i = 0; i < $('.calcCount').length; i++) {
        var end = $('.calcCount').eq(i).text();
        countStart(end, i);
      }
    }

    function countStart(end, i) {
      var start = 0;
      var interval = setInterval(function () {
        $('.calcCount').eq(i).text(++start);
        if (start == end) {
          clearInterval(interval);
        }
      }, 1);//скорость менять вот-тута
    }
    calcCount();*/

    // tabs
    function activeTab(){
        $("ul.tabs__caption").on("click", "li:not(.active)", function () {
            $(this).addClass("active").siblings().removeClass("active").closest(".tabs").find("div.tabs__content").removeClass("active").eq($(this).index()).addClass("active");
            sliderTab();
        });
    }
    activeTab();


    // Footer menu

    $(".footer__links-title").on("click", function () {
        var th = $(this);
        var f = th.closest(".footer__links-item").toggleClass("open");
    });

    $(".number__product__block input").on("input change paste", function () {
        let minimum = parseInt($(this).attr("data-minimum")) || 1;
        let tryval = parseInt(this.value.replace(/[^0-9]/, ""));
        let setval = tryval;
        if (tryval == "" || tryval == 0) {
            setval = minimum;
        }
        if (minimum > 1 && tryval % minimum != 0) {
            setval = minimum * Math.round(tryval / minimum);
        }
        $(this).val(Math.round(setval > 0 ? setval : minimum));
    });


function number_controls(){
    $(".number__product__block .number_controls > div").on("click", function () {
        let input = $(this).closest(".number__product__block").find(".input_number");
        let value = parseInt(input.val()) || 0;
        let minimum = parseInt(input.attr("data-minimum")) || 1;
        if ($(this).hasClass("nc-minus")) {
            value = value - minimum;
            value == 0 ? (value = minimum) : false;
        }
        if ($(this).hasClass("nc-plus")) {
            value = value + minimum;
        }
        input.val(value).change();
    });  
}

setTimeout(function() {
    number_controls()
 }, 1500);


    // initialize swiper sliders
function sliderTab(){
    $(".right_column__slider-count .swiper-container").each(function () {
        let iSliderItem = $(this).find(".swiper-slide").not(".swiper-slide-duplicate").length;
        // console.log(iSliderItem);

        let navPag = $(this).closest(".nd_slider").find(".swiper-pagination");
        let navPrev = $(this).closest(".nd_slider").find(".swiper-prev-slide");
        let navNext = $(this).closest(".nd_slider").find(".swiper-next-slide");

        var swiper = new Swiper(this, {
            loop: true,
            slidesPerView: 3,
            centeredSlides: false,
            spaceBetween: 0,
            pagination: {
                el: navPag,
                type: "fraction",
                renderFraction: function (currentClass, totalClass) {
                    return '<span class="' + currentClass + '"></span>' + " / " + '<span class="' + totalClass + '"></span>';
                },
            },
            on: {
                init() {
                    setTimeout(updateFraction, 1000, this);
                },
                slideChange() {
                    updateFraction(this);
                },
                resize() {
                    updateFraction(this);
                },
            },
            navigation: {
                nextEl: navNext,
                prevEl: navPrev,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    watchSlidesVisibility: false,
                    loop: false,
                    centeredSlides: false,
                    autoHeight: false,
                    watchOverflow: false,
                    grabCursor: true,
                    allowTouchMove: true,
                },
                600: {
                    slidesPerView: 2,
                    grabCursor: true,
                 allowTouchMove: true,
                },
                1200: {
                    slidesPerView: 3,
                    grabCursor: false,
                    allowTouchMove: false,
                },
            },
        });

        function updateFraction(slider) {
            const { params, activeIndex, realIndex } = slider;

            let copySlide = slider.$el.find(".swiper-slide-duplicate").length;

            slider.$el.find(`.${params.pagination.currentClass}`).text(`${realIndex + 1}`);

            slider.$el.find(`.${params.pagination.totalClass}`).text(slider.slides.length - copySlide);
        }

        var swiper = new Swiper(this, {
            loop: true,
            slidesPerView: 3,
            centeredSlides: false,
            spaceBetween: 0,
            grabCursor: true,
            pagination: {
                el: navPag,
                type: "fraction",
                renderFraction: function (currentClass, totalClass) {
                    return '<span class="' + currentClass + '"></span>' + " / " + '<span class="' + totalClass + '"></span>';
                },
            },
            on: {
                init() {
                    setTimeout(updateFraction, 1000, this);
                },
                slideChange() {
                    updateFraction(this);
                },
                resize() {
                    updateFraction(this);
                },
            },
            navigation: {
                nextEl: navNext,
                prevEl: navPrev,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    watchSlidesVisibility: true,
                    loop: true,
                    centeredSlides: false,
                    autoHeight: false,
                    grabCursor: true,
                    watchOverflow: true,
                    allowTouchMove: true,
                    allowTouchMove: true,
                },
                600: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 3,
                    allowTouchMove: false,
                    grabCursor: false,
                },
            },
        });

        function updateFraction(slider) {
            const { params, activeIndex, realIndex } = slider;

            let copySlide = slider.$el.find(".swiper-slide-duplicate").length;

            slider.$el.find(`.${params.pagination.currentClass}`).text(`${realIndex + 1}`);

            slider.$el.find(`.${params.pagination.totalClass}`).text(slider.slides.length - copySlide);


        }
       
           
       
    });
}

sliderTab();
setTimeout(function() {
    sliderTab();
    activeTab();
    dragscroll_wrap();
}, 2200);


    var swiper = new Swiper(".news-slider", {
        slidesPerView: 1,
        loop: false,
        centeredSlides: false,
        // autoHeight: true,
        spaceBetween: 30,
        simulateTouch: false,
        watchOverflow: true,
        autoplay: {
            delay: 5000,
        },
        // init: false,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            480: {
                slidesPerView: 2,
            },
            1000: {
                slidesPerView: 3,
            },
            1280: {
                slidesPerView: 3,
                spaceBetween: 50,
            },
        },
    });

    var swiper = new Swiper(".pluses-slider", {
        slidesPerView: 1,
        loop: false,
        centeredSlides: false,
        autoHeight: true,
        simulateTouch: false,
        watchOverflow: true,
        // init: false,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            520: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1000: {
                slidesPerView: 4,
            },
        },
    });

    var swiper = new Swiper(".reviews-slider-v1 .swiper-container", {
        slidesPerView: "auto",
        loop: true,
        centeredSlides: false,
        autoHeight: false,
        simulateTouch: false,
        watchOverflow: true,
        autoplay: {
            delay: 5000,
        },
        // init: false,
        navigation: {
            nextEl: ".reviews-slider-v1 .swiper-next-slide",
            prevEl: ".reviews-slider-v1 .swiper-prev-slide",
        },
        pagination: {
            el: ".reviews-slider-v1 .swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            480: {
                autoHeight: false,
            },
            768: {
                autoHeight: false,
            },
        },
    });

    $(".product-slider .swiper-container").each(function (index, value) {
        if ($(this).parent().hasClass("recommended")) {
            var mySwiper = new Swiper(value, {
                navigation: {
                    nextEl: value.nextElementSibling.nextElementSibling,
                    prevEl: value.nextElementSibling,
                },
                slidesPerView: "auto",
                watchSlidesVisibility: true,
                loop: false,
                centeredSlides: false,
                autoHeight: false,
                simulateTouch: false,
                watchOverflow: true,
            });
        } else {
            var mySwiper = new Swiper(value, {
                navigation: {
                    nextEl: value.nextElementSibling.nextElementSibling,
                    prevEl: value.nextElementSibling,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                slidesPerView: "auto",
                watchSlidesVisibility: true,
                loop: false,
                centeredSlides: false,
                autoHeight: false,
                simulateTouch: false,
                watchOverflow: true,
            });
        }
    });

    function FixedHeader() {
        var header = $("header");
        var header_h = $("header").innerHeight();
        var header_hh = $("header .head").innerHeight();

        $(window).scroll(function () {
            var scrolled = $(window).scrollTop();
            if (scrolled < header_hh) {
                mobile_menu.css("top", header_h - scrolled - 2 + "px");
            }
            if (scrolled > header_h - 55) {
                header.addClass("fixed");
                $("header .search-scrol--wrap").addClass("fix");
                // search
                if (document.documentElement.clientWidth > 1000) {
                    $("#search-wrap").appendTo(".search-scrol--wrap");
                }
                // navTabProduct
                if (document.documentElement.clientWidth < 560) {
                    // $('#product-detail .tabs__nav').addClass('fix-top');
                }
            } else {
                $("header .search-scrol--wrap").removeClass("fix");
                $("header .search-scrol--wrap .search").removeClass("open-search");
                $("header .search-scrol--wrap .search-scrol-btn").removeClass("open");
                // search
                if (document.documentElement.clientWidth > 1000) {
                    $("header .search-scrol--wrap #search-wrap").appendTo("header .head .middle");
                }
                // navTabProduct
                if (document.documentElement.clientWidth < 560) {
                    // $('#product-detail .tabs__nav').removeClass('fix-top');
                }

                header.removeClass("fixed");
            }
        });
    }

    FixedHeader();

    // mob menu

    $("body").append('<div class="menu-overlay"></div>');
    var menu_overlay = $(".menu-overlay");
    var mobile_menu = $(".mobile-menu");

    function mobMenuTop() {
        var header_h = $("header").innerHeight();
        var header_hh = $("header .head").innerHeight();
        var scrolled = $(window).scrollTop();
        
            mobile_menu.css("top", header_h - scrolled - 1 + "px");
            console.log(header_h - scrolled - 1 + "px")
        
    }

    mobMenuTop();

    $(".mob-menu__btn").click(function () {
        if ($(this).hasClass("open")) {
            $(this).removeClass("open");
            menu_overlay.removeClass("open");
            mobile_menu.removeClass("open");
            $('body').css('overflow','initial');
        } else {
            $('body').css('overflow','hidden');
            $(this).addClass("open");
            menu_overlay.addClass("open");
            mobile_menu.addClass("open");
            mobMenuTop();
        }
    });

    menu_overlay.click(function () {        
        $('body').css('overflow','initial');
        $(this).removeClass("open");
        $(".mob-menu__btn").removeClass("open");
        mobile_menu.removeClass("open");
    });
    // $(".phone").mask("+0(000)000-00-00");

    $(window).resize(function () {
        FixedHeader();
        mobMenuTop();
    });

    $('.open_mob_filter').on('click', function(){
        $('.mfilter-free-button').trigger('click');
    })
});
