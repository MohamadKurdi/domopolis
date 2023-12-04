<div id="tab-videos">

	<table id="videos" class="list">
		<thead>
			<tr>
				<td class="left"style="width:150px;">Превьюшка</td>
				<td class="left" style="width:200px;">Видео</td>
				<td class="right">Заголовок</td>																					
				<td class="right" style="width:50px;">Сортировка</td>
				<td style="width:100px;"></td>
			</tr>
		</thead>
		<?php $video_row = 0; ?>
		<?php foreach ($product_videos as $product_video) { ?>
			<tbody id="video-row<?php echo $video_row; ?>">
				<tr>
					<td class="left">
						<div class="video">
							<img src="<?php echo $product_video['thumb']; ?>" alt="" id="videothumb<?php echo $video_row; ?>" />
							<input type="hidden" name="product_video[<?php echo $video_row; ?>][image]" value="<?php echo $product_video['image']; ?>" id="videoimage<?php echo $video_row; ?>" />
							<br />
							<a onclick="image_upload('videoimage<?php echo $video_row; ?>', 'videothumb<?php echo $video_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $video_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#videoimage<?php echo $video_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
						</div>
					</td>

					<td class="right">
						<textarea name="product_video[<?php echo $video_row; ?>][video]" rows="4" cols="50"><?php echo $product_video['video']; ?></textarea>

						<br />
						<a href="<?php echo $product_video['play'] ?>" target="_blank"><i class="fa fa-play"></i> <?php echo $product_video['play']; ?></a>
					</td>

					<td class="right">
						<?php foreach ($languages as $language) { ?>
							<input style="width:90%;" type="text" name="product_video[<?php echo $video_row; ?>][product_video_description][<?php echo $language['language_id'] ?>][title];" 
							value="<?php if (isset($product_video['product_video_description'][$language['language_id']])) { echo $product_video['product_video_description'][$language['language_id']]['title']; } ?>" />
							<img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br>
						<?php } ?>
					</td>

					<td class="right">
						<input type="text" name="product_video[<?php echo $video_row; ?>][sort_order]" value="<?php echo $product_video['sort_order']; ?>" size="2" />
					</td>

					<td class="left">
						<a onclick="$('#video-row<?php echo $video_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a>
					</td>
				</tr>
			</tbody>
			<?php $video_row++; ?>
		<?php } ?>
		<tfoot>
			<tr>
				<td class="right" colspan="5">
					<a onclick="addvideo();" class="button">Добавить видео</a>																										
				</td>
			</tr>
		</tfoot>
	</table>

	<table class="list">
		<tr>																											
			<td>
				<span class="status_color" style="display:inline-block; padding:3px 5px; background:#FF9900; color:#FFF">Youtube Видео (старая логика, работает только с Youtube)</span>
				<textarea name="youtube" cols="200" rows="3"><?php echo $youtube; ?></textarea>
				<span class="help">
					Например, Mf8YQR9n47U<br />
					Если несколько, то через запятую Mf8YQR9n47U,Zk8YQR9n47U 
				</span>
			</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	var video_row = <?php echo $video_row; ?>;
	
	function addvideo() {
		html  = '<tbody id="video-row' + video_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><div class="video"><img src="<?php echo $no_image; ?>" alt="" id="videothumb' + video_row + '" /><input type="hidden" name="product_video[' + video_row + '][image]" value="" id="videoimage' + video_row + '" /><br /><a onclick="image_upload(\'videoimage' + video_row + '\', \'videothumb' + video_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#videothumb' + video_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#video' + video_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';

		html +='<td class="right">';
		html +='<textarea name="product_video[' + video_row + '][video]" rows="4" cols="50"></textarea>';
		html +='</td>';
		html +='	<td class="right">';
		<?php foreach ($languages as $language) { ?>
			html += '<input style="width:90%;" type="text" name="product_video[' + video_row + '][product_video_description][<?php echo $language['language_id'] ?>][title];" value="" /><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br/>';
		<?php } ?>
		html +='	</td>';
		html += '    <td class="right"><input type="text" name="product_video[' + video_row + '][sort_order]" value="" size="2" /></td>';
		html += '    <td class="right"><a onclick="$(\'#video-row' + video_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
		html += '  </tr>';
		html += '</tbody>';
		
		$('#videos tfoot').before(html);
		
		video_row++;
	}
</script> 