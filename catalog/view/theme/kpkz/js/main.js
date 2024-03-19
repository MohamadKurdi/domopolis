function getWindowWidth() {
    return window.innerWidth || document.body.clientWidth;
}

function initPopups() {
    $(".do-popup-element").click(function () {
        let $popup = $("#" + $(this).attr("data-target"));
        $popup.show();
        $("#main-overlay-popup").show();
        let scrol = window.scrollY + 50;
        let popap = $(".popup-form").css("top", scrol);

        $("body").css("overflow", "hidden");
        
        $("#main-overlay-popup, .overlay-popup-close").click(function () {
            $("#main-overlay-popup").hide();
            $popup.hide();
            $("body").css("overflow", "initial");
        });
    });
    
}
initPopups();

function getURLVar(key) {
    var value = [];
    
    var query = String(document.location).split("?");
    
    if (query[1]) {
        var part = query[1].split("&");
        
        for (i = 0; i < part.length; i++) {
            var data = part[i].split("=");
            
            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }
        
        if (value[key]) {
            return value[key];
        } else {
            return "";
        }
    }
}

function addToCart(e, t) {
    $("#product__grid").css({ opacity: "0.5" }).addClass("load_product_grid");
    t = void 0 !== t ? t : 1;
    $.ajax({
        url: "/index.php?route=checkout/cart/add",
        type: "post",
        data: "product_id=" + e + "&quantity=" + t,
        dataType: "json",
        beforeSend: function(){
            if (NProgress instanceof Object){				
                NProgress.configure({ showSpinner: false });
                NProgress.start();
                NProgress.inc(0.3);
            }   
        },
        error: function (e) {
            console.log(e);
        },
        complete: function(e){
            if (NProgress instanceof Object){	
                NProgress.inc(0.2);
            }
        },
        success: function (json) {
            if (json["total"]) {
                $("#header-small-cart").load("/index.php?route=module/cart");
                $("#popup-cart").load("/index.php?route=common/popupcart", function () {
                    $("#popup-cart-trigger").click();
                    if ($(window).width() <= "500") {
                        $("body").css("overflow", "hidden");
                    }
                    
                    $.ajax({
                        url: "/index.php?route=product/product/getEcommerceInfo",
                        data: "product_id=" + e,
                        dataType: "json",
                        error: function (e) {
                            console.log(e);
                        },
                        beforeSend: function () {
                            if (NProgress instanceof Object){	
                                NProgress.inc(0.3);
                            }
                            if (document.documentElement.clientWidth < 780) {
                                $("header .top-menu .icons-link").remove();
                            }
                        },
                        complete: function () { 
                            if (NProgress instanceof Object){	
                                NProgress.done();  $(".fade").removeClass("out");
                            }
                            
                            $("#product__grid").css({ opacity: "1" }).removeClass("load_product_grid");
                        },
                        success: function (json) {
                            if (document.documentElement.clientWidth < 780) {
                                $("header .icons-link").clone().appendTo(".social-contact");
                            }
                            setTimeout(function(){
                                checkButtonTrigger();
                            },500)
                            window.dataLayer = window.dataLayer || [];
                            console.log("dataLayer.push addToCart");
                            
                            dataLayer.push({
                                'event': 'add_to_cart',
                                'value': json.price,
                                'items': [{
                                    'id': json.product_id, 
                                    'google_business_vertical': 'retail'
                                }]
                            });
                            
                            dataLayer.push({
                                event: "addToCart",
                                ecommerce: {
                                    currencyCode: json.currency,
                                    add: {
                                        products: [
                                        {
                                            id: json.product_id,
                                            name: json.name,
                                            price: json.price,
                                            brand: json.brand,
                                            category: json.category,
                                            quantity: t,
                                        },
                                        ],
                                    },
                                },
                            });

                            if((typeof VK !== 'undefined')){
                                console.log('VK trigger add_to_cart');

                                VK.Retargeting.ProductEvent(
                                    json.config_vk_pricelist_id, 
                                    'add_to_cart', 
                                    {
                                        'products' : [{
                                            id: json.product_id,
                                            price_from: 0,
                                            price: json.price
                                        }], 
                                        'currency_code': json.currency, 
                                        'total_price': json.price
                                    }
                                    );
                            }
                            
                            if (typeof fbq !== "undefined") {
                                fbq("track", "AddToCart", {
                                    value: json.price,
                                    currency: json.currency,
                                    content_type: "product",
                                    content_ids: [parseInt(e)],
                                });
                            }
                            
                        },
                    });
});
}

if (getWindowWidth() <= 767) {
    var name_prod = json.name,
    img_prod = json.image;
    
    $('#shov_mob').find('.img').attr('src', img_prod);     
    $('#shov_mob').find('.name').html(name_prod);  
    $('#shov_mob').show();
    
    setTimeout(function(){
        $('#shov_mob').hide();
    }, 1500);
}


},

});
}

function addToCompare(e) {
    $.ajax({
        url: "index.php?route=product/compare/add",
        type: "post",
        data: "product_id=" + e,
        dataType: "json",
        error: function (e) {
            console.log(e);
        },
        success: function (e) {
            $(".success, .warning, .attention, .information").remove(),
            e.success &&
            ($("#popup-cart").html('<div class="object success_body">' + e.success + '<div class="overlay-popup-close"></div>'),
                $("#popup-cart-trigger").click(),
                $(".success").fadeIn("slow"),
                $("#wishlist-total").html(e.total));
            
            $.ajax({
                url: "/index.php?route=product/product/getEcommerceInfo",
                data: "product_id=" + e,
                dataType: "json",
                error: function (e) {
                    console.log(e);
                },
                success: function (json) {
                    window.dataLayer = window.dataLayer || [];
                    console.log("dataLayer.push addToCompare");
                    dataLayer.push({
                        event: "addToCompare",
                        ecommerce: {
                            currencyCode: json.currency,
                            add: {
                                products: [
                                {
                                    id: json.product_id,
                                    name: json.name,
                                    price: json.price,
                                    brand: json.brand,
                                    category: json.category,
                                },
                                ],
                            },
                        },
                    });


                    if((typeof VK !== 'undefined')){
                        console.log('VK trigger add_to_wishlist');

                        VK.Retargeting.ProductEvent(
                            json.config_vk_pricelist_id, 
                            'add_to_cart', 
                            {
                                'products' : [{
                                    id: json.product_id,
                                    price_from: 0,
                                    price: json.price
                                }], 
                                'currency_code': json.currency, 
                                'total_price': json.price
                            }
                            );
                    }

                },
            });
        },
    });
}

function addToWishList(e) {
    $.ajax({
        url: "index.php?route=account/wishlist/add",
        type: "post",
        data: "product_id=" + e,
        dataType: "json",
        error: function (e) {
            console.log(e);
        },
        success: function (e) {
            $(".success, .warning, .attention, .information").remove(),
            e.success &&
            ($("#popup-cart").html('<div class="object success_body">' + e.success + '<div class="overlay-popup-close"></div>'),
                $("#popup-cart-trigger").click(),
                $(".success").fadeIn("slow"),
                $("#wishlist-total").html(e.total));
            
            $.ajax({
                url: "/index.php?route=product/product/getEcommerceInfo",
                data: "product_id=" + e,
                dataType: "json",
                error: function (e) {
                    console.log(e);
                },
                success: function (json) {
                    window.dataLayer = window.dataLayer || [];
                    console.log("dataLayer.push addToWishList");
                    dataLayer.push({
                        event: "addToWishList",
                        ecommerce: {
                            currencyCode: json.currency,
                            add: {
                                products: [
                                {
                                    id: json.product_id,
                                    name: json.name,
                                    price: json.price,
                                    brand: json.brand,
                                    category: json.category,
                                },
                                ],
                            },
                        },
                    });


                    if((typeof VK !== 'undefined')){
                        console.log('VK trigger add_to_wishlist');

                        VK.Retargeting.ProductEvent(
                            json.config_vk_pricelist_id, 
                            'add_to_cart', 
                            {
                                'products' : [{
                                    id: json.product_id,
                                    price_from: 0,
                                    price: json.price
                                }], 
                                'currency_code': json.currency, 
                                'total_price': json.price
                            }
                            );
                    }
                    
                },
            });
        },
    });
}

// Кнопка на вверх
var top_show = 150;
var delay = 1000;
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > top_show) $("#top").fadeIn();
        else $("#top").fadeOut();
    });
    $("#top").click(function () {
        $("body, html").animate(
        {
            scrollTop: 0,
        },
        delay
        );
    });
});

$(document).mouseup(function (e) {
    var div = $(".menu-list, .catalog__btn, .mobile-menu__list, #popup-cart, #quick_popup, #formRev, #info_delivery, #notification_reviews, #callback-view, .popup-form, .popup_rev");
    if (!div.is(e.target) && div.has(e.target).length === 0) {
        $(".menu-list, .topmenu").removeClass("open");
        $(".overlay_popup").hide().css("z-index", "999");
        $(".popup-form").hide();
        $(".catalog__btn .burger").removeClass("open");
    }
});

// mobile menu

if (document.documentElement.clientWidth < 1000) {
    $("#mobile-category .parent.level1").each(function () {
        $(this).find("a:first").append('<span class="btn-opnen-child-menu fas fa-ellipsis-h"></span>');
    });
    
    $("#mobile-category .parent.level1 .topmenu").each(function () {
        $(this).find(".title_mmenu:first").prepend('<span class="btn-close-child-menu fas fa-chevron-left"></span>');
    });
    
    $("#mobile-category .parent.level1 a .btn-opnen-child-menu").click(function (e) {
        e.preventDefault();
        $(this).closest("li.level1").find(".topmenu").addClass("open_child");
        e.stopPropagation();
    });
    
    $("#mobile-category .parent.level1 .topmenu .btn-close-child-menu").click(function (e) {
        $(this).closest(".topmenu").removeClass("open_child");
    });
    
    $(".catalog_list__block .level1 .topmenu .main-center-cat-block .product__item ").each(function () {
        var _link_prod = $(this).find(".product__title > a").attr("href");
        var triggetBtn = $(this).find(".product__btn-cart > button");
        $(this).find('.product__btn-cart > button').prop("onclick", null).off("click");
        triggetBtn.on("click", function () {
            window.location.href = _link_prod;
        });
    });
}

$(".mobile-menu__list nav > ul > li.level1 > i").each(function () {
    $(this).on("click", function () {
        $(this).closest("li.level1").addClass("open_menu");
        $(this).closest("li.level1").find(".topmenu").addClass("open");
        $(this).closest("li.level1").siblings().find(".topmenu").removeClass("open");
        $(this).closest("li.level1").siblings().removeClass("open_menu");
    });
});

$(".mobile-menu__list .information-menu__mobile").on("click", function () {
    $(this).toggleClass("open_menu_parent");
    $(this).find("ul").toggleClass("open_menu");
});
$(".mobile-menu__list .account-menu__mobile").on("click", function () {
    $(this).toggleClass("open_menu_parent");
    $(this).find("ul").toggleClass("open_menu");
});

var _mMenu;
$(".mobile-menu .children-category-list li.parent .btn-children").on("click", function (e) {
    if (_mMenu !== this) {
        _mMenu = this;
        e.preventDefault();
        $(this).closest("li.parent").addClass("open_menu3");
    } else {
        console.log(_mMenu);
    }
});

$(".catalog__link-mob-btn").on("click", function () {
    $(".catalog_list__block").toggleClass("open_menu_category");
});

$(".b24-button").on("click touchstart", function () {
    $(".b24-widget-button-inner-container").trigger("click");
});

const accauntHeaderBtn = $("#account_header button.profile"),
accauntMenu = $("#account_header .content");

accauntHeaderBtn.on("click", function () {
    if ($(this).hasClass("is-active")) {
        $(this).removeClass("is-active");
        accauntMenu.slideUp();
    } else {
        $(this).addClass("is-active");
        accauntMenu.slideDown();
    }
});

$(document).mouseup(function (e) {
    if (!accauntHeaderBtn.is(e.target) && accauntHeaderBtn.has(e.target).length === 0) {
        accauntMenu.slideUp();
        accauntHeaderBtn.removeClass("is-active");
    }
});

const cartHeaderBtn = $("#header-small-cart button.cart"),
cartMenu = $("#header-small-cart div.content");

cartHeaderBtn.on("click", function () {
    if ($(this).hasClass("is-active")) {
        $(this).removeClass("is-active");
        cartMenu.slideUp();
    } else {
        $(this).addClass("is-active");
        cartMenu.slideDown();
    }
});

$(document).mouseup(function (e) {
    if (!cartHeaderBtn.is(e.target) && cartHeaderBtn.has(e.target).length === 0) {
        cartMenu.slideUp();
        cartHeaderBtn.removeClass("is-active");
    }
});

function addSubscribe(module) {
    var email = $('input[name="subscribe_email' + module + '"]').attr("value");
    
    $.ajax({
        url: "index.php?route=module/subscribe/addSubscribe",
        type: "post",
        dataType: "json",
        data: "email=" + email + "&module=" + module,
        success: function (data) {
            $(".subscribe_success" + module + ", .error" + module).remove();
            
            if (data["error"]) {
                $(".subscribe" + module).after('<span class="error error' + module + '">' + data["error"] + "</span>");
            }
            
            if (data["success"]) {
                $(".subscribe" + module).after('<span class="subscribe_success' + module + '">' + data["success"] + "</span>");
                $('input[name="subscribe_email' + module + '"]').attr("value", "");
            }
        },
    });
}

$(".callback-view").on("click", function () {
    $("#callback-view .content").load("/index.php?route=module/callback");
});

// Search new
$(".search-scrol--wrap .search-scrol-btn").on("click", function () {
    let scroll = $(this).closest(".search-scrol--wrap");
    $(this).toggleClass("open");
    scroll.toggleClass("open");
    
    if (scroll.hasClass("fix")) {
        $(this).closest(".search-scrol--wrap").find("#search-wrap").toggleClass("open-search");
    }
});

$(document).mouseup(function (e) {
    var searchCloset = $("#search-wrap, .search-scrol--wrap");
    if (!searchCloset.is(e.target) && searchCloset.has(e.target).length === 0) {
        $(".search-scrol--wrap #search-wrap").removeClass("open-search");
        $("header .search-scrol--wrap .search-scrol-btn").removeClass("open");
    }
});
// Search new end

$("#button-review-report_bug").bind("click", function () {
    let __btn = $(this);
    let formData = new FormData($("form#report_bug-form")[0]);
    let coment = $("textarea[name='text']").val("");
    
    $.ajax({
        url: "index.php?route=kp/errorreport/write",
        type: "post",
        dataType: "json",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $(".success, .warning").remove();
            $("#button-review-report_bug").attr("disabled", true);
            __btn.after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
        },
        complete: function (data) {
            $("button-review-report_bug").attr("disabled", false);
            $(".attention").remove();
        },
        success: function (data) {
            if (data["error"]) {
                __btn.before('<div class="warning" style="color:red;">' + data["error"] + "</div>");
            }
            
            if (data.success) {
                __btn.before('<div class="success">' + data["success"] + "</div>");
                $("input[name='name']").val("");
                $("textarea[name='text']").val("");
                $("input[name='telephone']").val("");
            }
        },
        error: function (data) {
            console.log("error", data);
        },
    });
});

// header baner
$(".header-banner .close-button .close").on("click", function () {
    $(".header-banner").addClass("hide");
    if (getWindowWidth() <= 767) {
        $('.mobile-menu.open').css({
            top: '69px',
        });
    }
});

var navPrevSlide = $("#carousel-slide-up").find(".button-prev");
var navNextSlide = $("#carousel-slide-up").find(".button-next");

if ($("#carousel-slide-up .swiper-slide").length == 1) {
    navPrevSlide.remove();
    navNextSlide.remove();
}

if ($("#carousel-slide-up .swiper-slide").length == 1) {
    var hBaner = new Swiper("#carousel-slide-up", {
        loop: true,
        slidesPerView: 1,
        centeredSlides: true,
        spaceBetween: 0,
        grabCursor: false,
        simulateTouch: false,
        autoHeight: false,
        navigation: {
            nextEl: navNextSlide,
            prevEl: navPrevSlide,
        },
        breakpoints: {
            320: {
                simulateTouch: true,
            },
            560: {
                simulateTouch: false,
            },
        },
    });
    // header baner
} else {
    var hBaner = new Swiper("#carousel-slide-up", {
        loop: true,
        slidesPerView: 1,
        centeredSlides: true,
        spaceBetween: 0,
        grabCursor: false,
        simulateTouch: false,
        autoHeight: false,
        navigation: {
            nextEl: navNextSlide,
            prevEl: navPrevSlide,
        },
        autoplay: {
            delay: 5000,
        },
        breakpoints: {
            320: {
                simulateTouch: true,
            },
            560: {
                simulateTouch: false,
            },
        },
    });
    // header baner
}
