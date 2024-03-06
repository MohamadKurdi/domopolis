<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
		</div>
		<div class="content">
			<table width="100%">
				<tr class="filter">
					<td align="left" width="245px">
						<p>IP</p>
					<input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" /></td>
					<td align="left">
						<p>Покупатель</p>
					<input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" /></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td align="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
				</tr>
			</table>
			<table class="list">
				<thead>
					<tr>
						<td class="left" width="245px"><?php echo $column_ip; ?></td>
						<td class="left" width="60px">BOT</td>
						<td class="left"><?php echo $column_customer; ?></td>
						<td class="left"><?php echo $column_url; ?></td>
						<td class="left"><?php echo $column_referer; ?></td>
						<td class="left"><?php echo $column_date_added; ?></td>
						<td class="right"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($customers) { ?>
						<?php foreach ($customers as $customer) { ?>
							<tr>
								<td class="left">
								<? if (!empty($customer['ip_geoip_full_info']) && $customer['ip_geoip_full_info']['country_code'] && file_exists(DIR_APPLICATION . '/view/image/flags/' . mb_strtolower($customer['ip_geoip_full_info']['country_code']) . '.png')) { ?>
									<img src="<?php echo DIR_FLAGS_NAME; ?><? echo mb_strtolower($customer['ip_geoip_full_info']['country_code']); ?>.png" title="<? echo mb_strtolower($customer['ip_geoip_full_info']['country_code']) ?>" />
								<? } ?> &nbsp;
								<a href="http://whatismyipaddress.com/ip/<?php echo $customer['ip']; ?>" target="_blank"><?php echo $customer['ip']; ?></a></td>
								<td class="left"><div><?php if ($customer['is_bot']) { ?>
										<i class="fa fa-sitemap" aria-hidden="true" style="font-size:32px; color:rgb(249, 28, 2)"></i>

									<? } else { ?>
									<i class="fa fa-user" aria-hidden="true" style="font-size:32px; color:rgb(0, 84, 179);"></i>

									<? } ?></div></td>
								<td class="left"><?php echo $customer['customer']; ?></td>
								<td class="left"><a href="<?php echo $customer['url']; ?>" target="_blank"><?php echo implode('<br/>', str_split($customer['url'], 30)); ?></a></td>
								<td class="left"><?php if ($customer['referer']) { ?>
									<a href="<?php echo $customer['referer']; ?>" target="_blank"><?php echo implode('<br/>', str_split($customer['referer'], 30)); ?></a>
								<?php } ?></td>
								<td class="left">
									<?php echo $customer['useragent']; ?>
								</td>
								<td class="left"><?php echo $customer['date_added']; ?></td>
								<td class="right"><?php foreach ($customer['action'] as $action) { ?>
									<a class="button" href="<?php echo $action['href']; ?>"><i class="fa fa-pencil-square-o"></i></a>
								<?php } ?></td>            
							</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	function filter() {
		url = 'index.php?route=report/customer_online&token=<?php echo $token; ?>';
		
		var filter_customer = $('input[name=\'filter_customer\']').attr('value');
		
		if (filter_customer) {
			url += '&filter_customer=' + encodeURIComponent(filter_customer);
		}
		
		var filter_ip = $('input[name=\'filter_ip\']').attr('value');
		
		if (filter_ip) {
			url += '&filter_ip=' + encodeURIComponent(filter_ip);
		}
		
		location = url;
	}
//--></script> 
<?php echo $footer; ?>												