<script>
function do_notification_block(t,b){
		$(".success, .warning, .attention, .information").remove();
		$('#container').append('<div id="mask-dark"></div>');
		$("#notification").html('<div class="information" id="info_load" style="display: none;"><div class="close"><i class="fa fa-times"></i></div><div id="info_content"></div></div>');
		$("#info_load > #info_content").load("index.php?route=information/information/info_block&information_id="+t+"&block="+b);
		$(".information").fadeIn("slow");
		$(document).on("keydown",function(z){27===z.keyCode&&$(".information").fadeOut("slow")});
		
		$('.close').on('click', function () {
		$('.information').fadeOut("slow")
	});
	}
</script>
<div class="col-md-12 col-sm-12 col-xs-24 shipping-select">
<a href="/information#delivery" onclick="do_notification_block(31,'delivery_block');return false;"><img src="catalog/view/theme/kitchde/images/car.png">Liefermöglichkeiten <i class="fa fa-info-circle"></i></a>
</div>
<div class="col-md-12 col-sm-12 col-xs-24 shipping-info">
    <div class="shipping-block">
        <h4><i class="fa fa-check-circle"></i> Ein sicherer Ankauf!</h4>
            <p>100% Qualitätsgarantie und Produktauthentizität</p>
    </div>
</div>