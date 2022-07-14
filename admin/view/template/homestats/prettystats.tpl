<style>
.tile-footer{font-size:9px!important; }
.tile-heading{font-size:10px!important;}
</style>
<div>
	<div style="width:49%;float: left;">
		<div class="tile">
			<div class="tile-heading online-heading">Сессий покупателей</div>
			<div class="tile-body online-body"><i class="fa fa-user"></i>
				<span style="color:#FFF; font-size:48px; float:right; display:inline-block;" id="customers_online"><? echo $online; ?></span>
			</div>
			<div class="tile-footer online-footer">
				<i class="fa fa-thumbs-up" id="customers_online_fa"></i>
				<span id="customers_online_status">Стабильное количество</span>
			</div>
		</div>
	</div>
	<div style="width:49%;float: right;">
		<div class="tile">
			<div class="tile-heading now-heading">Заказов за месяц</div>
			<div class="tile-body now-body"><i class="fa fa-shopping-bag"></i>
				<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $orders_now; ?></span>
			</div>
			<div class="tile-footer now-footer"><span><? if ($orders_diff > 0) { ?>+<? } ?><? echo $orders_diff; ?> по сравнению с <? echo date('Y', strtotime('-1 year')); ?></span></div>
		</div>
	</div>
	<div class="clr"></div>
</div>


<div>
	<div style="width:49%;float:left;">
		<div class="tile">
			<div class="tile-heading loyal-heading">Лояльных покупателей</div>
			<div class="tile-body loyal-body">
				<i class="fa fa-heart" aria-hidden="true"></i>
			<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $loyal_customers; ?></i></span>
		</div>
		<div class="tile-footer loyal-footer">
			<span><? echo $loyal_customers_diff; ?> с вторым+ заказом с <? echo date('d.m.Y', strtotime('-7 day')); ?></span>
		</div>
	</div>
</div>
<div style="width:49%;float: right;">
	<div class="tile">
		<div class="tile-heading loyal-percent-heading">Повторные за месяц</div>
		<div class="tile-body loyal-percent-body"><i class="fa  fa-heartbeat"></i>
			<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $loyal_orders_percent; ?>%</span>
		</div>
		<div class="tile-footer loyal-percent-footer"><span><? echo $loyal_orders_last_month; ?> из <? echo $orders_now; ?> заказов</span></div>
	</div>
</div>
<div class="clr"></div>
</div>


<div>
	<div style="width:49%;float: right;">
		<div class="tile">
			<div class="tile-heading customers-heading">Активных покупателей</div>
			<div class="tile-body customers-body"><i class="fa fa-users"></i>
				<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $customers_now; ?></span>
			</div>
			<div class="tile-footer customers-footer"><span>+<? echo $customers_diff; ?> за прошлую неделю с <? echo date('d.m.Y', strtotime('-7 day')); ?></span></div>
		</div>
	</div>
	<div style="width:49%;float:left;">
		<div class="tile">
			<div class="tile-heading total-heading">Всего продано</div>
			<div class="tile-body total-body">
				<i class="fa fa-eur"></i>
			<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $order_total; ?></i></span>
		</div>
		<div class="tile-footer total-footer">
			<span>первый оформлен в <? echo $date_first_order; ?></span>
		</div>
	</div>
</div>

<?php if ($this->config->get('config_country_id') == 176) { ?>
<div>
	
	<div style="width:49%;float:left;">
		<div class="tile">
			<div class="tile-heading yandex-total-heading">Заказы YAM, % за месяц</div>
			<div class="tile-body yandex-total-body"><i class="fa fa-yoast"></i>
				<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $yam_percent_for_last_month; ?>%</span>
			</div>
			<div class="tile-footer yandex-total-footer">
				<span>всего <b><? echo $total_yam_orders; ?></b>, за месяц <b><? echo $orders_yam_now; ?></b> из <b><? echo $orders_ru_now; ?></b></span>
			</div>
		</div>
	</div>
	
	<div style="width:49%;float:right;">
		<div class="tile">
			<div class="tile-heading total-heading">Комиссия YAM</div>
			<div class="tile-body total-body"><i class="fa fa-yoast"></i>
				<span style="color:#FFF; font-size:48px; float:right; display:inline-block;">~<? echo $yam_comission; ?></span>
			</div>
			<div class="tile-footer total-footer">
				<span>12%, сумма <? echo $yam_orders_total; ?></span>
			</div>
		</div>
	</div>	
</div>
<?php } ?>

<div>
	
	<div style="width:49%;float:left;">
		<div class="tile">
			<div class="tile-heading pwa-order-heading">Заказы APP/PWA</div>
			<div class="tile-body pwa-order-body"><i class="fa fa-shopping-bag"></i>
				<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $pwa_percent_for_last_month; ?>%</span>
			</div>
			<div class="tile-footer pwa-order-footer">
				<span>всего <b><? echo $total_pwa_orders; ?></b>, за месяц <b><? echo $orders_pwa_now; ?></b></span>
			</div>
		</div>
	</div>
	
	<div style="width:49%;float: right;">
		<div class="tile">
			<div class="tile-heading pwa-heading">Установок PWA (без нативных)</div>
			<div class="tile-body pwa-body"><i class="fa fa-download"></i>
				<span style="color:#FFF; font-size:48px; float:right; display:inline-block;"><? echo $total_installs; ?></span>
			</div>
			<div class="tile-footer pwa-footer">
				<i class="fa fa-thumbs-up" id="pwa_online_fa"></i>
			<span id="pwa_online_status"></span> текущих сессий</div>
		</div>
	</div>
</div>

<div class="clr"></div>
</div>


<script>
	function declension(num, expressions) {
		var result;
		count = num % 100;
		if (count >= 5 && count <= 20) {
			result = expressions['2'];
			} else {
			count = count % 10;
			if (count == 1) {
				result = expressions['0'];
				} else if (count >= 2 && count <= 4) {
				result = expressions['1'];
				} else {
				result = expressions['2'];
			}
		}
		return result;
	}
	
	function updateCustomersPWAOnline(){
		
		$("#pwa_online_status").load("index.php?route=common/home/getCustomersOnlineAppAjax&token=<? echo $token; ?>", function(){});
		
	}
	
	function updateCustomersOnline(){
		var wasCustomers = parseInt($("#customers_online").text());
		$("#customers_online").load("index.php?route=common/home/getCustomersOnlineAjax&token=<? echo $token; ?>", function(){
			var nowCustomers = parseInt($("#customers_online").text());
			var text = '';
			if (wasCustomers > nowCustomers){
				var count = wasCustomers - nowCustomers;
				var text = declension(count, ['Ушел','Ушли','Ушло']) + ' ' + count + ' ' +  declension(count, ['покупатель','покупателя','покупателей']);
				$('#customers_online_fa').removeClass('fa-thumbs-up fa-thumbs-down');
				$('#customers_online_fa').addClass('fa-thumbs-down');
				} else if(wasCustomers < nowCustomers) {
				var count = nowCustomers - wasCustomers;
				var text = declension(count, ['Пришел','Пришли','Пришло']) + ' ' + count + ' ' + declension(count, ['покупатель','покупателя','покупателей']);
				$('#customers_online_fa').removeClass('fa-thumbs-up fa-thumbs-down');
				$('#customers_online_fa').addClass('fa-thumbs-up');
				} else {
				var text = 'Стабильное количество покупателей';
				$('#customers_online_fa').removeClass('fa-thumbs-up fa-thumbs-down');
				$('#customers_online_fa').addClass('fa-thumbs-up');
			}
			$("#customers_online_status").text(text);
		});
	}
	
	$(document).ready(function() {
		setInterval(function(){ updateCustomersOnline(); updateCustomersPWAOnline(); } , 10000);   
	});
	
	updateCustomersPWAOnline();
	updateCustomersOnline();
	</script>				