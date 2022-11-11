$(document).ready(function () {
    $(".quickview").fancybox({
        type: 'iframe',
        autoSize: false,
        width: '800px',
        height: '600px',
        closeEffect: 'elastic',
        afterClose: function () {
            headCart();
            return;
        },
        helpers: {
            overlay: {
                locked: false,
                css: {
                    "background": 'transparent'
                }
            }
        }
    });
});

function headCart() {
    $('#cart').load('index.php?route=module/cart #cart > *');
    $('#cart-total').load('index.php?route=module/cart #cart > *');
}