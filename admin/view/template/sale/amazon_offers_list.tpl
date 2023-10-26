<?php if (!empty($offers) || !empty($bad_offers)) { ?>
	<table class="list">
		<thead>
			<tr>
				<td width="30px;">					
				</td>
				<td width="70px;">					
				</td>
				<td width="70px;">					
				</td>
				<td width="70px;">					
				</td>
				<td width="30px;"></td>
				<td width="20px;"></td>
				<td>						
					<b>Продавец</b>
				</td>
				<td>						
					Рейтинг			
				</td>
				<td>						
					Отзывы			
				</td>
				<td>						
					Цена			
				</td>
				<td>						
					Доставка			
				</td>
				<td>						
					Инфо
				</td>
				<td>						
					Дней			
				</td>
				<td>						
					От			
				</td>
				<td>						
					До			
				</td>
				<td>						
					Итог			
				</td>
				<td>						
					Рейтинг			
				</td>
				<td>						
					Обновлено			
				</td>	
				<td>						
					Линк			
				</td>																	
			</tr>
		</thead>
		<?php foreach ($offers as $offer) { ?>
			<tr>
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
				<td class="center" width="30px;">
					<?php if ($offer['country']) { ?>
						<?php echo $offer['country']; ?>
					<? } else { ?>
						<i class="fa fa-times-circle" style="color:#cf4a61"></i>
					<? } ?>
				</td>
				<td class="center" width="30px;">
					<?php if ($offer['is_native']) { ?>
						<i class="fa fa-check-circle" style="color:#51A62D"></i>
					<? } else { ?>
						<i class="fa fa-times-circle" style="color:#cf4a61"></i>
					<? } ?>
				</td>
				<td class="left">						
					<b><?php echo $offer['seller']; ?></b>

					<?php if ($offer['supplier']) { ?>						
						<? if ($offer['supplier']['amzn_good']) { ?>+5
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">повышенный рейтинг</small>
						<?php } elseif ($offer['supplier']['amzn_bad']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">пониженный рейтинг</small>
						<?php } elseif ($offer['supplier']['amzn_coefficient']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">рейтинг <?php echo $offer['supplier']['amzn_coefficient']; ?></small>
						<?php } ?>
					<?php } ?>

					<?php if ($offer['link']) { ?>
						<a href="<?php echo $offer['link']; ?>" target="_blank"><i class="fa fa-external-link-square"></i></a>
					<?php } ?>					

				</td>
				<td class="left">
					<?php if ($offer['rating']) { ?>										
						<?php if ($offer['rating'] > 4.5) { ?>
							<span style="color:#4ea24e"><i class="fa fa-star"></i> <?php echo $offer['rating']; ?></span>
						<?php } else { ?>
							<span style="color:#cf4a61"><i class="fa fa-star"></i> <?php echo $offer['rating']; ?></span>
						<?php } ?>		
					<?php } ?>

				</td>
				<td>
					<?php if ($offer['rating']) { ?>				
						<?php if ($offer['reviews'] > 500) { ?>
							<span style="color:#4ea24e"><i class="fa fa-edit"></i><?php echo $offer['reviews']; ?></span>
						<?php } else { ?>
							<span style="color:#cf4a61"><i class="fa fa-edit"></i><?php echo $offer['reviews']; ?></span>
						<?php } ?>
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
					<?php echo $offer['date_added']; ?>
				</td>

				<td>		
					<a href="<?php echo $offer['link2']; ?>" target="_blank"><i class="fa fa-external-link-square"></i></a>
				</td>
			</tr>
		<?php } ?>

		<?php if (!empty($bad_offers)) { ?>
			<?php foreach ($bad_offers as $bad_offer) { ?>
				<td colspan="6">
					<small style="display:inline-block; padding:3px 5px; color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> bad offer</span> 
					<small style="display:inline-block; padding:3px 5px; color:#cf4a61"><i class="fa fa-exclamation-triangle"></i> <?php echo $bad_offer['bad_reason']; ?></span>
				</td>
				<td class="left" style="color:#cf4a61">						
					<b><?php echo $bad_offer['seller']; ?></b>

					<?php if ($bad_offer['supplier']) { ?>
						
						<? if ($bad_offer['supplier']['amzn_good']) { ?>+5
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">повышенный рейтинг</small>
						<?php } elseif ($offer['supplier']['amzn_bad']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">пониженный рейтинг</small>
						<?php } elseif ($offer['supplier']['amzn_coefficient']) { ?>
							<small class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">рейтинг <?php echo $bad_offer['supplier']['amzn_coefficient']; ?></small>
						<?php } ?>

					<?php } ?>

					<?php if ($bad_offer['link']) { ?>
						<a href="<?php echo $bad_offer['link']; ?>" target="_blank"><i class="fa fa-external-link-square"></i></a>
					<?php } ?>					

				</td>
				<td class="left" style="color:#cf4a61">
					<?php if ($bad_offer['rating']) { ?>										
						<?php if ($bad_offer['rating'] > 4.5) { ?>
							<span style="color:#4ea24e"><i class="fa fa-star"></i> <?php echo $bad_offer['rating']; ?></span>
						<?php } else { ?>
							<span style="color:#cf4a61"><i class="fa fa-star"></i> <?php echo $bad_offer['rating']; ?></span>
						<?php } ?>		
					<?php } ?>

				</td>
				<td style="color:#cf4a61">
					<?php if ($offer['rating']) { ?>				
						<?php if ($offer['reviews'] > 500) { ?>
							<span style="color:#4ea24e"><i class="fa fa-edit"></i><?php echo $bad_offer['reviews']; ?></span>
						<?php } else { ?>
							<span style="color:#cf4a61"><i class="fa fa-edit"></i><?php echo $bad_offer['reviews']; ?></span>
						<?php } ?>
					<?php } ?>
				</td>
				<td class="left" style="white-space:nowrap;color:#cf4a61">						
					<b><?php echo $bad_offer['price']; ?></b>		
				</td>
				<td class="left" style="white-space:nowrap;color:#cf4a61">	

					<?php if ($bad_offer['delivery_fba']) { ?>
						<i class="fa fa-amazon" style="color:#FF9900;"></i>
					<?php } ?>

					<b><?php echo $bad_offer['delivery']; ?></b>			
				</td>

				<td class="left" style="color:#cf4a61">						
					<small><?php echo $bad_offer['delivery_comment']; ?></small>		
				</td>

				<td class="left" style="color:#cf4a61">						
					<small><?php echo $bad_offer['min_days']; ?></small>		
				</td>

				<td class="left" style="color:#cf4a61">						
					<small><?php echo $bad_offer['delivery_from']; ?></small>		
				</td>

				<td class="left" style="color:#cf4a61">						
					<small><?php echo $bad_offer['delivery_to']; ?></small>		
				</td>

				<td class="left" style="white-space:nowrap;color:#cf4a61">						
					<b <?php if ($bad_offer['is_min_price']) { ?>style="color:#4ea24e"<?php } ?>><?php echo $offer['total']; ?></b>
				</td>

				<td class="left" style="white-space:nowrap;color:#cf4a61">						
					<b><?php echo $bad_offer['offer_rating']; ?></b>
				</td>

				<td style="color:#cf4a61">		
					<?php echo $bad_offer['date_added']; ?>
				</td>

				<td style="color:#cf4a61">		
					<a href="<?php echo $bad_offer['link2']; ?>" target="_blank"><i class="fa fa-external-link-square"></i></a>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>

	</table>
	<? } else { ?>
		<div style="color:rgb(207, 74, 97); font-size:28px; text-align:center;"><i class="fa fa-exclamation-triangle"></i><br /> Нет офферов на Amazon</div>
	<?php } ?>