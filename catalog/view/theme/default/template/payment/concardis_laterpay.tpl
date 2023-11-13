<?php echo $simpleheader; ?>
<section id="carousel-brand-logo"  class="brand-top">
	<div class="wrap">
		<div style="clear:both;"></div>
		<div style="margin:20px auto; width:400px; text-align:center;">
			<h2>Оплата заказа #<? echo $order_id; ?></h2>
		</div>
		<div style="text-align:center; margin-top:10px; width:400px; margin:auto;">
			<div id="widget-container" style="width:400px; height:700px; overflow:hidden; text-align: center;padding-top: 10px;">
			</div>
		</div>
	</div>			
</section>

		
<script src="https://pp.payengine.de/widgetjs/payengine.widget.min.js"></script>
<script>
	var widgetReference;
	
	function initCallback(error, result) {
		if (error) {
			console.log(error);
			return;
		}
		console.log(result);
		widgetReference = result;
	}
	
	function resultCallback(error, result) {
		if (error) {
			console.log(error);
			return;
			} else {
			console.log(result);
		}
	}
	
	window.onload = function() {
		var container = document.getElementById("widget-container");
		var publishableKey = "<? echo $merchant; ?>";
		var orderId = "<? echo $cc_order_id; ?>";
		var optionalParameters = new Object;
		optionalParameters.initCallback = initCallback;
		optionalParameters.products = ["creditcard"];
		optionalParameters.language = "en";
		optionalParameters.hidePayButton = false;
		optionalParameters.redirectUser = true;
		optionalParameters.hideTitleIcons = false;
		PayEngineWidget.initAsInlineComponent(
		container,
		publishableKey,
		orderId,
		optionalParameters,
		resultCallback
		);
	}
</script>

<?php echo $simplefooter; ?>						