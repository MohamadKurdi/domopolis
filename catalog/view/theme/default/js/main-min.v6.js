function l_image(e) {
    document.imagepath.src = e
}

function getURLVar(e) {
    var t = [],
	n = String(document.location).split("?");
    if (n[1]) {
        var a = n[1].split("&");
        for (i = 0; i < a.length; i++) {
            var o = a[i].split("=");
            o[0] && o[1] && (t[o[0]] = o[1])
		}
        return t[e] ? t[e] : ""
	}
}

function left_carusel() {
    var e = $(".carousel-block-ajcart").width() + 20;
    $(".carousel-items-ajcart .carousel-block-ajcart").eq(-1).clone().prependTo(".carousel-items-ajcart"), $(".carousel-items-ajcart").css({
        left: "-" + e + "px"
		}), $(".carousel-items-ajcart").animate({
        left: "0px"
	}, 200), $(".carousel-items-ajcart .carousel-block-ajcart").eq(-1).remove()
}

function right_carusel() {
    var e = $(".carousel-block-ajcart").width() + 20;
    $(".carousel-items-ajcart").animate({
        left: "-" + e + "px"
		}, 200), setTimeout(function() {
        $(".carousel-items-ajcart .carousel-block-ajcart").eq(0).clone().appendTo(".carousel-items-ajcart"), $(".carousel-items-ajcart .carousel-block-ajcart").eq(0).remove(), $(".carousel-items-ajcart").css({
            left: "0px"
		})
	}, 300)
}

function sklonenie(e, t, n, i) {
    var a = $(".ajaxtable_tbody .ajaxtable_tr").length % 100;
    return [e, t, n][a = 11 <= a && a <= 14 ? 0 : (a %= 10) < 5 ? 2 < a ? 2 : a : 0]
}

function start_show() {
    $(".ajaxtable_tbody .ajaxtable_tr").slice(0, 3).css("display", "table-row");
    var e = $(".ajaxtable_tbody .ajaxtable_tr").length;
    console.log($(".ajaxtable_tbody .ajaxtable_tr")), console.log($(".ajaxtable_tbody .ajaxtable_tr").length), 3 < e ? (product_count = e - 3, $("#hideproducts span b").html(product_count), $("#hideproducts span i").html("С‚РѕРІР°СЂ(РѕРІ)")) : e < 4 ? $("#hideproducts ").css("display", "none") : product_count = !1
}

function addToCart(e, t, n) {
    t = void 0 !== t ? t : 1, $.ajax({
        url: "index.php?route=checkout/cart/add",
        type: "post",
        data: "product_id=" + e + "&quantity=" + t + "&set=" + n,
        dataType: "json",
        success: function(e) {
			if ((typeof fbq !== 'undefined')){
				fbq('track', 'AddToCart');
			}
            $(".success, .warning, .attention, .information, .error").remove(), e.redirect && (location = e.redirect), !$.browser.msie || 7 != $.browser.version && 8 != $.browser.version ? e.success && ($("#showcart").trigger("click"), $("#cart-total").html(e.total).addClass("cart-full")) : e.success && ($("#notification").html('<div class="success" style="display: none;">' + e.success + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>'), $(".success").fadeIn("slow"), $("#cart-total").html(e.total), $("html, body").animate({
                scrollTop: 0
			}, "slow")), $("#cart").load("index.php?route=module/cart #cart > *"), $("body").append('<div id="mask-dark"></div>'), $("#mask-dark").on("click", function() {
			$(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
            })
		}
	})
}

function addToWishList(e) {
    $.ajax({
        url: "index.php?route=account/wishlist/add",
        type: "post",
        data: "product_id=" + e,
        dataType: "json",
        success: function(e) {
            $(".success, .warning, .attention, .information").remove(), e.success && ($("#notification").html('<div class="success" style="display: none;">' + e.success + '<div class="close"><i class="fa fa-times"></i></div>'), $(".success").fadeIn("slow"), $("#wishlist-total").html(e.total)), $("body").append('<div id="mask-dark"></div>'), $("#notification .success .close").on("click", function() {
                $(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
				}), $("#mask-dark").on("click", function() {
                $(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
			})
		}
	})
	}
	
	function AddToCartSet(e) {
		$.ajax({
			url: "index.php?route=module/set/add_to_cart",
			type: "post",
			data: $("#set-" + e + " input[type='text'], #set-" + e + " input[type='hidden'], #set-" + e + " input[type='radio']:checked, #set-" + e + " input[type='checkbox']:checked, #set-" + e + " select, #set-" + e + " textarea"),
			dataType: "json",
			error: function(e) {
				alert("No contact")
			},
			success: function(e) {
				$(".success, .warning, .attention, .information, .error").remove(), !$.browser.msie || 7 != $.browser.version && 8 != $.browser.version ? e.success && ($("#showcart").trigger("click"), $("#cart-total").html(e.total_cnt).addClass("cart-full")) : e.success && ($("#notification").html('<div class="success" style="display: none;">' + e.success + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>'), $(".success").fadeIn("slow"), $("#cart-total").html(e.total_cnt), $("html, body").animate({
					scrollTop: 0
				}, "slow")), $("body").append('<div id="mask-dark"></div>'), $("#mask-dark").on("click", function() {
                $(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
				})
			}
		})
	}
	
	function addToCompare(e) {
		$.ajax({
			url: "index.php?route=product/compare/add",
			type: "post",
			data: "product_id=" + e,
			dataType: "json",
			success: function(e) {
				$(".success, .warning, .attention, .information").remove(), e.success && ($("#notification").html('<div class="success" style="display: none;">' + e.success + '<div class="close"><i class="fa fa-times"></i></div>'), $(".success").fadeIn("slow"), $("#compare-total").html(e.total)), $("body").append('<div id="mask-dark"></div>'), $("#notification .success .close").on("click", function() {
					$(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
					}), $("#mask-dark").on("click", function() {
					$(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
				})
			}
		})
	}
	
	function do_notification(e) {
		$(".success, .warning, .attention, .information, #mask-dark").remove(), $("#notification").html('<div class="information" id="info_load" style="display: none;"><div class="close"><i class="fa fa-times"></i></div><div id="info_content"></div></div>'), $("#info_load > #info_content").load("index.php?route=information/information/info2&information_id=" + e), $(".information").fadeIn("slow"), $("body").append('<div id="mask-dark"></div>'), $("#mask-dark").on("click", function() {
			$(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
			}), $(document).on("keydown", function(e) {
			27 === e.keyCode && $(".information").fadeOut("slow")
		})
	}
	
	function do_picview(e) {
		$(".success, .warning, .attention, .information, #mask-dark").remove(), $("#notification").html('<div class="popup-image kitchen-panel-info information" id="info_load" style="display: none;"><div class="popup-image-fixed"><div class="close"><i class="fa fa-times"></i></div><div id="info_content"></div></div></div>'), $("#info_load  #info_content").load("index.php?route=product/picview&product_id=" + e, function() {
			$.getScript("catalog/view/theme/default/js/picview.js")
			}), $(".information").fadeIn("slow"), $("body").append('<div id="mask-dark"></div>'), $("#mask-dark").on("click", function() {
			$(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
			}), $(document).on("keydown", function(e) {
			27 === e.keyCode && $(".information").fadeOut("slow")
			}), $(".close").on("click", function() {
			$(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
		})
	}
	
	function headCart() {
		$("#cart").load("index.php?route=module/cart #cart > *"), $("#cart-total").load("index.php?route=module/cart #cart > *")
	}
	
	function addSubscribe(t) {
		var e = $('input[name="subscribe_email' + t + '"]').attr("value");
		$.ajax({
			url: "index.php?route=module/subscribe/addSubscribe",
			type: "post",
			dataType: "json",
			data: "email=" + e + "&module=" + t,
			success: function(e) {
				$(".subscribe_success" + t + ", .error" + t).remove(), e.error && $(".subscribe" + t).after('<span class="error error' + t + '">' + e.error + "</span>"), e.success && ($(".subscribe" + t).after('<span class="subscribe_success' + t + '">' + e.success + "</span>"), $('input[name="subscribe_email' + t + '"]').attr("value", ""))
			}
		})
	}
	$(document).ready(function() {
		$(".m-menu-group-button").click(function() {
			$(".m-menu-group").hasClass("active") ? $(".m-menu-group").removeClass("active") : $(".m-menu-group").addClass("active")
			}), $(".button-search").bind("click", function() {
			url = $("base").attr("href") + "index.php?route=product/search";
			var e = $("input[name='search']").attr("value");
			e && (url += "&search=" + encodeURIComponent(e)), location = url
			}), $("#search-wrap input[name='search']").bind("keydown", function(e) {
			if (13 == e.keyCode) {
				url = $("base").attr("href") + "index.php?route=product/search";
				var t = $("input[name='search']").attr("value");
				t && (url += "&search=" + encodeURIComponent(t)), location = url
			}
		});
		var e = 0;
		$("#cart").on("mouseover", function() {
			e || ($("#cart").load("index.php?route=module/cart #cart > *"), e = 1), $("#cart > .content").slideDown("fast")
			}), $("#cart > .content").on("mouseover", function() {
			e = 0, $("#cart > .content").slideUp("fast")
			}), $("#menu ul > li.custombox > a + div, .menu li.parent div.topmenu, #topbrand").each(function() {
			var e = $("#menu").offset(),
            t = $(this).parent().offset();
			i = t.left + $(this).outerWidth() - (e.left + $("#menu").outerWidth()), 0 < i && $(this).css("margin-left", "-" + (i + 5) + "px")
			}), $(window).resize(function() {
			$("#menu ul > li.custombox > a + div, .menu li.parent div.topmenu, #topbrand").each(function() {
				var e = $("#menu").offset(),
                t = $(this).parent().offset();
				i = t.left + $(this).outerWidth() - (e.left + $("#menu").outerWidth()), 0 < i && $(this).css("margin-left", "-" + (i + 5) + "px")
			})
			}), $.browser.msie && ($.browser.version <= 6 && ($("#column-left + #column-right + #content, #column-left + #content").css("margin-left", "195px"), $("#column-right + #content").css("margin-right", "195px"), $(".box-category ul li a.active + ul").css("display", "block")), $.browser.version <= 7 && ($("#menu > ul > li").bind("mouseover", function() {
				$(this).addClass("active")
				}), $("#menu > ul > li").bind("mouseout", function() {
				$(this).removeClass("active")
			}))), $(".success .close, .warning .close, .attention .close, .information .close").on("click", function() {
			$(this).parent().fadeOut("slow", function() {
				$(this).remove()
			})
			}), $(".do-tooltip").tooltip({
			track: !0,
			content: function() {
				var e = $(this);
				return "<img src='" + e.attr("src") + "' />&nbsp;&nbsp;" + e.attr("title")
			}
			}), $(".btn-group .btn-success").click(function() {
			$(".btn-group .btn-success").removeClass("active"), $(this).addClass("active")
		})
		}), $(".carousel-button-right-ajcart").on("click", function() {
		right_carusel()
		}), $(".carousel-button-left-ajcart").on("click", function() {
		left_carusel()
		}), $(document).ready(function() {
		$("#showcart").on("click", function() {
			var e = {
				closeButton: !1,
				scrolling: !1,
				href: $(this).attr("href"),
				fastIframe: !1,
				scrolling: !1,
				initialWidth: !1,
				innerWidth: !1,
				maxWidth: !1,
				height: !1,
				initialHeight: !1,
				innerHeight: !1,
				onComplete: function() {
					$(this).colorbox.resize(), $("#mask-dark").remove()
				},
				onLoad: function() {
					$(this).colorbox.resize(), $("#mask-dark").remove()
				}
			};
			jQuery.colorbox(e), $(window).resize(function() {
				$.colorbox.resize({
					width: window.innerWidth > parseInt(e.maxWidth) ? e.maxWidth : e.width
				})
			})
		})
		}), $(window).load(function() {
		var e = 0,
        t = 0;
		$("#menu ul > li.custombox > a + div").each(function() {
			(t = $(this).width()) > e && (e = t)
			}), varWidth = e, $("#menu ul > li.custombox > a + div").each(function() {
			var e = $("#menu").offset(),
            t = $(this).parent().offset();
			i = t.left + varWidth - (e.left + $("#menu").outerWidth()), 0 < i && $(this).css("margin-left", "-" + (i + 5) + "px")
		})
		}), $(document).ready(function() {
		function e(e) {
			t.val(parseInt(t.val(), 10) + e)
		}
		$("div.topmenu_theme").removeAttr("style"), $(".owl-addimage").owlCarousel({
			navigation: !0,
			pagination: !1,
			singleItem: !0
			}), $(".owl-addimage-mini").owlCarousel({
			items: 3,
			itemsCustom: !1,
			itemsDesktop: [1199, 3],
			itemsDesktopSmall: [980, 2],
			itemsTablet: [768, 2],
			itemsTabletSmall: !1,
			itemsMobile: [479, 1],
			navigation: !0,
			pagination: !1
			}), $("a.layout-main-img").fancybox({
			transitionIn: "elastic",
			transitionOut: "elastic",
			speedIn: 600,
			speedOut: 200,
			overlayShow: !1
			}), $(".box-category .accordeon_plus").each(function(e, t) {
			1 == $(this).parent().hasClass("cat-active") && ($(this).addClass("open"), $(this).next().addClass("active"))
			}), $(".box-category .accordeon_plus").click(function() {
			0 == $(this).next().is(":visible") && $(".box-category .accordeon_subcat").slideUp(300, function() {
				$(this).removeClass("active"), $(".accordeon_plus").removeClass("open")
				}), 1 == $(this).hasClass("open") ? $(this).next().slideUp(300, function() {
				$(this).removeClass("active"), $(this).prev().removeClass("open")
				}) : $(this).next().slideDown(300, function() {
				$(this).addClass("active"), $(this).prev().addClass("open")
			})
			}), $(".accordeon_description .accordeon_plus").each(function(e, t) {
			1 == $(this).parent().hasClass("cat-active") && ($(this).addClass("open"), $(this).next().addClass("active"))
			}), $(".accordeon_description .accordeon_plus").click(function() {
			0 == $(this).next().is(":visible") && $(".accordeon_description .view").slideUp(300, function() {
				$(this).removeClass("active"), $(".accordeon_plus").removeClass("open")
				}), 1 == $(this).hasClass("open") ? $(this).next().slideUp(300, function() {
				$(this).removeClass("active"), $(this).prev().removeClass("open")
				}) : $(this).next().slideDown(300, function() {
				$(this).addClass("active"), $(this).prev().addClass("open")
			})
		}), $("div").is(".full_container") && $(".main.topmain").css("padding", 0);
		var t = $("#htop");
		$("#increase").click(function() {
			e(1)
			}), $("#decrease").click(function() {
			0 < t.val() && e(-1)
			}), $("body").click(function(e) {
			0 == $(e.target).closest("#notification .success").length && $("#notification .success").remove()
			}), $("#notification > .information > .close").on("click", function() {
			$(".information").hide(), $("#mask-dark").remove()
			}), $("#mask-dark").on("click", function() {
			$(".success, .warning, .attention, .information").remove(), $("#mask-dark").remove()
			}), $(".btn-navbar").click(function() {
			var e = 0;
			$("#navbar-inner").hasClass("navbar-inactive") && 0 == e && ($("#navbar-inner").removeClass("navbar-inactive"), $("#navbar-inner").addClass("navbar-active"), $("#ma-mobilemenu").css("display", "block"), e = 1), $("#navbar-inner").hasClass("navbar-active") && 0 == e && ($("#navbar-inner").removeClass("navbar-active"), $("#navbar-inner").addClass("navbar-inactive"), $("#ma-mobilemenu").css("display", "none"), e = 1)
			}), enquire.register("only screen and (min-width: 1170px)", {
			match: function() {
				$(".full_container").removeClass("fixwidth")
			}
			}).register("only screen and (max-width: 1169px)", {
			match: function() {
				$(".full_container").addClass("fixwidth")
			}
			}), $("#box-facebook .icon-facebook").toggle(function() {
			$("#box-facebook").animate({
				right: "280px"
			}, 500)
			}, function() {
			$("#box-facebook").animate({
				right: "0px"
			}, 500)
			}), $("#box-twitter .icon-twitter").toggle(function() {
			$("#box-twitter").animate({
				right: "280px"
			}, 500)
			}, function() {
			$("#box-twitter").animate({
				right: "0px"
			}, 500)
			}), $("#box-vkt .icon-vkt").toggle(function() {
			$("#box-vkt").animate({
				right: "280px"
			}, 500)
			}, function() {
			$("#box-vkt").animate({
				right: "0px"
			}, 500)
		});
		var n, i = $("#what_to_search1 > .first-flag > img").first().attr("src"),
        a = $("#what_to_search1 > .first-flag").first();
		$(".refine-search-menu1 > li > a").each(function(e, t) {
			if ($(t).find("img").attr("src") == i) {
				var n = $(t).find("img").attr("title");
				$(a).prepend(n), $(t).hasClass("first-flag") || $(t).parent().remove()
			}
			}), (n = jQuery)(document).on("click", "a[href^=#]", function() {
			return n("html, body").animate({
				scrollTop: n('div[id="' + this.hash.slice(1) + '"]').offset().top
			}, 1e3), !1
			}), $("#search-refine-search2").on("click", function() {
			$("#refine-search-menu-link2 > #refine-search-menu2").toggle()
			}), $("#search-refine-search2 #refine-search-menu2").on("mouseleave", function() {
			$("#refine-search-menu-link2 > .refine-search-menu2").hide()
			}), $("#search-refine-search2 #refine-search-menu2 li").on("click", function() {
			$("#what_to_search2").html($(this).html())
			}), $("#search-refine-search3").on("click", function() {
			$("#refine-search-menu-link3 > #refine-search-menu3").toggle()
			}), $("#refine-search-menu-link3 #refine-search-menu3").on("mouseleave", function() {
			$("#refine-search-menu-link3 > .refine-search-menu3").hide()
			}), $("#refine-search-menu-link3 #refine-search-menu3 li").on("click", function() {
			$("#what_to_search3").html($(this).html())
			}), $("#search-refine-search3").on("mouseleave", function() {
			$("#refine-search-menu-link3 > .refine-search-menu3").hide()
			}), $("#search-refine-search2").on("mouseleave", function() {
			$("#refine-search-menu-link2 > .refine-search-menu2").hide()
		})
		}), $(".checker .htop").css({
		"max-width": "36px"
		}), $(document).ready(function() {
		$("input,textarea").focus(function() {
			$(this).data("placeholder", $(this).attr("placeholder")), $(this).attr("placeholder", "")
			}), $("input,textarea").blur(function() {
			$(this).attr("placeholder", $(this).data("placeholder"))
		})
		}), $(".clickme-to-select").click(function(e) {
		return 0 < $(".more-mobile-none").length ? $(".more-mobile-none").toggleClass("more-mobile-block").toggleClass("more-mobile-none") : $(".more-mobile-block").toggleClass("more-mobile-block").toggleClass("more-mobile-none"), e.preventDefault, !1
		}), $(document).ready(function() {
		$(".container").click(function() {
			$("#list_header_viewed").hide
			}), $("#callback-view").on("click", function() {
			var e = {
				closeButton: !1,
				scrolling: !1,
				href: "index.php?route=module/callback",
				width: "95%",
				maxWidth: "675px"
			};
			jQuery.colorbox(e), $(window).resize(function() {
				$.colorbox.resize({
					width: window.innerWidth > parseInt(e.maxWidth) ? e.maxWidth : e.width
				})
			})
			}), $("#refine-search-menu-link").on("click", function() {
			$("#refine-search-menu-link > .refine-search-menu").toggle()
			}), $("#refine-search-menu-link .refine-search-menu").on("mouseleave", function() {
			$("#refine-search-menu-link > .refine-search-menu").hide()
			}), $("#refine-search-menu-link .refine-search-menu li").on("click", function() {
			$("#what_to_search").html($(this).html())
			}), $("#shops").hover(function() {
			$("#refine-search-menu-link1 > .refine-search-menu1").toggle()
			}), $("#refine-search-menu-link1 .refine-search-menu1 li").on("click", function() {
			$("#what_to_search1").html($(this).html())
			}), $("#header_viewed").click(function() {
			$("#list_header_viewed").toggle(), $("#list_header_viewed").on("mouseenter", function() {
				$("#list_header_viewed").on("mouseleave", function() {
					$("#list_header_viewed").hide()
				})
			})
		})
		}), $(document).ready(function() {
		$(".quickview").fancybox({
			type: "iframe",
			autoSize: !1,
			width: "800px",
			height: "600px",
			closeEffect: "elastic",
			afterClose: function() {
				headCart()
			},
			helpers: {
				overlay: {
					locked: !1,
					css: {
						background: "transparent"
					}
				}
			}
		})
		}), $(function() {
		$(".in-product").jScrollPane({
			mouseWheelSpeed: 50
			}), $(".tabscroll").jScrollPane({
			mouseWheelSpeed: 30
			}), $(".tabscroll-two").jScrollPane({
			mouseWheelSpeed: 30
			}), $(".quickviewscroll").jScrollPane({
			mouseWheelSpeed: 30
			}), $("#owl-more-price").owlCarousel({
			items: 3,
			navigation: !0,
			navigationText: ["<i class='icon-chevron-left icon-white'></i>", "<i class='icon-chevron-right icon-white'></i>"],
			pagination: !1
		})
		}), $(document).ready(function() {
		$(".select1").customStyle1()
	});	