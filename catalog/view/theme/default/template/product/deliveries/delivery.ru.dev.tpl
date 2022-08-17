<script>
function do_notification_block(t,b){
		$(".success, .warning, .attention, .information").remove();
		$('#container').append('<div id="mask-dark"></div>');
		$("#notification").html('<div class="information" id="info_load" style="display: none;"><div class="close"><i class="fa fa-times"></i></div><div id="info_content"></div></div>');
		$("#info_load > #info_content").load("index.php?route=information/information/info2&information_id="+t+"&block="+b);
		$(".information").fadeIn("slow");
		$(document).on("keydown",function(z){27===z.keyCode&&$(".information").fadeOut("slow")});
		
		$('.close').on('click', function () {
		$('.information').fadeOut("slow")
	});
	}
</script>
<style>
.shipping-select{width:49%!important; padding: 0; }
.shipping-select a img{padding-right: 5px}
.shipping-select a i{margin-left: 3px}
.shipping-select a {padding-top: 12px; padding-right: 2px; padding-bottom: 7px; padding-left: 2px; font-size: 12px; height: 52px;}
.stock-status{background-color: #fff; border: 1px solid #DDD; padding: 10px 2px; font-size: 12px; font-weight: 700;text-align: left;}
.stock-status img{ float: left;    padding-left: 8px; padding-right: 3px}
</style>
<div class="col-md-6 col-sm-12 col-xs-24 shipping-select">
<div class="stock-status"><img src="catalog/view/theme/default/images/airplane-flight-around-the-planet.png"><span >Склад Москва (Киев) - </span><span style="color: rgb(76, 102, 0);">доставка/отправка 1-3 дня</span></div>
</div>
<div class="col-md-6 col-sm-12 col-xs-24 shipping-select" style="margin-left: 8px">
<a href="/information#delivery" onclick="do_notification_block(31,'delivery_block');return false;"><img src="catalog/view/theme/default/images/car.png"><span>Варианты доставки</span><i class="fa fa-info-circle"></i></a>
</div>
<div class="col-md-12 col-sm-12 col-xs-24 shipping-info">
    <div class="shipping-block">
        <h4><i class="fa fa-check-circle"></i> Надежная покупка!</h4>
            <p>100% гарантия качества и подлинности товара</p>
    </div>
</div>