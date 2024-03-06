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
			<h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>			
							
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked).trigger('change');" /></td>
							<td class="left" width='1'>Рисунок</td>
							<td class="left"><?php if ($sort == 'name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
							<?php } ?></td>
							<td class="left" width=''>Альтернативные</td>	
							<td class="left" width=''>Виртуальная</td>	
							<td class="left" width=''>Родительская</td>	
							<td class="left" width=''>
                                Производитель
                                <br>
                                <select name="manufacturer_id">
                                    <option value="0">--- Выберите производителя ---</option>
                                    <?php foreach ($brandArray as $b): ?>
                                    <option value="<?=$b['manufacturer_id'] ?>" <?php if ($b['manufacturer_id'] == $control_brand) print 'selected'; ?>><?=$b['name'] ?></option>
                                    <?php endforeach; ?>
								</select>
							</td>
							<td class="right"><?php if ($sort == 'sort_order') { ?>
								<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
							<?php } ?></td>
							<td class="right"><?php echo $column_action; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($collections) { ?>
							<?php foreach ($collections as $collection) { ?>
								<tr>
									<? $problem = ($collection['_lngth']['description']>2500) & ($collection['_lngth']['title']>80) & ($collection['_lngth']['meta_description']>150) ?>
									
									
									<td style="text-align: center; <? if (!$problem) { ?>border-left:2px solid #cf4a61;<? } ?>"><?php if ($collection['selected']) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $collection['collection_id']; ?>" checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $collection['collection_id']; ?>" />
									<?php } ?></td>
									<td class="left" width='1'> <img src='<?php echo $collection['image']; ?>' />
										<? if ($collection['not_update_image']) { ?><br />Не обновлять<? } ?>
										<? if ($collection['no_brand']) { ?><br />Не показывать<? } ?>
									</td>
									<td class="left"><b><?php echo $collection['name']; ?></b> / <?php echo $collection['mname']; ?>  <b>(id = <?php echo $collection['collection_id']; ?>)</b><br /><br />
										
										Описание: <? if ($collection['_lngth']['description']>2500) { ?>
											<span style='color:#4ea24e'>
												ДА, <? echo $collection['_lngth']['description']; ?> симв.
											</span>
											<? } else { ?>
											<span style='color:#cf4a61'>
												НЕТ, <? echo $collection['_lngth']['description']; ?> симв.
											</span>
										<? } ?> (условие > 2500, иначе нет!)<br />
										
										Тайтл: <? if ($collection['_lngth']['title']>80) { ?>
											<span style='color:#4ea24e'>
												ДА, <? echo $collection['_lngth']['title']; ?> симв.
											</span>
											<? } else { ?>
											<span style='color:#cf4a61'>
												НЕТ, <? echo $collection['_lngth']['title']; ?> симв.
											</span>
										<? } ?> (условие > 80, иначе нет!)<br />
										
										Мета-дескрипшн: <? if ($collection['_lngth']['meta_description']>150) { ?>
											<span style='color:#4ea24e'>
												ДА, <? echo $collection['_lngth']['meta_description']; ?> симв.
											</span>
											<? } else { ?>
											<span style='color:#cf4a61'>
												НЕТ, <? echo $collection['_lngth']['meta_description']; ?> симв.
											</span>
										<? } ?> (условие > 150, иначе нет!)<br />
										
										
										
										
									</td>
									<td class="left"><small><?php echo $collection['alternate_name']; ?></small></td>
									<td class="right"><?php if ($collection['virtual']) { ?><span style='color:white; padding:4px; background:#4ea24e;'>да</span><? } else { ?>нет<? } ?></td>
									<td class="right"><?php echo $collection['parent_name']; ?></td>
									<td class="right"><?=$collection['mname'] ?></td>
									<td class="right"><?php echo $collection['sort_order']; ?></td>
									<td class="right"><?php foreach ($collection['action'] as $action) { ?>
										<a class="button" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
									<?php } ?></td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="8"><strong><?php echo $text_no_results; ?></strong></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function updateURLParameter(url, param, paramVal){
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (i=0; i<tempArray.length; i++){
                if(tempArray[i].split('=')[0] != param){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
				}
			}
		}
		
        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
	}
    $(document).ready(function () {
        $('[name="manufacturer_id"]').on('change', function () {
            var id = $(this).val();
            document.location.href = updateURLParameter(window.location.href, 'manufacturer_id', id);
		})
	});
</script>

<?php echo $footer; ?>