<?php if ($delivery_dates) { ?>													
	<div>
		<i class="fas fa-truck-loading" style="color:#51a881"></i> <?php echo $delivery_text; ?> <span class="delivery__city" id="change-city-product-city-name"><?php echo $delivery_city['city']; ?></span> <span class="delivery__dates" ><?php echo $delivery_dates['start']; ?> - <?php echo $delivery_dates['end']; ?></span>
	</div>
	
	<?php /*СДЭК*/ if (!empty($cdek_delivery_dates)) { ?>
		<div style="margin-top:5px;">
			<i class="fas fa-truck" style="color:#51a881"></i> <?php echo $delivery_to_city_cs_ru; ?> <?php echo $delivery_city['city']; ?> <span class="delivery__dates"><?php echo $cdek_delivery_dates['start']; ?> - <?php echo $cdek_delivery_dates['end']; ?></span>
		</div>
	<?php } ?>
	
	<?php /*НП*/ if (!empty($np_delivery_dates)) { ?>
		<div style="margin-top:5px;">
			<i class="fas fa-truck" style="color:#51a881"></i> <?php echo $delivery_to_city_cs_ua; ?> <?php echo $delivery_city['city']; ?> <span class="delivery__dates"><?php echo $np_delivery_dates['start']; ?> - <?php echo $np_delivery_dates['end']; ?></span>
		</div>
	<?php } ?>
	
	<?php /*Самовывоз*/ if (!empty($pickup_text)) { ?>
		<div style="margin-top:5px;">
			<i class="fas fa-box-open" style="color:#51a881"></i> <?php echo $pickup_text; ?></span>
		</div>
	<?php } ?>
<?php } ?>