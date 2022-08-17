<?php echo $header; ?>
<?php echo $column_right; ?>
<?php include(dirname(__FILE__).'/../structured/breadcrumbs.tpl'); ?>

<style type="text/css">
	.account_content .head_wrap .title {
		font-size: 22px;
		display: block;
		margin-bottom: 25px;
		font-weight: 500;
		text-align: center;
	}
	.account_content .head_wrap .description {
		font-size: 16px;
		margin-bottom: 10px;
		line-height: 25px;
		position: relative;
	}
	.table-adaptive table{
		width: 100%;
	}
	.btn-copy {
		background-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDc3Ljg2NyA0NzcuODY3IiBzdHlsZT0iZmlsbDojNTFhODgxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik0zNDEuMzMzLDg1LjMzM0g1MS4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJ2MjkwLjEzM2MwLDI4LjI3NywyMi45MjMsNTEuMiw1MS4yLDUxLjJoMjkwLjEzMyAgICBjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjEzNi41MzNDMzkyLjUzMywxMDguMjU2LDM2OS42MSw4NS4zMzMsMzQxLjMzMyw4NS4zMzN6IE0zNTguNCw0MjYuNjY3ICAgIGMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3SDUxLjJjLTkuNDI2LDAtMTcuMDY3LTcuNjQxLTE3LjA2Ny0xNy4wNjdWMTM2LjUzM2MwLTkuNDI2LDcuNjQxLTE3LjA2NywxNy4wNjctMTcuMDY3ICAgIGgyOTAuMTMzYzkuNDI2LDAsMTcuMDY3LDcuNjQxLDE3LjA2NywxNy4wNjdWNDI2LjY2N3oiLz4gIDwvZz48L2c+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik00MjYuNjY3LDBoLTMwNy4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJjMCw5LjQyNiw3LjY0MSwxNy4wNjcsMTcuMDY3LDE3LjA2N1MxMDIuNCw2MC42MjYsMTAyLjQsNTEuMiAgICBzNy42NDEtMTcuMDY3LDE3LjA2Ny0xNy4wNjdoMzA3LjJjOS40MjYsMCwxNy4wNjcsNy42NDEsMTcuMDY3LDE3LjA2N3YzMDcuMmMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3ICAgIHMtMTcuMDY3LDcuNjQxLTE3LjA2NywxNy4wNjdzNy42NDEsMTcuMDY3LDE3LjA2NywxNy4wNjdjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjUxLjIgICAgQzQ3Ny44NjcsMjIuOTIzLDQ1NC45NDQsMCw0MjYuNjY3LDB6Ii8+ICA8L2c+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==);
		width: 22px;
		height: 12px;
		background-color: transparent;
		background-repeat: no-repeat;
		background-size: contain;
		background-position: center;
		display: inline-flex;
		margin-left: 5px;
		cursor: pointer;
		position: relative;
	}
	.btn-copy .tooltiptext{
		display: none;
		width: 116px;
		background-color: black;
		color: #fff;
		text-align: center;
		padding: 9px 6px;
		border-radius: 6px;
		position: absolute;
		z-index: 1;
		font-size: 12px;
		top: -50px;
		left: 0;
	}
	.promo-code-txt-action{
		color: #50a780;
		white-space: nowrap;
		font-weight: 500 !important;
		cursor: pointer;
	}
	.table-adaptive table .promo_tr div{
		display: flex;
		align-items: center;
	}
	.table-adaptive table .promo_tr small{
		color:grey;
		font-size:11px;
		line-height: 15px;
	}
	
	.table-adaptive table .promo_tr .promo-code-txt-action{
		/*color:grey;*/
	}
	.table-adaptive table .date{
		white-space: nowrap;
	}
	.table-adaptive table .description a{
		color: #50a780;
		text-decoration: underline;
	}
	.table-adaptive table thead td{
		font-size: 16px;
		white-space: nowrap;
	}
	.table-adaptive table tbody td{
		font-size: 14px;
		font-weight: 300;
	}
	.product__label-hit{
		display:inline-block; 
		padding:2px 10px; 
		font-weight:700;
	}
	@media screen and (max-width: 560px) {
		.list-transactions {
		    margin: 0;
		}
		.btn-copy .tooltiptext {
		    top: 19px;
		    left: initial;
		    right: 0;
		}
		.list.list-transactions thead{
			display: none
		}
		.list.list-transactions tbody{
			display: flex;
			flex-direction: column;
		}
		.list.list-transactions tbody tr{
			display: flex;
			flex-wrap: wrap;
			border: 1px solid #ccc;
			border-radius: 2px;
			padding: 10px;
			margin-bottom: 10px;

		}
		.list.list-transactions tbody tr:last-child{
			margin-bottom: 0
		}
		.list.list-transactions tbody tr td{
			font-size: 14px;
			border: 0;
			padding: 5px 0;
			gap: 15px;
		}
		.list.list-transactions tbody tr td::before{
			content: attr(data-text)':';
			font-size: 12px;
			color: gray;
		}
		.list.list-transactions tbody tr td.promo_tr,
		.list.list-transactions tbody tr td.date,
		.list.list-transactions tbody tr td.usage{
			display: flex;
			align-items: center;
			flex-basis: 100%;
		}
		.list.list-transactions tbody tr td.promo_tr{
			order: 1;
			flex-direction: column;
			align-items: end;
			justify-content: end;
			padding-left: 75px;
			position: relative;
			gap: 5px;
		}
		.list.list-transactions tbody tr td.promo_tr::before{
			position: absolute;
			left: 0;
			top: 0;
		}
		.list.list-transactions tbody tr td.promo_tr > div{
			text-align: right;
		}
		.list.list-transactions tbody tr td.date{
			order: 2;
			justify-content: space-between;
		}
		.list.list-transactions tbody tr td.usage{
			order: 3;
			justify-content: space-between;
		}
		.list.list-transactions tbody tr td.description{
			order: 4;
			display: flex;
			flex-direction: column;
			gap: 4px;

		}
		.list.list-transactions tbody tr td.description br{
			display: none
		}
		.list.list-transactions tbody tr td.usage .text-success {
			color: #28a745;
			font-size: 14px;
			line-height: 15px;
			display: flex;
			align-items: center;
			text-align: right;
			flex-direction: row-reverse;
		}
		.list.list-transactions tbody tr td.usage .text-success i{
			margin-left: 5px
		}
		
	}
</style>

<section id="content" class="support_wrap account_wrap"><?php echo $content_top; ?>
	<div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
		</div>
		
		<div class="account_content сoupons_wrap">
        	<div class="head_wrap">
        		<p class="description"><?php echo $promocode_text_page_description; ?></p>
			</div>
        	<div class="table-adaptive">
        		<table class="list list-transactions">
        			<thead>
						<tr>
							<td class="left"><?php echo $promocode_text_column_code; ?></td>
							<td class="left"><?php echo $promocode_text_column_date; ?></td>
							<td class="left"><?php echo $promocode_text_column_description; ?></td>
							<td class="left"><?php echo $promocode_text_column_usage; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($promocodes as $promocode) { ?>
							<tr>
								<td class="promo_tr" data-text="<?php echo $promocode_text_column_code; ?>">
									<div>
										<?php if (!empty($promocode['usage']) && $promocode['usage']['type'] == 'limited_and_used') { ?>
											
											<span class="promo-code-txt-action" style="color:grey;"><?php echo $promocode['code']; ?></span>
											
										<?php } else { ?>
										
											<span class="promo-code-txt-action" onclick="copytext(this)" title="скопировать промокод"><?php echo $promocode['code']; ?></span>
											<button class="btn-copy" onclick="copytext('#promo-code-txt-action')" title="скопировать промокод">
												<span class="tooltiptext"><?php echo $promocode_text_copied; ?></span>
											</button>
											
										<? } ?>
																				
									</div>
									<div>
										<small >
											<?php echo $promocode['text_total']; ?>
										</small>
									</div>
								</td>
								<td class="date" data-text="<?php echo $promocode_text_column_date; ?>">
									<span class="date">
										<?php if ($promocode['date_start'] && $promocode['date_end']) { ?>
											<?php echo $promocode['date_start']; ?> - <?php echo $promocode['date_end']; ?>
											<?php } elseif ($promocode['date_start']) {  ?>
											<?php echo $promocode_text_from; ?> <?php echo $promocode['date_start']; ?> 
											<?php } elseif ($promocode['date_end']) {  ?>
											<?php echo $promocode_text_to; ?> <?php echo $promocode['date_end']; ?> 
										<?php } ?>
									</span>
								</td>
								<td class="description" data-text="<?php echo $promocode_text_column_description; ?>">
									<p class="text" hidden>
										<?php echo $promocode['short_description']; ?>
									</p>
									
									<?php if ($promocode['action_caption'] && $promocode['action_href']) { ?>											
										<p class="text">
											<i class="fas fa-info-circle"></i> <?php echo $promocode_text_detailed_action; ?> <a href="<?php echo $promocode['action_href']; ?>" title="<?php echo $promocode['action_caption']; ?>"><?php echo $promocode['action_caption']; ?></a>
										</p>
									<?php } ?>
									
									<?php if (!empty($promocode['label'])) { ?>

										<p class="text">
											<i class="fas fa-info-circle"></i> <?php echo $text_promocode_label_at_products; ?> 
											
											<div class="product__label-hit" style=" --tooltip-color:#<?php echo $promocode['label']['label_background']; ?>; background-color:#<?php echo $promocode['label']['label_background']; ?>; color:#<?php echo $promocode['label']['label_color']; ?>" data-tooltip="<?php echo $promocode['label']['label_text']; ?>"><?php echo $promocode['label']['label']; ?></div>
											
										</p>
									<? } ?>
									
									<?php if (!empty($promocode['categories'])) { ?>

										<p class="text">											
											<i class="fas fa-bars"></i> <?php echo $text_promocode_label_at_categories; ?> 
											
											<ul>
												<?php foreach ($promocode['categories'] as $category) { ?>
													<li>
														<a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
													</li>
												<?php } ?>
											</ul>
										</p>									
									<?php } ?>
									
									<?php if (!empty($promocode['manufacturers'])) { ?>

										<p class="text">											
											<i class="fas fa-tags"></i> <?php echo $text_promocode_label_at_manufacturers; ?> 
											
											<ul>
												<?php foreach ($promocode['manufacturers'] as $manufacturer) { ?>
													<li>
														<a href="<?php echo $manufacturer['href']; ?>" title="<?php echo $manufacturer['name']; ?>"><?php echo $manufacturer['name']; ?></a>
													</li>
												<?php } ?>
											</ul>
										</p>									
									<?php } ?>
									
									<?php if (!empty($promocode['collections'])) { ?>

										<p class="text">											
											<i class="fas fa-tags"></i> <?php echo $text_promocode_label_at_collections; ?> 
											
											<ul>
												<?php foreach ($promocode['collections'] as $collection) { ?>
													<li>
														<a href="<?php echo $collection['href']; ?>" title="<?php echo $collection['name']; ?>"><?php echo $collection['name']; ?></a>
													</li>
												<?php } ?>
											</ul>
										</p>									
									<?php } ?>
									
								</td>
								<td class="usage" data-text="<?php echo $promocode_text_column_usage; ?>">									
									<?php if (!empty($promocode['usage'])) { ?>
										
										<?php if ($promocode['usage']['type'] == 'unlimited') { ?>
											<span class="text-success">
												<i class="fas fa-info-circle"></i> <?php echo $promocode['usage']['text']; ?>
											</span>
										<?php } ?>
										
										<?php if ($promocode['usage']['type'] == 'limited') { ?>
											<span class="text-success">
												<i class="fas fa-info-circle"></i> <?php echo $promocode['usage']['text']; ?>			
											</span>
										<?php } ?>
										
										<?php if ($promocode['usage']['type'] == 'limited_and_used') { ?>
											<span class="text-success">
												<i class="fas fa-info-circle"></i> <?php echo $promocode['usage']['text']; ?>			
											</span><br />
											<span class="text-danger">
												<i class="fas fa-info-circle"></i> <?php echo $promocode['usage']['text2']; ?>			
											</span>
										<?php } ?>
										
										<?php if ($promocode['usage']['type'] == 'limited_and_partly_used') { ?>
											<span class="text-success">
												<i class="fas fa-info-circle"></i> <?php echo $promocode['usage']['text']; ?>			
											</span><br />
											<span class="text-danger">
												<i class="fas fa-info-circle"></i> <?php echo $promocode['usage']['text2']; ?>			
											</span>
										<?php } ?>
																			
									<?php } ?>
									
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>	
			</div>
			<?php echo $content_bottom; ?>
		</div>
	</div>
</div>
</section>

<script>
	function copytext(el) {
		var $tmp = $("<textarea>");
		$("body").append($tmp);
		$tmp.val($(el).text()).select();
		document.execCommand("copy");
		$tmp.remove();
		$(el).closest('.promo_tr').find('.btn-copy').find('.tooltiptext').show();
		setInterval(function(){
			$(el).closest('.promo_tr').find('.btn-copy').find('.tooltiptext').hide();
		}, 1500);
	}		 
</script>
<?php echo $footer; ?>