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
            <h1><img src="view/image/actions.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a>
                <a onclick="location = '<?php echo $setting; ?>'" class="button"><span><?php echo $button_setting; ?></span></a>
                <a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
			</div>
		</div>
        <div class="content">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="list">
                    <thead>
                        <tr>
                            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                            <td class="center"><?php if ($sort == 'n.date_start') { ?>
                                <a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
							<?php } ?></td>
                            <td class="center"><?php echo $column_date_end; ?></td>
														<td class="center"></td>
                            <td class="left"><?php if ($sort == 'nd.caption') { ?>
                                <a href="<?php echo $sort_caption; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_caption; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_caption; ?>"><?php echo $column_caption; ?></a>
							<?php } ?></td>
							<td class="center">Промокод</td>
							<td class="center">Лейбл</td>
							<td class="center">Список всех</td>
							<td class="center">Только нал</td>
                            <td class="center">Уд. наличие</td>
                            <td class="center">Бренд</td>
                            <td class="center">Категории</td>
                            <td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
                    <tbody>
                        <?php if ($actionss) { ?>
                            <?php foreach ($actionss as $actions) { ?>
                                <?php 
                                    if(!$actions['status']) { 
                                        $style = 'style="color:grey;"';
                                        } else {
                                        $style = '';
									}
								?>
                                <tr>
                                    <td style="text-align: center;"><?php if ($actions['selected']) { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $actions['actions_id']; ?>" checked="checked" />
                                        <?php } else { ?>
                                        <input type="checkbox" name="selected[]" value="<?php echo $actions['actions_id']; ?>" />
									<?php } ?></td>
                                    <td class="center" <?php echo $style;?> width="60" style="font-size:10px;">
										<?php echo $actions['date_start']; ?><br /><?php echo $actions['time_start']; ?>
									</td>
                                    <td class="center" <?php echo $style;?> width="60" style="font-size:10px;">
										<?php echo $actions['date_end']; ?><br /><?php echo $actions['time_end']; ?>
									</td>
									
																		
									<td class="left">
										<img src="<?php echo $actions['image']; ?>" />
									</td>	
									
                                    
									<td class="left" <?php echo $style;?>>				
										<a <?php if(!$actions['status'])echo ' style="color:#999999;"';?> href="<?php echo $actions['href']; ?>"><?php echo $actions['caption']; ?></a> 
										
										<?php if ($actions['anonnce']) { ?>
											<br />
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#7F00FF;color:#FFF ; font-size:10px;">
												Анонс: <?php echo $actions['anonnce']; ?>
											</span>
										<? } ?>
										
										<?php if ($actions['label_text']) { ?>
											<br />
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000;color:#FFF; font-size:10px;">
												Подсказка: <?php echo $actions['label_text']; ?>
											</span>
										<? } ?>
									</td>

									<td class="left">
										<? if ($actions['coupon']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#000;color:#FFF"><?php echo $actions['coupon']; ?></span>
										<? } ?>
									</td>	
									
									<td class="left">
										<? if ($actions['label']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#<?php echo $actions['label_background']; ?>; color:#<?php echo $actions['label_color']; ?>"><?php echo $actions['label']; ?></span>
										<? } ?>
									</td>	
									
									<td class="left">
										<? if ($actions['display_all_active']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
											<? } else { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
										<? } ?>
									</td>	
									
									<td class="left">
										<? if ($actions['only_in_stock']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
											<? } else { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
										<? } ?>
									</td>
									
									<td class="left">
										<? if ($actions['deletenotinstock']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF">Да</span>
											<? } else { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
										<? } ?>
									</td>
									<td class="center">
										<?php if ($actions['manufacturer']) { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#4ea24e; color:#FFF"><?php echo $actions['manufacturer']; ?></span>
											<?php } else { ?>
											<span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Нет</span>
										<?php } ?>
										
									</td>
									<td class="center">
										<?php foreach ($actions['categories'] as $category) { ?>
											<small><?php echo $category['name']; ?></small><br />
										<?php } ?>	
									</td>
									<td class="right">
										<?php foreach ($actions['action'] as $action) { ?>
											<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> 
											<?php } ?>
											</td>
											</tr>
										<?php } ?>
										<?php } else { ?>
										<tr>
											<td class="center" colspan="5"><?php echo $text_no_results; ?></td>
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