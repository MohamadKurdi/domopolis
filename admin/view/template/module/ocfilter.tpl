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
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <?php if ($installed) { ?>
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a onclick="$('#form').attr('action','<?php echo $apply; ?>').submit();" class="button"><?php echo $button_apply; ?></a>
				<?php } ?>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <div id="tabs" class="ocfilter-htabs">
        <?php if (!$installed) { ?>
				<a href="#tab-install"><?php echo $tab_install; ?></a>
        <?php } else { ?>
				<a href="#tab-general"><?php echo $tab_general; ?></a>
        <a href="#tab-option"><?php echo $tab_option; ?></a>
        <a href="#tab-price-filtering"><?php echo $tab_price_filtering; ?></a>
        <a href="#tab-other"><?php echo $tab_other; ?></a>
        <a href="#tab-copy"><?php echo $tab_copy; ?></a>
				<?php } ?>
      </div>
      <?php if (!$installed) { ?>
      <form action="<?php echo $install; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-install" class="ocfilter-htab">
          <h2>Проверьте файлы модуля:</h2>
          <table class="form ocfilter-settings">
						<?php foreach ($files as $file) { ?>
						<tr class="install-package">
              <td><?php echo $file['text']; ?></td>
              <td><input type="text" size="100%" value="<?php echo $file['path']; ?>" /></td>
						</tr>
						<?php } ?>
            <tr>
							<?php if ($validate_install) { ?>
              <td><a onclick="$('#form').submit();" class="button">Установить модуль</a></td><td></td>
							<?php } else { ?>
							<td><a onclick="$('#form').submit();" class="button">Обновить</a></td><td></td>
              <?php } ?>
						</tr>
					</table>
				</div>
			</form>
      <?php } else { ?>
			<form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="ocfilter-htab">
          <table class="form ocfilter-settings">
						<tr class="notice"><td></td><td><?php echo $notice_status; ?></td></tr>
            <tr>
							<td><?php echo $entry_status; ?></td>
							<td><label<?php echo (isset($ocfilter_module[0]['status']) ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][status]" value="1" <?php echo (isset($ocfilter_module[0]['status']) ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_position; ?></td></tr>
            <tr>
              <td><?php echo $entry_position; ?></td>
              <td>
                <div class="position">
                  <?php foreach ($positions as $item) { ?>
                  <a class="<?php echo $item . ($ocfilter_module[0]['position'] == $item ? ' selected' : ''); ?>" title="<?php echo ${'text_' . $item}; ?>"><?php echo ${'text_' . $item}; ?></a>
                  <?php } ?>
                  <input type="hidden" name="ocfilter_module[0][position]" value="<?php echo $ocfilter_module[0]['position']; ?>" />
                </div>
              </td>
            </tr>
            <tr class="notice"><td></td><td><?php echo $notice_sort_order; ?></td></tr>
            <tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="ocfilter_module[0][sort_order]" value="<?php echo $ocfilter_module[0]['sort_order']; ?>" size="2" /></td>
      			</tr>
          </table>
        </div>
        <div id="tab-option" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr class="notice"><td></td><td><?php echo $notice_show_selected; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_selected; ?></td>
							<td><label<?php echo ($ocfilter_show_selected ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_show_selected" value="1" <?php echo ($ocfilter_show_selected ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_price; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_price; ?></td>
							<td><label<?php echo ($ocfilter_show_price ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_show_price" value="1" <?php echo ($ocfilter_show_price ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_counter; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_counter; ?></td>
							<td><label<?php echo ($ocfilter_show_counter ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_show_counter" value="1" <?php echo ($ocfilter_show_counter ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_manufacturer; ?></td></tr>
            <tr>
              <td><?php echo $entry_manufacturer; ?></td>
              <td>
                <label<?php echo ($ocfilter_manufacturer ? ' class="checked"' : ''); ?>><input type="checkbox" class="with-subfield" name="ocfilter_manufacturer" value="1" <?php echo ($ocfilter_manufacturer ? 'checked="checked" ' : ''); ?>data-subfield="manufacturer" /></label>
                <div class="subfield sf-manufacturer<?php echo ($ocfilter_manufacturer ? ' visible' : ''); ?>">
                  <label>
	                  <span><?php echo $entry_type; ?></span>
	                  <select name="ocfilter_manufacturer_type">
	                    <?php foreach ($types as $item) { ?>
	                    <option value="<?php echo $item; ?>" <?php echo ($ocfilter_manufacturer_type == $item ? 'selected="selected" ' : ''); ?>><?php echo ucfirst($item); ?></option>
	                    <?php } ?>
	                  </select>
									</label>
                </div>
              </td>
            </tr>
            <tr class="notice"><td></td><td><?php echo $notice_stock_status; ?></td></tr>
            <tr>
              <td><?php echo $entry_stock_status; ?></td>
              <td>
                <label<?php echo ($ocfilter_stock_status ? ' class="checked"' : ''); ?>><input type="checkbox" class="with-subfield" name="ocfilter_stock_status" value="1" <?php echo ($ocfilter_stock_status ? 'checked="checked" ' : ''); ?>data-subfield="stock-status" /></label>
								<div class="subfield sf-stock-status<?php echo ($ocfilter_stock_status ? ' visible' : ''); ?>">
									<label>
	                  <span><?php echo $entry_stock_status_method; ?></span>
		                <select name="ocfilter_stock_status_method" class="with-subfield" data-subfield="stock-status-method">
		                  <?php if ($ocfilter_stock_status_method == 'quantity') { ?>
		                  <option value="quantity" selected="selected"><?php echo $text_stock_by_quantity; ?></option>
		                  <option value="stock_status_id"><?php echo $text_stock_by_status_id; ?></option>
		                  <?php } else { ?>
		                  <option value="quantity"><?php echo $text_stock_by_quantity; ?></option>
		                  <option value="stock_status_id" selected="selected"><?php echo $text_stock_by_status_id; ?></option>
		                  <?php } ?>
		                </select>
									</label>
									<div class="subfield stock-status-method sf-stock-status-id<?php echo ($ocfilter_stock_status_method == 'stock_status_id' ? ' visible' : ''); ?>">
										<label>
											<span><?php echo $entry_type; ?></span>
                      <select name="ocfilter_stock_status_type">
		                    <?php foreach ($types as $item) { ?>
		                    <option value="<?php echo $item; ?>" <?php echo ($ocfilter_stock_status_type == $item ? 'selected="selected" ' : ''); ?>><?php echo ucfirst($item); ?></option>
		                    <?php } ?>
		                  </select>
										</label>
									</div>
									<div class="subfield stock-status-method sf-quantity<?php echo ($ocfilter_stock_status_method == 'quantity' ? ' visible' : ''); ?>">
										<label>
											<span><?php echo $entry_stock_out_value; ?></span>
											<input type="checkbox" name="ocfilter_stock_out_value" value="1"<?php echo ($ocfilter_stock_out_value ? ' checked="checked" ' : ''); ?> />
										</label>
									</div>
								</div>
              </td>
            </tr>
          </table>
        </div>
        <div id="tab-price-filtering" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr class="notice"><td></td><td><?php echo $notice_manual_price; ?></td></tr>
            <tr>
							<td><?php echo $entry_manual_price; ?></td>
							<td><label<?php echo ($ocfilter_manual_price ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_manual_price" value="1" <?php echo ($ocfilter_manual_price ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_consider_discount; ?></td></tr>
            <tr>
							<td><?php echo $entry_consider_discount; ?></td>
							<td><label<?php echo ($ocfilter_consider_discount ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_consider_discount" value="1" <?php echo ($ocfilter_consider_discount ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_consider_special; ?></td></tr>
            <tr>
							<td><?php echo $entry_consider_special; ?></td>
							<td><label<?php echo ($ocfilter_consider_special ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_consider_special" value="1" <?php echo ($ocfilter_consider_special ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
          </table>
        </div>
        <div id="tab-other" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr class="notice"><td></td><td><?php echo $notice_show_options_limit; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_first_limit; ?></td>
							<td><input type="text" name="ocfilter_show_options_limit" value="<?php echo $ocfilter_show_options_limit; ?>" size="4" />&nbsp;<?php echo $text_options; ?></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_values_limit; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_first_limit; ?></td>
							<td><input type="text" name="ocfilter_show_values_limit" value="<?php echo $ocfilter_show_values_limit; ?>" size="4" />&nbsp;<?php echo $text_values; ?></td>
						</tr>
            <tr class="notice"><td></td><td><?php echo $notice_hide_empty_values; ?></td></tr>
            <tr>
							<td><?php echo $entry_hide_empty_values; ?></td>
							<td><label<?php echo ($ocfilter_hide_empty_values ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_hide_empty_values" value="1" <?php echo ($ocfilter_hide_empty_values ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr>
							<td>Запрещать индексацию после выбора</td>
							<td><input type="number" name="ocfilter_noindex_limit" value="<?php echo $ocfilter_noindex_limit; ?>" size="4" />&nbsp;<?php echo $text_options; ?></td>
						</tr>
            <tr>
							<td colspan="2"><a href="<?php echo $reinstall; ?>" class="button">Переустановить модуль</a></td>
      			</tr>
          </table>
        </div>
        <div id="tab-copy" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td>
                <div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>"><input type="checkbox" name="option_store[]" value="0" /> <?php echo $text_default; ?></div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>"><input type="checkbox" name="option_store[]" value="<?php echo $store['store_id']; ?>" /> <?php echo $store['name']; ?></div>
                  <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_type; ?></td>
              <td>
                <select name="type">
                  <?php foreach ($types as $item) { ?>
                  <option value="<?php echo $item; ?>"><?php echo ucfirst($item); ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Очистить существующие опции фильтра:</td>
              <td>
                <input type="checkbox" name="truncate" value="1" />
              </td>
            </tr>
            <tr>
              <td>Копировать атрибуты:</td>
              <td>
                <input type="checkbox" name="attribute" value="1" />
              </td>
            </tr>
            <tr>
              <td>Копировать опции товаров:</td>
              <td>
                <input type="checkbox" name="option" value="1" />
              </td>
            </tr>
            <tr>
              <td>Копировать стандартные фильтры:</td>
              <td>
                <input type="checkbox" name="filter" value="1" />
              </td>
            </tr>
            <tr><td colspan="2" align="right"><a id="copy-attributes" class="button"><span><?php echo $button_copy; ?></span></a></td></tr>
          </table>
        </div>
      </form>
			<?php } /* if module installed */ ?>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
	ocfilter.php.button_copy = '<?php echo $button_copy; ?>';
  ocfilter.php.button_remove = '<?php echo $button_remove; ?>';
	ocfilter.php.text_executed = '<?php echo $text_executed; ?>';
  ocfilter.php.text_ready = '<?php echo $text_ready; ?>';
  ocfilter.php.text_options = '<?php echo $text_options; ?>';
  ocfilter.php.text_values = '<?php echo $text_values; ?>';
  ocfilter.php.text_select = '<?php echo $text_select; ?>';
	ocfilter.php.text_selected = '<?php echo $text_selected; ?>';
  ocfilter.php.entry_show_diagram = '<?php echo $entry_show_diagram; ?>';
  ocfilter.php.entry_show_first_limit = '<?php echo $entry_show_first_limit; ?>';
  ocfilter.php.entry_show_price = '<?php echo mb_substr($entry_show_price, 0, -1, 'UTF-8'); ?>';
  ocfilter.php.entry_stock_status = '<?php echo mb_substr($entry_stock_status, 0, -1, 'UTF-8'); ?>';
  ocfilter.php.entry_manufacturer = '<?php echo mb_substr($entry_manufacturer, 0, -1, 'UTF-8'); ?>';

  ocfilter.php.layouts = [];

 	<?php foreach ($layouts as $layout) { ?>
  ocfilter.php.layouts.push({
		layout_id: <?php echo $layout['layout_id']; ?>,
		name: '<?php echo $layout['name']; ?>'
	});
  <?php } ?>

  ocfilter.php.categories = [];

 	<?php foreach ($categories as $category) { ?>
  ocfilter.php.categories.push({
		category_id: <?php echo $category['category_id']; ?>,
    level: <?php echo $category['level']; ?>,
		name: '<?php echo $category['name']; ?>'
	});
  <?php } ?>

  ocfilter.php.positions = [];

  <?php foreach ($positions as $item) { ?>
  ocfilter.php.positions.push({
		name: '<?php echo ${'text_' . $item}; ?>',
		position: '<?php echo $item; ?>'
	});
  <?php } ?>
//--></script>
<?php echo $footer; ?>