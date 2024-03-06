<div style="padding:10px;">
	Наша цена: <b><?=$result['price']; ?></b><br/>
	<?php if($result['special_price']): ?>
	Цена со скидкой: <b><?=$result['special_price']; ?></b>
	<br/>
	<br/>
	<?php endif;?>
	<table class="list" style="border:1px; width:100%;">
		<?php if ($result['suppliers']) { ?>
			<tr>
				<td class='left' style="padding:2px 4px;" colspan="4">Поставщики Европа</td>
			</tr>
			<thead>
				<td class='center'>Поставщик</td>
				<td class='center'>Цена</td>
				<td class='center' style="white-space:nowrap;">Ц. со скидкой</td>
				<td class='center'>Наличие</td>
			</thead>			
			<?php foreach ($result['suppliers'] as $supplier) { ?>
				<tr>
					<td class='left' style="padding:2px 4px;">
						<a href="<?=$supplier['url'] ?>" target="_blank"><?=$supplier['supplier_name'] ?></a>
					</td>
					<?php if (isset($supplier['fail']) && $supplier['fail']) { ?>
						<td colspan="3">Не удалось получить данные</td>
						<?php } else { ?>
						<td class='center' style="padding:2px 4px;">
							<b><? echo $supplier['price_txt']; ?></b>
						</td>
						<td class='center' style="padding:2px 4px;"><b><? echo $supplier['special_txt']?$supplier['special_txt']:'' ?></b></td>
						<td class='center' style="padding:2px 4px;"><? echo $supplier['stock'] ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		<?php } ?>
		
		<?php if ($result['local_suppliers']) { ?>
			<tr>
				<td class='left' style="padding:2px 4px;" colspan="4">Поставщики Локальные</td>
			</tr>
			<thead>
				<td class='center'>Поставщик</td>
				<td class='center'>Цена</td>
				<td class='center' style="white-space:nowrap;">Ц. со скидкой</td>
				<td class='center'>Наличие</td>
			</thead>			
			<?php foreach ($result['local_suppliers'] as $supplier) { ?>
				<tr>
					<td class='left' style="padding:2px 4px;">
						<?=$supplier['supplier_name'] ?>
					</td>
					<?php if (isset($supplier['fail']) && $supplier['fail']) { ?>
						<td colspan="3">Не удалось получить данные</td>
						<?php } else { ?>
						<td class='center' style="padding:2px 4px;">
							<b><? echo $supplier['price_txt']; ?></b>
						</td>
						<td class='center' style="padding:2px 4px;"><b><? echo $supplier['special_txt']?$supplier['special_txt']:'' ?></b></td>
						<td class='center' style="padding:2px 4px;"><? echo $supplier['stock'] ?></td>
					<?php } ?>
				</tr>
				<?php } ?>
			<? } ?>
			
			<? if (!$result['local_suppliers'] && !$result['suppliers']) { ?>
				<tr>
				<td colspan="4">Поставщики не настроены, или невозможно получить информацию.</td>
				</tr>
			<?php } ?>
			</table>
			
			<? /*
				<?php if(isset($result['good']) && $result['good']): ?>
				<span class='success'>Рекомендуется заказывать у: <?=$result['good']['supplier_name'] ?>, по <a href="<?=$result['good']['url'] ?>" target="_blank">ссылке</a></span>
				<?php else: ?>
				<span class='warning'>Не удалось найти лучшего поставщика.</span>
				<?php endif;?>
				<br/><br/>
			*/ ?>
		</div>			