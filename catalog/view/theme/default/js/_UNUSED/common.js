$(document).ready(function () {
    /* Search */
    $('.button-search').bind('click', function () {
        url = $('base').attr('href') + 'index.php?route=product/search';

        var search = $('input[name=\'search\']').attr('value');

        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }

        location = url;
    });

    $('#header input[name=\'search\']').bind('keydown', function (e) {
        if (e.keyCode == 13) {
            url = $('base').attr('href') + 'index.php?route=product/search';

            var search = $('input[name=\'search\']').attr('value');

            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }

            location = url;
        }
    });


    /* Ajax Cart */
    var cartover = false;
    $('#cart').live('mouseover', function () {
        if (!cartover) {
            $('#cart').load('index.php?route=module/cart #cart > *');
            $('#cart > .content').slideDown('fast');
            cartover = true;
        }
    });

    $('#cart .content').live('mouseleave', function () {
        $('#cart > .content').hide();
        cartover = false;
    });

    /* Mega Menu */
    $('#menu ul > li.custombox > a + div, .menu li.parent div.topmenu').each(function (index, element) {

        var menu = $('#menu').offset();
        var dropdown = $(this).parent().offset();

        i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

        if (i > 0) {
            $(this).css('margin-left', '-' + (i + 5) + 'px');
        }

    });
    $(window).resize(function () {
        $('#menu ul > li.custombox > a + div, .menu li.parent div.topmenu').each(function (index, element) {
            var menu = $('#menu').offset();
            var dropdown = $(this).parent().offset();

            i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

            if (i > 0) {
                $(this).css('margin-left', '-' + (i + 5) + 'px');
            }
        });
    });

    // IE6 & IE7 Fixes
    if ($.browser.msie) {
        if ($.browser.version <= 6) {
            $('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');

            $('#column-right + #content').css('margin-right', '195px');

            $('.box-category ul li a.active + ul').css('display', 'block');
        }

        if ($.browser.version <= 7) {
            $('#menu > ul > li').bind('mouseover', function () {
                $(this).addClass('active');
            });

            $('#menu > ul > li').bind('mouseout', function () {
                $(this).removeClass('active');
            });
        }
    }

    $('.success img, .warning img, .attention img, .information img').live('click', function () {
        $(this).parent().fadeOut('slow', function () {
            $(this).remove();
        });
    });
});

function getURLVar(key) {
    var value = [];

    var query = String(document.location).split('?');

    if (query[1]) {
        var part = query[1].split('&');

        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');

            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }

        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
}

function addToCart(product_id, quantity) {
    quantity = typeof (quantity) != 'undefined' ? quantity : 1;

    $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: 'product_id=' + product_id + '&quantity=' + quantity,
        dataType: 'json',
        success: function (json) {
            $('.success, .warning, .attention, .information, .error').remove();

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;" >' + json['success'] + '<img src="catalog/view/theme/mattimeo/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#cart-total').html(json['total']);

                //$('html, body').animate({ scrollTop: 0 }, 'slow'); 
            }
        }
    });

}

function addToWishList(product_id) {
    $.ajax({
        url: 'index.php?route=account/wishlist/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function (json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/mattimeo/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#wishlist-total').html(json['total']);

                //$('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        }
    });
}

function addToCompare(product_id) {
    $.ajax({
        url: 'index.php?route=product/compare/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function (json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/mattimeo/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#compare-total').html(json['total']);

                //$('html, body').animate({ scrollTop: 0 }, 'slow'); 
            }
        }
    });
}
