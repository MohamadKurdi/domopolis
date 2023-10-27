<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/language.png" alt="" /> <?php echo $heading_title; ?></h1>		
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="50px;"></td>
							<td width="70px;"></td>		
							<td></td>												
							<td width="30px;">Лучший</td>
							<td width="70px;">BuyBox</td>
							<td width="70px;">Prime</td>
							<td width="70px;">Мин цена</td>
							<td>Цена</td>
							<td>Доставка</td>
							<td>Инфо</td>
							<td>Дней</td>
							<td>От</td>
							<td>До</td>
							<td>Итог</td>
							<td>Рейтинг</td>
							<td>Обновлено</td>
							<td></td>											
						</tr>
					</thead>
					<tbody>
						<?php if ($offers) { ?>
							<?php foreach ($offers as $offer) { ?>
								<tr>
									<td style="padding:5px;">
										<?php if ($offer['image']) { ?>
											<img src="<?php echo $offer['image']; ?>" />
										<?php } ?>
									</td>
									<td>
										<b><?php echo $offer['asin']; ?></b>	
									</td>
									<td>
										<?php if ($offer['name']) { ?>
											<?php echo $offer['name']; ?>
										<?php } ?>
									</td>
									<td width="30px;" class="center">
										<?php if ($offer['is_best']) { ?>
											<span style="color:#FF6600"><i class="fa fa-star" style="color:#FF6600; font-size:18px;"></i></span>
										<?php } ?>
									</td>
									<td width="70px;">
										<?php if ($offer['buybox_winner']) { ?>
											<span style="color:#FF6600"><i class="fa fa-star" style="color:#FF6600"></i> BuyBox</span>
										<?php } ?>
									</td>
									<td width="70px;">
										<?php if ($offer['prime']) { ?>
											<span style="color:#009fd5"><i class="fa fa-star" style="color:#009fd5"></i> Prime</span>
										<?php } ?>
									</td>
									<td width="70px;">
										<?php if ($offer['is_min_price']) { ?>
											<span style="color:#4ea24e"><i class="fa fa-sort-amount-asc" style="color:#4ea24e" aria-hidden="true"></i> Цена</span>
										<?php } ?>
									</td>
									<td class="left" style="white-space:nowrap;">						
										<b><?php echo $offer['price']; ?></b>		
									</td>
									<td class="left" style="white-space:nowrap;">	
										<?php if ($offer['delivery_fba']) { ?>
											<i class="fa fa-amazon" style="color:#FF9900;"></i>
										<?php } ?>

										<b><?php echo $offer['delivery']; ?></b>			
									</td>
									<td class="left">						
										<small><?php echo $offer['delivery_comment']; ?></small>		
									</td>

									<td class="left">						
										<small><?php echo !empty($offer['min_days'])?$offer['min_days']:''; ?></small>		
									</td>

									<td class="left">						
										<small><?php echo !empty($offer['delivery_from'])?$offer['delivery_from']:''; ?></small>		
									</td>

									<td class="left">						
										<small><?php echo !empty($offer['delivery_to'])?$offer['delivery_to']:''; ?></small>		
									</td>

									<td class="left" style="white-space:nowrap;">						
										<b <?php if ($offer['is_min_price']) { ?>style="color:#4ea24e"<?php } ?>><?php echo $offer['total']; ?></b>
									</td>
									<td class="left" style="white-space:nowrap;">						
										<b><?php echo $offer['offer_rating']; ?></b>
									</td>

									<td>		
										<small><?php echo $offer['date_added']; ?></small>
									</td>

									<td class="right" width="90px">
										<?php foreach ($offer['action'] as $action) { ?>
											<a class="button" href="<?php echo $action['href']; ?>" <? if (isset($action['target'])) { ?>target="<? echo $action['target'] ?>"<? } ?>><?php echo $action['text']; ?></a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<?php echo $footer; ?>