<?php if (!empty($amazon_offers_histories)) { ?>
	<table class="list">
		<thead>
			<tr>
				<td width="70px;" class="center">Время</td>
				<td width="70px;" class="center"><span style="color:#FF6600">BuyBox</span></td>
				<td width="70px;" class="center"><span style="color:#009fd5">Prime</span></td>
				<td width="70px;" class="center"><span style="color:#4ea24e">Цена</span></td>
				<td width="30px;"></td>
				<td width="20px;"></td>
				<td width="20px;"></td>
				<td><b>Продавец</b></td>				
				<td>Инфо</td>
				<td>Дней</td>
				<td>От-до</td>
				<td>Цена</td>
				<td>Доставка</td>
				<td class="center">Итог</td>
				<td class="center">Себестоимость</td>
				<td class="center">Цена фронт</td>
				<td class="center">Рентабельность</td>																
			</tr>
		</thead>
		<?php foreach ($amazon_offers_histories as $offer) { ?>
			<tr>
				<td width="70px;" class="center">
					<b>	
					<small><?php echo $offer['date_added']; ?></small>
					<br />
					<small><?php echo $offer['time_added']; ?></small>
					</b>
				</td>
				<td width="70px;" class="center">
					<?php if ($offer['offer']['buybox_winner']) { ?>
						<span style="color:#FF6600"><i class="fa fa-star" style="color:#FF6600"></i></span>
					<?php } ?>
				</td>
				<td width="70px;" class="center">
					<?php if ($offer['offer']['prime']) { ?>
						<span style="color:#009fd5"><i class="fa fa-star" style="color:#009fd5"></i></span>
					<?php } ?>
				</td>
				<td width="70px;" class="center">
					<?php if ($offer['offer']['is_min_price']) { ?>
						<span style="color:#4ea24e"><i class="fa fa-sort-amount-asc" style="color:#4ea24e"></i></span>
					<?php } ?>
				</td>
				<td class="center" width="30px;">
					<?php if ($offer['offer']['country']) { ?>
						<?php echo $offer['offer']['country']; ?>
					<? } else { ?>
						<i class="fa fa-times-circle" style="color:#cf4a61"></i>
					<? } ?>
				</td>
				<td class="center" width="30px;">
					<?php if ($offer['offer']['is_native']) { ?>
						<i class="fa fa-check-circle" style="color:#51A62D"></i>
					<? } else { ?>
						<i class="fa fa-times-circle" style="color:#cf4a61"></i>
					<? } ?>
				</td>
				<td class="center" width="30px;">
						<?php if ($offer['offer']['quality']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $offer['offer']['quality']; ?></small>
						<? } else { ?>
							<i class="fa fa-times-circle" style="color:#cf4a61"></i>
						<? } ?>
				</td>
				<td class="left">						
					<b><?php echo $offer['offer']['seller']; ?></b>
					<?php if ($offer['offer']['supplier']) { ?>		
						<a href="<?php echo $offer['offer']['edit_supplier']; ?>"><i class="fa fa-edit"></i></a>

						<? if ($offer['offer']['supplier']['amzn_good']) { ?>+5
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">повышенный рейтинг</small>
						<?php } elseif ($offer['offer']['supplier']['amzn_bad']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">пониженный рейтинг</small>
						<?php } elseif ($offer['offer']['supplier']['amzn_coefficient']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">рейтинг <?php echo $offer['offer']['supplier']['amzn_coefficient']; ?></small>
						<?php } ?>
					<?php } ?>

					<?php if ($offer['offer']['link'] || $offer['offer']['supplier']['store_url']) { ?>
						<div>
						<?php if ($offer['offer']['link']) { ?>						
							<a href="<?php echo $offer['offer']['link']; ?>" target="_blank"><i class="fa fa-external-link-square"></i></a>
						<?php } ?>

						<?php if ($offer['offer']['supplier']['store_link']) { ?>						
							<a href="<?php echo $offer['offer']['supplier']['store_link']; ?>" target="_blank"><i class="fa fa-external-link-square"></i></a>
						<?php } ?>
						</div>
					<?php } ?>					

				</td>
				
				<td class="left">						
					<small><?php echo $offer['offer']['delivery_comment']; ?></small>		
				</td>

				<td class="left">						
					<small><?php echo !empty($offer['offer']['min_days'])?$offer['offer']['min_days']:''; ?></small>		
				</td>

				<td class="left">						
					<small><?php echo !empty($offer['offer']['delivery_from'])?$offer['offer']['delivery_from']:''; ?></small>
					<br />		
					<small><?php echo !empty($offer['offer']['delivery_to'])?$offer['offer']['delivery_to']:''; ?></small>
				</td>

				<td class="left" style="white-space:nowrap;">						
					<b><?php echo $offer['offer']['price']; ?></b>		
				</td>

				<td class="left" style="white-space:nowrap;">	
					<?php if ($offer['offer']['delivery_fba']) { ?>
						<i class="fa fa-amazon" style="color:#FF9900;"></i>
					<?php } ?>
					<b><?php echo $offer['offer']['delivery']; ?></b>			
				</td>

				<td class="center" style="white-space:nowrap;">						
					 <span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $offer['offer']['total']; ?></span>
				</td>

				<td class="center" style="white-space:nowrap;">						
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF"><?php echo $offer['costprice']; ?></span>
				</td>

				<td class="center" style="white-space:nowrap;">						
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF; color:#FFF"><?php echo $offer['price']; ?></span>
				</td>

				<td class="center" style="white-space:nowrap;">						
					<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000; color:#FFF"><?php echo $offer['profitability']; ?>%</span>
				</td>
			
			</tr>
		<?php } ?>
	</table>
	<? } else { ?>
		<div style="color:rgb(207, 74, 97); font-size:18px; text-align:center;"><i class="fa fa-exclamation-triangle"></i> Нет истории ценообразования</div>
	<?php } ?>