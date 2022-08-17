<?php echo $simpleheader; ?>


<style>
	
	.go-to-concardis-payment-button{
	background: #51a881;
	position: relative;
	display: flex;
	align-items: center;
	justify-content: center;
	text-align: center;
	color: #fff;
	font-weight: 600;
	width: 100%;
	height: 50px;
	white-space: nowrap;
	overflow: hidden;
	border: 2px solid #51a881;
	transition: background .2s ease,border-color .2s ease;
	}	
	.go-to-concardis-payment-button:hover{
		background: #fff;
	}
</style>

<section id="carousel-brand-logo"  class="brand-top">
	<div class="wrap">
		<div style="clear:both;"></div>
		<div style="margin:0px auto; max-width:375px; text-align:center;">
			<a class="" href="<? echo $url; ?>"><img  style="border-radius:2%!important;"  class="img img-responsive" src="<?php echo $image;?>" /></a>
		</div>
		<div style="margin:20px auto; max-width:375px; text-align:center;">
			<a class="button go-to-concardis-payment-button" href="<? echo $url; ?>">Оплатить заказ #<? echo $order_id; ?></a>
		</div>
	</div>			
</section>



<?php echo $simplefooter; ?>						