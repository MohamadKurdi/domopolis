<div id="tab-image">
	<table class="form">
		<tr>
			<td style="width:30%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Логотип</span></p>

				<div class="image">
					<img src="<?php echo $logo; ?>" alt="" id="thumb-logo" />
					<input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo" />
					<br />
					<a onclick="image_upload('logo', 'thumb-logo');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-logo').attr('src', '<?php echo $no_image; ?>'); $('#logo').attr('value', '');"><?php echo $text_clear; ?></a>
				</div>
			</td>

			<td style="width:30%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Иконка (для совместимости)</span></p>

				<div class="image">
					<img src="<?php echo $icon; ?>" alt="" id="thumb-icon" />
					<input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon" />
					<br />
					<a onclick="image_upload('icon', 'thumb-icon');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-icon').attr('src', '<?php echo $no_image; ?>'); $('#icon').attr('value', '');"><?php echo $text_clear; ?></a>
				</div>
			</td>

			<td style="width:30%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Заглушка No Image</span></p>

				<div class="image"><img src="<?php echo $noimage; ?>" alt="" id="thumb-noimage" />
					<input type="hidden" name="config_noimage" value="<?php echo $config_noimage; ?>" id="noimage" />
					<br />
					<a onclick="image_upload('noimage', 'thumb-noimage');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb-noimage').attr('src', '<?php echo $no_image; ?>'); $('#noimage').attr('value', '');"><?php echo $text_clear; ?></a>
				</div>
			</td>
		</tr>
	</table>
	<h2>Качество изображений</h2>

	<table class="form">
		<tr>
			<td style="width:30%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Качество JPEG</span></p>

				<input type="number" name="config_image_jpeg_quality" value="<?php echo $config_image_jpeg_quality; ?>" size="50" style="width:100px;">										
			</td>

			<td style="width:30%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Качество WEBP</span></p>

				<input type="number" name="config_image_jpeg_quality" value="<?php echo $config_image_jpeg_quality; ?>" size="50" style="width:100px;">										
			</td>

			<td style="width:30%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#57AC79; color:#FFF">Качество AVIF</span></p>

				<input type="number" name="config_image_avif_quality" value="<?php echo $config_image_avif_quality; ?>" size="50" style="width:100px;">										
			</td>
		</tr>
	</table>

	<h2>Размеры изображений</h2>
	<table class="form">
		<tr>
			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_category; ?></span></p>

				<input type="number" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" size="50" style="width:100px;" />
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF">Изображения подкатегорий</span></p>

				<input type="number" name="config_image_subcategory_width" value="<?php echo $config_image_subcategory_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_subcategory_height" value="<?php echo $config_image_subcategory_height; ?>" size="50" style="width:100px;" />
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_thumb; ?></span></p>

				<input type="number" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" size="50" style="width:100px;" />
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_popup; ?></span></p>

				<input type="number" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" size="50" style="width:100px;" />
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_product; ?></span></p>

				<input type="number" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" size="50" style="width:100px;" />
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_additional; ?></span></p>

				<input type="number" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" size="50" style="width:100px;" />
			</td>

			

		</tr>
		<tr>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_related; ?></span></p>

				<input type="number" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" size="50" style="width:100px;" />
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_compare; ?></span></p>

				<input type="number" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" size="50" style="width:100px;" />
			</td>

			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_wishlist; ?></span></p>

				<input type="number" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" size="50" style="width:100px;" />
			</td>


			<td style="width:15%">
				<p><span class="status_color" style="display:inline-block; padding:3px 5px; background:#cf4a61; color:#FFF"><?php echo $entry_image_wishlist; ?></span></p>

				<input type="number" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" size="50" style="width:100px;" />
				x
				<input type="number" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" size="50" style="width:100px;"  />
			</td>
		</tr>
	</table>
</div>						