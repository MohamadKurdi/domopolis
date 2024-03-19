<?php echo $header; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<style>
.ui-dialog.ui-corner-all{
padding: 10px 10px;
box-shadow: 1px 1px 12px #9d9d9d69;
border: 1px solid #e0e0e0;
border-radius: 4px;
}
.ui-dialog.ui-corner-all > .ui-draggable-handle{
font-size: 17px;
display: flex;
align-items: center;
justify-content: space-between;
margin-bottom: 10px;
font-weight: 500;
border-bottom: 1px solid #e0e0e0;
padding-bottom: 10px;
}
.ui-dialog.ui-corner-all > .ui-draggable-handle button{

width: 25px;
height: 25px;
line-height: 25px;
color: #2121217d;
cursor: pointer;
background-image: url(/catalog/view/theme/default/img/close-modal.svg);
background-size: 11px 11px;
background-repeat: no-repeat;
border: 1px solid #000;
border-radius: 50px;
text-align: center;
background-position: center;
opacity: .5;
background-color: #fff;
font-size: 0;
}
#ttninfo{
border: 0;
}
#ttninfo table{
width: 100%;
}
#ttninfo td {
padding: 2px 5px;
}
#ttninfo tr{
transition: .15s ease-in-out;
}
#ttninfo tr:hover{
background: #f7f4f4;
}
.tracker-order-info{
border-top: 1px solid rgba(0,0,0,0.08);
border-left: 1px solid rgba(0,0,0,0.08);
margin-bottom: 20px;
background: #fff;
overflow-x: auto;
display: block;
}

.tracker-order-info-padd{
padding:10px;
font-size:14px;

}
.tracker-order-info-padd ul{
justify-content: flex-start !important;
}
.tracker-order-info-padd ul li{
width: 200px !important;
font-size:14px !important;
}

.tracker-order-info ul{
padding-left:5px;
padding-right:5px;
display: flex;
justify-content: space-between;
}

.tracker-order-info ul li{
display: inline-block;
width: 150px;
min-width: 100px;
margin: 0;
vertical-align: top;
text-align: center;
padding: 20px 0 0;
font-size: 11px;
line-height: 15px;
}

.tracker-order-info ul li i{
font-size:36px !important;
margin-bottom: 5px;
}

.product-tracker-info{
border:0px;
margin-bottom:5px;
}

.product-tracker-info ul{
margin:0px;
margin-top:5px;
}

.product-tracker-info ul li{
width:120px !important;
}

.product-tracker-info ul li i{
font-size:24px !important;	
}

.product-tracker-info ul li{		
}

.tracker-order-info ul li.undone{
color:#7F7F7F;
}

.tracker-order-info ul li.done{
color:#7cc04b;
}
@media screen and (max-width: 568px){
.tracker-order-info ul li{
min-width: 100px;
}
}
</style>
<div id="content" class="order-history-page account_wrap">
	<div class="wrap two_column">
		<div class="side_bar">
            <?php echo $column_left; ?>
        </div>
        <div class="account_content">
			<?php echo $content_top; ?>


			<? 
				$ct = 'Москве';
				if ($full_order_info['shipping_country_id'] == 220){
					$ct = 'Києві';
					} elseif($full_order_info['shipping_country_id'] == 176) {
					$ct = 'Москве';
					} elseif($full_order_info['shipping_country_id'] == 109) {
					$ct = 'Москве';
					} elseif($full_order_info['shipping_country_id'] == 20) {
					$ct = 'Минске';
				}
			?>		
			
			<? if ($general_tracker_status) { ?>
				<div class="tracker-order-info">		
					<ul>
						<li class="done">
							<i class="fa fa-map-marker" ></i>
							<br /><?php echo $text_tracker_1; ?>
						</li>
						<li class="<? if (in_array('first_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
							<i class="fa fa-spinner" ></i>
							<br /><?php echo $text_tracker_2; ?>
						</li>
						<li class="<? if (in_array('second_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
							<i class="fa fa-check" ></i>
							<br /><?php echo $text_tracker_3; ?>
						</li>
						<li class="<? if (in_array('third_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
							<i class="fas fa-chart-pie" ></i>
							<br /><?php echo $text_tracker_4; ?>
						</li>
						<li class="<? if (in_array('fourth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
							<i class="fa fa-truck" ></i>
							<br /><?php echo $text_tracker_5; ?> <? echo $full_order_info['shipping_country'] ?>
						</li>
						<li class="<? if (in_array('fifth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">				
							<i class="fa fa-clock" ></i>
							<br /><?php echo $text_tracker_6; ?> <? echo $ct; ?>
							<br /><?php echo $text_tracker_7; ?>
						</li>
						
						<? if ($is_on_pickpoint) { ?>	
							<li class="<? if (in_array('sixth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
								<i class="fa fa-car" ></i>
								<br /><?php echo $text_tracker_8; ?>
							</li>
							<? } else { ?>
							<li class="<? if (in_array('sixth_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
								<i class="fa fa-car" ></i>
								<br /><?php echo $text_tracker_9; ?>
							</li>
							
						<? } ?>
						
						<? if (!$is_on_pickpoint) { ?>	
							<li class="<? if (in_array('seventh_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
								<i class="fa fa-check-circle" ></i>
								<br /><?php echo $text_tracker_10; ?>								
							</li>
							<? } else { ?>
							<li class="<? if (in_array('seventh_step', $general_tracker_status)) { ?>done<? } else { ?>undone<? } ?>">
								<i class="fa fa-check-circle" ></i>
								<br /><?php echo $text_tracker_11; ?>	
							</li>
							
						<? } ?>
						
					</ul>
				</div>
			<? } ?>
			
			<? if ($full_order_info['date_delivery_actual'] != '0000-00-00' || $full_order_info['date_delivery_to'] != '0000-00-00' || $full_order_info['wait_full'] || $manager_set) { ?>	
				<div class="tracker-order-info tracker-order-info-padd">		
					<ul class="tracker-order-info-padd-ul">
						
						<? if ($manager && $manager_set) { ?>
							<li class="undone">
								<i class="fa fa-user"></i><br /><?php echo $text_tracker_12; ?> <?php echo $manager; ?>
							</li>
						<? } ?>
						
						
						<? if ($full_order_info['display_date_in_account'] && ($full_order_info['date_delivery_actual'] != '0000-00-00' || $full_order_info['date_delivery_to'] != '0000-00-00')) { ?>	
							<li class="undone">
								<i class="fa fa-car" ></i><br /> <?php echo $text_tracker_13; ?> 
								<? if ($full_order_info['date_delivery_actual'] != '0000-00-00') { ?>
									<? echo date('d.m.Y', strtotime($full_order_info['date_delivery_actual'])) ?>
									<? } elseif ($full_order_info['date_delivery_to'] != '0000-00-00') { ?>
									- <? echo date('d.m.Y', strtotime($full_order_info['date_delivery_to'])) ?>
								<? } ?>
							</li>
						<? } ?>
						
						<? if ($full_order_info['wait_full']) { ?>	
							<li class="undone">
								<i class="fa fa-th-list" ></i><br />
								<?php echo $text_tracker_14; ?>
							</li>
						<? } ?>
						
						<? if ($is_full_paid) { ?>	
							<li class="done">
								<i class="fa fa-check-circle" ></i><br />
								<?php echo $text_tracker_15; ?>	
							</li>
						<? } ?>
						
						<? if ($full_order_info['urgent']) { ?>	
							<li class="undone">
								<i class="fa fa-space-shuttle" ></i><br />
								<?php echo $text_tracker_16; ?>	
							</li>
						<? } ?>
						
						<? if ($full_order_info['preorder']) { ?>	
							<li class="undone">
								<i class="fa fa-space-shuttle" ></i><br />
								<?php echo $text_tracker_17; ?>	
							</li>
						<? } ?>
					</ul>
				</div>
			<? } ?>
			<div class="table-adaptive">
				<table class="list list-order-info">
					<thead>
						<tr>
							<td class="left" colspan="2"><?php echo $text_order_detail; ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="left" style="width: 50%;"><?php if ($invoice_no) { ?>
								<b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
							<?php } ?>
							<b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
							<b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
							<td class="left" style="width: 50%;"><?php if ($payment_method) { ?>
								<b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br /> 
							<?php } ?>
							<?php if ($shipping_method) { ?>
								<b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
							<?php } ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="table-adaptive">
				<table class="list list-order-info">
					<thead>
						<tr>
							<td class="left"><?php echo $text_payment_address; ?></td>
							<?php if ($shipping_address) { ?>
								<td class="left"><?php echo $text_shipping_address; ?></td>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="left"><?php echo $payment_address; ?></td>
							<?php if ($shipping_address) { ?>
								<td class="left"><?php echo $shipping_address; ?></td>
							<?php } ?>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="table-adaptive">
				<table class="list list-order-info">
					<thead>
						<tr>
							<td class="left"></td>
							<td class="left"><?php echo $column_name; ?></td>
							<td class="right"><?php echo $column_quantity; ?></td>
							<td class="right"><?php echo $column_price; ?></td>
							<td class="right" style="whitespace:nowrap;"><?php echo $column_total; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($products as $product) { ?>
							<tr>
								<td class="center"><img src="<?php echo $product['image']; ?>" /></td>
								<td class="left">
									<a href="<?php echo $product['link']; ?>" target="_blank" title="<?php echo $product['name']; ?>"><b><?php echo $product['name']; ?></b></a>
									<?php foreach ($product['option'] as $option) { ?>
										<br />
										&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
									<?php } ?>
									<br /><?php echo $product['model']; ?>								
								</td>
								<td class="right"><?php echo $product['quantity']; ?></td>
								
								<td class="right">
									<?php if ($full_order_info['preorder'] && $product['price_isnull']) { ?>
										<?php echo $text_tracker_18; ?>	
										<?php } else { ?>
										<?php echo $product['price']; ?>
									<?php } ?>
								</td>
								
								<td class="right" style="whitespace:nowrap;">
									<?php if ($full_order_info['preorder'] && $product['price_isnull']) { ?>
										<?php echo $text_tracker_18; ?>	
										<?php } else { ?>
										<b><?php echo $product['total']; ?></b>
									<?php } ?>
								</td>   
							</tr>
						<?php } ?>
						<?php foreach ($vouchers as $voucher) { ?>
							<tr>
								<td class="left"><?php echo $voucher['description']; ?></td>
								<td class="left"></td>
								<td class="right">1</td>
								<td class="right"><?php echo $voucher['amount']; ?></td>
								<td class="right"><?php echo $voucher['amount']; ?></td>
								<?php if ($products) { ?>
									<td></td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<?php foreach ($totals as $total) { ?>
							<tr>
								<td colspan="2" align="right" style="padding-top:3px; padding-bottom:3px;">
									<style>
										.buttons {margin-bottom:0px;}
										table.pay tr td{border:0px;}
									</style>
									<? if ($total['code'] == 'total') { ?>
										
										<?php if (!empty($pp_express_onpay) && $pay_equirePP) { ?>
											<? if ($currency_code == 'RUB') { ?>
												<a style="text-transform:none; text-align:center; float:right; margin-left:10px; max-width:200px;" onclick="location='<?php echo $pp_express_onpay; ?>'" class="button">
													<div style="padding:4px 8px; margin-top:4px; display:inline-block;"><img src="catalog/view/image/payment/pp2_visa_mc_s.png" height="30px" title="Payment methods" alt="Payment methods"/></div><br />
												<?php echo $text_tracker_19; ?>	<?php echo $total['text']; ?> <?php echo $text_tracker_10; ?>	</a>
											<? } ?>
										<? } ?>
										
										<?php if (!empty($liqpay_onpay) && $pay_equireLQP) { ?>
											<? if ($currency_code == 'UAH') { ?>
												<a style="text-transform:none; text-align:center; float:right; margin-left:10px; max-width:200px;" onclick="location='<?php echo $liqpay_onpay; ?>'" class="button">
													<div style="padding:4px 8px; margin-top:4px; display:inline-block;"><img src="catalog/view/image/payment/liqpay_payment_s.png" title="Payment methods" alt="Payment methods" height="30px"/></div><br />
													<?php echo $text_tracker_19; ?> <?php echo $total['text']; ?> через LiqPay
												</a>
											<? } ?>
										<? } ?>
										
										<?php if (!empty($concardis_onpay_cc_eur) && $pay_equireCP) { ?>
											<a style="text-transform:none; text-align:center; float:right; margin-left:10px; border:1px solid #E0E0E0; border-radius:5px; background:#FFF; max-width:200px;" onclick="location='<?php echo $concardis_onpay_cc_eur; ?>'" class="button">
												<div style="padding:4px 8px; margin-top:4px; display:inline-block;"><img src="catalog/view/image/payment/concardis-1.png" title="Payment methods" alt="Payment methods" height="30px"/></div><br />
												<?php echo $text_tracker_19; ?> <?php echo $order_to_pay_cc_eur; ?> через Concardis Payengine
											</a>
										<? } ?>
										
										<?php if (!empty($concardis_onpay) && $pay_equireCP) { ?>
											<a style="text-transform:none; text-align:center; float:right; margin-left:10px; border:1px solid #E0E0E0; border-radius:5px; background:#FFF; max-width:200px;" onclick="location='<?php echo $concardis_onpay; ?>'" class="button">
												<div style="padding:4px 8px; margin-top:4px; display:inline-block;"><img src="catalog/view/image/payment/concardis-1.png" title="Payment methods" alt="Payment methods" height="30px"/></div><br />
												<?php echo $text_tracker_19; ?> <?php echo $order_to_pay; ?> через Concardis Payengine
											</a>
										<? } ?>
										
										<?php if (!empty($paykeeper_onpay) && $pay_equire) { ?>
											<? if ($currency_code == 'RUB') { ?>
												<a style="text-transform:none; text-align:center; float:right; margin-left:10px; max-width:200px;" onclick="location='<?php echo $paykeeper_onpay; ?>'" class="button">
													<div style="padding:4px 8px; margin-top:4px; display:inline-block;"><img src="catalog/view/image/payment/PayKeeper_s.png" title="Payment methods" alt="Payment methods"/><img src="catalog/view/theme/default/image/payment/shoputils_psb/visa_PNG39.png" height="30px" title="Payment methods" alt="Payment methods"/></div><br />
												<?php echo $text_tracker_19; ?> <?php echo $order_to_pay; ?> <?php echo $text_tracker_20; ?></a>
												<? } else { ?>
												<a style="text-transform:none; text-align:center; max-width:200px;" onclick="location='<?php echo $paykeeper_onpay; ?>'" class="button">
													<div style="padding:4px 8px; margin-top:4px; display:inline-block;"><img src="catalog/view/image/payment/PayKeeper_s.png" title="Payment methods" alt="Payment methods"/><img src="catalog/view/theme/default/image/payment/shoputils_psb/visa_PNG39.png" height="30px" title="Payment methods" alt="Payment methods"/></div><br />
													<?php echo $text_tracker_19; ?> <?php echo $total['text']; ?> (<? echo $order_to_pay; ?>) <?php echo $text_tracker_20; ?>	
												</a>
											<? } ?>
										<? } ?>
										
										
									<? } ?>
								</td>
								<td class="right" colspan="2"><b><?php echo $total['title']; ?></b></td>
								<td class="right" style="whitespace:nowrap;"><b><?php echo $total['text']; ?></b></td>
							</tr>
						<?php } ?>
						<? /* if ($currency_code != 'RUB' && $currency_code != 'UAH') { ?>
							<tr>
								<td colspan='6' style="padding-top:5px;padding-bottom:5px;text-align:center;">
									<? 
										$a1 = array(
										'BYN' => 'белорусских рублях',
										'UAH' => 'українських гривнях',
										'KZT' => 'казахстанских тенге'
										);
										
									?>
									<span style="font-weight:700;">Оплата выполняется в <? echo $a1[$currency_code] ?> с конвертацией через рубль по курсу: <? echo $my_currency; ?> = <? echo $currency_cource; ?></span>
								</td>
							</tr>
						<? } */ ?>
					</tfoot>
				</table>
			</div>
			<?php if ($comment) { ?>
				<div class="table-adaptive">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><?php echo $text_comment; ?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="left"><?php echo $comment; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			<?php } ?>
			<?php if ($histories) { ?>
				<h2 style="margin-bottom:4px;"><?php echo $text_history; ?></h2>
				<div class="table-adaptive">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><?php echo $column_date_added; ?></td>
								<td class="left"><?php echo $column_status; ?></td>
								<td class="left"><?php echo $column_comment; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($histories as $history) { ?>
								<tr>
									<td class="left"><?php echo $history['date_added']; ?></td>
									<td class="left"><?php echo $history['status']; ?></td>
									<td class="left"><?php echo $history['comment']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
			
			<?php if ($transactions) { ?>
				<h2 style="margin-bottom:4px;"><?php echo $text_tracker_21; ?></h2>
				<div class="table-adaptive">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><?php echo $column_date_added; ?></td>
								<td class="left"><?php echo $column_description; ?></td>
								<td class="right"><?php echo $column_amount; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transactions  as $transaction) { ?>
								<tr>
									<td class="left"><?php echo $transaction['date_added']; ?></td>
									<td class="left"><?php echo $transaction['description']; ?></td>
									<td class="right"><?php echo $transaction['amount']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<? } ?>
			
			<? if ($ttns) { ?>
				<style>
					span.get_ttn_info {cursor:pointer; display:inline-block; border-bottom:1px dashed black;}
				</style>
				<h2 style="margin-bottom:4px;"><?php echo $text_tracker_22; ?></h2>
				<span class="help" style="display:inline-block; margin-bottom:4px;"><?php echo $text_tracker_23; ?></span>
				<div class="table-adaptive">
					<table class="list">
						<thead>
							<tr>
								<td class="left"><?php echo $text_tracker_24; ?></td>
								<td class="left"><?php echo $text_tracker_25; ?></td>
								<td class="left"><?php echo $text_tracker_26; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($ttns as $ttn) { ?>
								<tr data-ttn-id="<?php echo $ttn['order_ttn_id']; ?>">
									<td class="left"><?php echo $ttn['date_ttn']; ?></td>
									<td class="left"><?php echo $ttn['delivery_company']; ?></td>
									<td class="left"><span class="get_ttn_info" data-ttn="<?php echo $ttn['ttn']; ?>" data-delivery-code="<?php echo $ttn['delivery_code']; ?>"><?php echo $ttn['ttn']; ?></span>&nbsp;&nbsp;<span style="display:none;"></span></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>  
				</div>
			<? } ?>
			<div class="buttons">
				<div class="right"><a href="<?php echo $continue; ?>" class="button btn btn-acaunt"><?php echo $button_continue; ?></a></div>
				<?php if(isset($download_invoice)){ ?><div class="left"><a href="<?php echo $download_invoice; ?>" target="_blank" class="button"><?php echo $button_invoice; ?></a></div><?php } ?>
			</div>
			<?php echo $content_bottom; ?>
		</div>
	</div>
</div>
<script>$('.get_ttn_info').click(function(){
	var span = $(this);
	span.next().html('<i class="fa fa-spinner fa-spin"></i>');
	span.next().show();
	var ttn = span.attr('data-ttn');
	var code = span.attr('data-delivery-code');
	$('#ttninfo').load(
	'index.php?route=account/order/ttninfoajax',
	{
		ttn : ttn,
		delivery_code : code
	}, 
	function(){
		span.next().hide();
		$(this).dialog({width:900, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: '<?php echo $text_tracker_27; ?> '+ttn}); 
	});
});</script>
<div id="ttninfo"></div>
<?php echo $footer; ?> 	