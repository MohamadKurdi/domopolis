<?php echo $header; ?>
<style type="text/css">
	table.ordermain,table.history{width:100%;border-collapse:collapse;margin-bottom:20px}
	table.orderadress,table.orderproduct{width:48%;border-collapse:collapse;margin-bottom:20px}
	table.ordermain > tbody > tr > th,table.orderadress > tbody > tr > th,table.list > thead > tr > th,table.history > tbody > tr > th,table.form > tbody > tr > th{background: #1f4962;color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
	table.ordermain > tbody > tr > td{width:25%}
	table.ordermain > tbody > tr > td:nth-child(odd),table.orderadress > tbody > tr > td:nth-child(odd){background:#EFEFEF}
	table.ordermain > tbody > tr > td,table.orderadress > tbody > tr > td{padding:5px;color:#000;border-bottom:1px dotted #CCC}
	.clr{clear:both}
	input[type="text"]{width:70%;}
	.blue_heading{text-align:center; padding:8px 0;cursor:pointer; background: #40a0dd;color: #FFF;font-size: 14px;font-weight: 400;margin-bottom:5px;}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/module.png" alt="" />  <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<div class="th_style"></div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table id="notify_bar" class="list">
					<thead>
						<tr>
							<td class="left">SVG</td>
							<td class="left">Текст возле иконки</td>
							<td class="left">Основной текст</td>
							<td class="left">Ссылка</td>
							<td class="left">Текст ссылки</td>
							<td class="left"><?php echo $entry_sort_order; ?></td>
							<td class="left"><?php echo $text_status; ?></td>
							<td width="125px"><?php echo $entry_action; ?></td>
						</tr>
					</thead>
					<?php $notify_bar_row = 0; ?>
					<?php foreach ($notify_bars as $notify_bar) { ?>
						<tbody id="notify_bar-row<?php echo $notify_bar_row; ?>">
							<tr>
								<td class="left">
									<input type="hidden" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_id]" value="<?php echo $notify_bar['notify_bar_id']; ?>" />
									<textarea name="notify_bar[<?php echo $notify_bar_row; ?>][svg]" cols="50" rows="8"><?php echo isset($notify_bar['svg']) ? $notify_bar['svg'] : ''; ?></textarea>
								</td>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][text_near_svg]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['text_near_svg'] : ''; ?>" />
										<br />
									<?php } ?>									
								</td>
								<td class="left">
									<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][main_text]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['main_text'] : ''; ?>" />
										<br />
									<?php } ?>	
								</td>
								<td class="left">
										<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][link]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['link'] : ''; ?>" />
										<br />
									<?php } ?>	
								</td>
								<td class="left">
										<?php foreach ($languages as $language) { ?>
										<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
										<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][notify_bar_description][<?php echo $language['language_id']; ?>][link_text]" value="<?php echo isset($notify_bar['notify_bar_description'][$language['language_id']]) ? $notify_bar['notify_bar_description'][$language['language_id']]['link_text'] : ''; ?>" />
										<br />
									<?php } ?>	
								</td>
								<td class="left">
									<input type="text" name="notify_bar[<?php echo $notify_bar_row; ?>][sort_order]" value="<?php echo $notify_bar['sort_order']; ?>" />
								</td>
								<td class="middle">
									<input class="checkbox" type="checkbox" name="notify_bar[<?php echo $notify_bar_row; ?>][status]" <?php if (isset($notify_bar['status'])) { ?> checked="checked"<?php } ?> id="notify_bar_status_<?php echo $notify_bar_row; ?>">
									<label for="notify_bar_status_<?php echo $notify_bar_row; ?>"></label><?php echo $text_status; ?>
								</td>
								<td class="left">
									<a onclick="$('#notify_bar-row<?php echo $notify_bar_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a>
								</td>
							</tr>
						</tbody>
																		
						<?php $notify_bar_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="7"></td>
							<td class="right"><a onclick="addnotify_bar();" class="button"><?php echo $button_add_notify_bar; ?></a></td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 

<script type="text/javascript"><!--
	var notify_bar_row = <?php echo $notify_bar_row; ?>;
	
	function addnotify_bar() {
		html  = '<tbody id="notify_bar-row' + notify_bar_row + '">';
		html += '  <tr>';
		html += '    <td class="left">';
		html +=	'<textarea name="notify_bar[' + notify_bar_row + '][svg]" cols="50" rows="8"></textarea><input type="hidden" name="notify_bar[' + notify_bar_row + '][notify_bar_id]" value="' + notify_bar_row + '" />';
		html += '</td>';
		
		html += '    <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][text_near_svg]" value="" /> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
		<?php } ?>
		html += '</td>';
		
		html += '    <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][main_text]" value="" /> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
		<?php } ?>
		html += '</td>';
		
		html += '    <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][link]" value="" /> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
		<?php } ?>
		html += '</td>';
		
		html += '    <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<input type="text" name="notify_bar[' + notify_bar_row + '][notify_bar_description][<?php echo $language['language_id']; ?>][link_text]" value="" /> <img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
		<?php } ?>
		html += '</td>';
		
		html += '    <td class="left">';
		html += '	<input type="text" name="notify_bar[' + notify_bar_row + '][sort_order]" value="" />';
		html += '    </td>';
		html += '<td class="middle"><input class="checkbox" type="checkbox" name="notify_bar[' + notify_bar_row + '][status]" id="notify_bar_status_' + notify_bar_row + '" checked="checked"><label for="notify_bar_status_' + notify_bar_row + '"></label><?php echo $text_status; ?></td>';
		html += '    <td class="left"><a onclick="$(\'#notify_bar-row' + notify_bar_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';	
		html += '</tbody>';
		
		$('#notify_bar tfoot').before(html);
		
		notify_bar_row++;
	}
//--></script>
<?php echo $footer; ?>									