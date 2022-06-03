<?php echo $header; ?>
<div id="content">
    <div class="box">
		<div class="heading order_head">
            <div class="buttons" style="float: right; margin-bottom: 5px;">
                <a href="#" class="button" onclick="$('.js-queue-list').slideToggle(300); return false;">Очередь</a>
                <a href="#" class="button" onclick="$('.js-add-to-queue-form').slideToggle(300); return false;">Добавть в очередь</a>
				<div style="clear: both;"></div>
			</div>
		</div>
        <div class="content">
			<div class="js-add-to-queue-form" style="display: none; float: right;">
                <form action="" method="post">
                    <select name="manufacturer_id" style="width: 300px; float: left;">
                        <?php foreach ($manufacturers as $m): ?>
                        <option value="<?=$m['manufacturer_id'] ?>"><?=$m['name'] ?></option>
                        <?php endforeach; ?>
					</select>
                    <input type="submit" name="add_to_queue" class="" value="Добавить в очередь" onclick="return confirm('Вы уверены что ходите добавить этот бренд в очередь для обновления цен?');" style="float: left"/>
				</form>
			</div>
			
            <div class="js-queue-list" style="display: none;">
                <?php if(isset($queue_list) && $queue_list): ?>
                <table class="list" style="width: 50%">
                    <tr>
                        <td>Бренд</td>
                        <td>Добавлено</td>
					</tr>
                    <?php foreach ($queue_list as $item): ?>
					<tr>
						<td><?=$item['name'] ?></td>
						<td><?=date("d.m.Y H:i", strtotime($item['add_date'])); ?></td>
					</tr>
                    <?php endforeach; ?>
				</table>
                <?php else: ?>
				Очередь пуста.
                <?php endif;?>
			</div>
			
			<?php if(isset($add_to_queue_message) && $add_to_queue_message): ?>
			<div class="success">Вы успешно добавили запись в очередь, для обновления цен.</div>
			<?php endif;?>
			
			<form action="" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="15%">Brand</td>
							<td width="10%">Start time</td>
							<td width="30%">Status</td>
							<td width="45%">&nbsp;</td>
						</tr>
					</thead>
					<tbody>
						
						<?php if ($parsers) { ?>
							<?php foreach ($parsers as $id =>  $p): ?>
							<tr>
								<td width="20%">
									<h3>
										<?php if ($p['status'] == 'done'): ?>
										<a href="<?php print $p['url'] ?>"><?=$p['brand_name']; ?></a>
										<?php else: ?>
										<?=$p['brand_name']; ?>
										<?php endif; ?>
										
									</h3>
									<small><?=$id ?></small>
								</td>
								<td width="20%">
									<?=$p['start_time'] ?>
									<br/>
									<?=$p['start_date'] ?>
								</td>
								<?php if($p['process_percent']): ?>
								<td width="20%">
									<h3 style="margin-bottom: 3px;"><?=$p['status'] ?></h3>
									<?php if ($p['status'] != 'done'): ?>
									<br/>
									<?=$p['info'] ?>
									<br/>
									Парсер работае: <?=$p['work_time'] ?>
									<br/>
									Среднее время обработки 1-го товара: <?=$p['info_product'] ?>
									<br/>
									<?php else: ?>
									Парсер отработал за: <?=$p['work_time'] ?>
									<br/>
									Среднее время обработки 1-го товара: <?=$p['info_product'] ?>
									<br/>
									<?php endif; ?>
									<a href="<?=$p['file'] ?>" target="_blank">File</a> <br/><br/>
									</td>
								<td width="40%">
									<?php $color = '#cf4a61'; if ($p['process_percent'] > 25 ) $color = '#e4c25a'; if ($p['process_percent'] > 98 ) $color = '#4ea24e';  ?>
									<div style="padding: 2px; margin: 2px; border: 1px solid <?=$color?>; width: 100%; box-sizing: border-box; border-radius: 2px;">
										<div style="width: <?php echo $p['process_percent'] ?>%; background: <?=$color?>; text-align: center; box-sizing: border-box; padding: 8px;color: white;"><?=$p['process_percent'] ?>%</div>
									</div>
								</td>
								<?php else: ?>
								<td colspan="2"><strong>Данных нет, или произошла системная ошибка.</strong></td>
								<?php endif; ?>
							</tr>
							<?php endforeach; ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
		<? /**<div class="pagination"><?php echo @$pagination; ?></div> */?>
		</div>
		</div>
		</div>
		
		
		<?php echo $footer; ?>			