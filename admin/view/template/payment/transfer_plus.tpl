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
  <div class="box">
    <div class="heading order_head">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

          <div id="tabs" class="vtabs">
            <a href="#tab-general"><?php echo $tab_general; ?></a>
            <?php $module_row = 1; ?>
            <?php foreach ($modules as $module) { ?>
			<? $a = array(); ?>
			<?php foreach ($geo_zones as $geo_zone) { ?>				
				<?php if (isset($module['geo_zone']) and in_array($geo_zone['geo_zone_id'], $module['geo_zone'])) { 
					$a[] = $geo_zone['name'];
				} ?>                 
			<? } ?>
			
            <a href="#tab-module-<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo isset($module['title'][$this->config->get('config_language_id')]) ? $module['title'][$this->config->get('config_language_id')] : $tab_module . ' ' . $module_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" />
			  <?php if ($module['isprepay']) { ?>
				<br /><i style="font-size:10px;">Это предоплата!</i>
			  <? } ?>
			 <br /><i style="font-size:10px;">От <? echo $module['min_total']?$module['min_total'].' '.$module['curr']:'0'; ?></i> 
			<br /><i style="font-size:10px;">До <? echo $module['max_total']?$module['max_total'].' '.$module['curr']:'бесконечности'; ?></i>  
			<br /><i style="font-size:10px;">Страны: <? echo implode('<br />',$a); ?></i>
			<br /><i style="font-size:10px;">Порядок: <? echo $module['sort_order']; ?></i>
			</a>
            <?php $module_row++; ?>
            <?php } ?>
            <span id="module-add"><?php echo $button_add_module; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span>
          </div>


          <div id="tab-general" class="vtabs-content">
            <table class="form">
              <tr>
                  <td><?php echo $entry_status; ?></td>
                  <td><select name="<?php echo $name; ?>_status">
                      <?php if (${$name.'_status'}) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                    </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_sort_order; ?></td>
                <td><input type="text" name="<?php echo $name; ?>_sort_order" value="<?php echo ${$name.'_sort_order'}; ?>" size="3" /></td>
              </tr>
            </table>
          </div>


          <?php $module_row = 1; ?>
          <?php  foreach ($modules as $module) { ?>
          <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
        <table class="form">
          <?php foreach ($languages as $language) {
            if ($language['status'] == 1) {
            ?>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_title; ?></td>
            <td><input size="100" type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['title'][$language['language_id']]) ? $module['title'][$language['language_id']] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br/>
              <?php if (isset($error_title[$module_row][$language['language_id']])) { ?>
              <span class="error"><?php echo $error_title[$module_row][$language['language_id']]; ?></span>
              <?php } ?>
            </td>
		
          </tr>
		  <tr>
			 <td>TIP<span class="help">Курсив под названием метода доставки. Не для предоплат!</span></td>
				 <td><input size="100" type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][tip][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['tip'][$language['language_id']]) ? $module['tip'][$language['language_id']] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br/>             
            </td>
		  </tr>
          <tr>
            <td><?php echo $entry_info; ?></td>
            <td><textarea id="<?php echo $name; ?>_module_<?php echo $module_row; ?>_info_<?php echo $language['language_id']; ?>" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][info][<?php echo $language['language_id']; ?>]" cols="80" rows="7"><?php echo isset($module['info'][$language['language_id']]) ? $module['info'][$language['language_id']] : ''; ?></textarea>
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
              <?php if (isset(${'error_' . $name . '_' . $language['language_id']})) { ?>
              <span class="error"><?php echo ${'error_' . $name . '_' . $language['language_id']}; ?></span>
              <?php } ?></td>
          </tr>
          <?php }
           }
           ?>

          <tr>
              <td><?php echo $entry_image; ?></td>
              <td><div class="image"><img src="<?php if (isset($module['thumb'])) echo $module['thumb']; else echo $no_image; ?>" alt="" id="thumb<?php echo $module_row; ?>" /><br />
                    <input type="hidden" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][image]" value="<?php if (isset($module['image'])) echo $module['image']; ?>" id="image<?php echo $module_row; ?>" />
                    <a onclick="image_upload('image<?php echo $module_row; ?>', 'thumb<?php echo $module_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $module_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $module_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a></td>
          </tr>

          <tr>
            <td><?php echo $entry_store; ?></td>
            <td>
              <div class="scrollbox">
                <?php $class = 'even'; ?>
                <div class="<?php echo $class; ?>">
                  <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_0" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]" value="0" <?php if (isset($module['store']) and is_array($module['store']) and in_array(0, $module['store'])) { ?>checked="checked"<?php } ?> />
                  <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_0"><?php echo $text_default; ?></label>
                </div>
                <?php foreach ($stores as $store) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                      <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]" value="<?php echo $store['store_id']; ?>" <?php if (isset($module['store']) and in_array($store['store_id'], $module['store'])) { ?>checked="checked"<?php } ?> />
                      <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>
                    </div>
                <?php } ?>
              </div>
<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
                <?php if (isset($error_store[$module_row])) { ?>
                <span class="error"><?php echo $error_store[$module_row]; ?></span>
                <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_min_total; ?></td>
            <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][min_total]" value="<?php if (isset($module['min_total'])) echo $module['min_total']; ?>" />
			Только для первого заказа: 
			<select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][min_sum_for_first_order]">
                  <?php if ($module['min_sum_for_first_order']) { ?>
                  <option value="1" selected="selected">Да</option>
                  <option value="0">Нет</option>
                  <?php } else { ?>
                  <option value="1">Да</option>
                  <option value="0" selected="selected">Нет</option>
                  <?php } ?>
                </select>
			</td>
          </tr>
          <tr>
            <td><?php echo $entry_max_total; ?></td>
            <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][max_total]" value="<?php if (isset($module['max_total'])) echo $module['max_total']; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][order_status_id]">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if (isset($module['order_status_id']) and $order_status['order_status_id'] == $module['order_status_id']) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
		  <tr>
             <td>Валюта подсчета</td>
                 <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][curr]" value="<?php if (isset($module['curr'])) echo $module['curr']; ?>" size="5" />&nbsp;&nbsp;	
				<? foreach ($currencies as $c) { ?>
					<? echo $c['code']; ?>&nbsp;							
				<? } ?>
			</td>
           </tr>
		   
		   <tr>
             <td>Коды метода доставки, для которого доступна частичная предоплата</td>
                 <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][ship_code]" cols="60" rows="5"><?php if (isset($module['ship_code'])) echo $module['ship_code']; ?></textarea>			
			</td>
           </tr>
		   
		    <tr>
              <td>Основной город оплаты (обычно - столица)
			  <span class="help">Если это НЕ ПРЕДОПЛАТА, то способ показывается ТОЛЬКО для этих городов. Конструкция !,Москва,г.Москва делает наоборот - отключает показ метода для данных городов и не-предоплатного способа.</span>
			  </td>
                <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][main_city]" cols="60" rows="5"><?php if (isset($module['main_city'])) echo $module['main_city']; ?></textarea></td>
            </tr>
			
			 <tr>
               <td>Настройки для основного города оплаты (выше)
			   <span class="help">СУММА_ДО:ПРОЦЕНТ ПРЕДОПЛАТЫ. Для МСК: 100000:0,99999999999999999999:10</span>
			   </td>
               <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][main_city_rate]" cols="60" rows="5"><?php if (isset($module['main_city_rate'])) echo $module['main_city_rate']; ?></textarea></td>
            </tr>
			
			<tr>
               <td>Настройки для иных регионов страны
			     <span class="help">СУММА_ДО:ПРОЦЕНТ ПРЕДОПЛАТЫ. Для РФ: 10000:100,50000:20,100000:10,99999999999999999999:5</span>
			   </td>
               <td><textarea name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][other_rate]" cols="60" rows="5"><?php if (isset($module['other_rate'])) echo $module['other_rate']; ?></textarea></td>
            </tr>
		   
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td>
              <div class="scrollbox">
                <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                      <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" <?php if (isset($module['geo_zone']) and in_array($geo_zone['geo_zone_id'], $module['geo_zone'])) { ?>checked="checked"<?php } ?> />
                      <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></label>
                    </div>
                <?php } ?>
              </div>
			  <a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);">Выделить всё</a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);">Снять выделение</a>
            </td>
          </tr>
		  
		  						 <tr>
                            <td>Это модуль предоплаты?</td>
                            <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][isprepay]">
                                    <?php if ($module['isprepay']) { ?>
                                    <option value="1" selected="selected">Таки да!</option>
                                    <option value="0">Нет</option>
                                    <?php } else { ?>
                                    <option value="1">Таки да!</option>
                                    <option value="0" selected="selected">Нет</option>
                                    <?php } ?>
                                </select></td>
                        </tr>
						
						 <tr>
                            <td>Ставить галочку если активен</td>
                            <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][checkactive]">
                                    <?php if ($module['checkactive']) { ?>
                                    <option value="1" selected="selected">Таки да!</option>
                                    <option value="0">Нет</option>
                                    <?php } else { ?>
                                    <option value="1">Таки да!</option>
                                    <option value="0" selected="selected">Нет</option>
                                    <?php } ?>
                                </select></td>
                        </tr>
		  
          <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
          </tr>
        </table>

          </div>
          <?php $module_row++; ?>
          <?php } ?>

      </form>
	  <div class="clr"></div>
    </div>
  </div>
</div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
<?php foreach ($languages as $language) {
if ($language['status'] == 1 && false) {?>
CKEDITOR.replace('<?php echo $name; ?>_module_<?php echo $module_row; ?>_info_<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } }
    $module_row++;
}?>
//--></script>

<script type="text/javascript"><!--
<?php //$module_row = count($modules) + 1; ?>

var module_row = <?php echo $module_row; ?>;

function addModule() {
	html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';

	html += '<table class="form">';

    html += '<?php foreach ($languages as $language) { if ($language["status"] == 1) { ?>';
    html += '   <tr>';
    html += '       <td><span class="required">*</span> <?php echo $entry_title; ?></td>';
    html += '       <td><input size="100" type="text" name="<?php echo $name; ?>_module[' + module_row + '][title][<?php echo $language['language_id']; ?>]" value="" /></td>';
    html += '   </tr>';
    html += '   <tr>';
    html += '       <td><?php echo $entry_info; ?></td>';
    html += '       <td><textarea id="<?php echo $name; ?>_module_' + module_row + '_info_<?php echo $language['language_id']; ?>" name="<?php echo $name; ?>_module[' + module_row + '][info][<?php echo $language['language_id']; ?>]" cols="80" rows="7"></textarea>';
    html += '           <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />';
    html += '       </td>';
    html += '   </tr>';
    html += '<?php } } ?>';
    html += '<tr>';
    html += '<td><?php echo $entry_image; ?></td>';
    html += '<td><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + module_row + '" /><br />';
    html += '<input type="hidden" name="<?php echo $name; ?>_module[' + module_row + '][image]" value="<?php if (isset($module['image'])) echo $module['image']; ?>" id="image<?php echo $module_row; ?>" />';
    html += '<a onclick="image_upload(\'image' + module_row + '\', \'thumb' + module_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + module_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + module_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></td>';
    html += '</tr>';
    html += '   <tr>';
    html += '       <td><?php echo $entry_store; ?></td>';
    html += '       <td>';
    html += '           <div class="scrollbox">';
    html += '           <?php $class = 'even'; ?>';
    html += '           <div class="<?php echo $class; ?>">';
    html += '               <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_0" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]" value="0" />';
    html += '               <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_0"><?php echo $text_default; ?></label>';
    html += '           </div>';
    html += '       <?php foreach ($stores as $store) { ?>';
    html += '       <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>';
    html += '       <div class="<?php echo $class; ?>">';
    html += '           <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]" value="<?php echo $store['store_id']; ?>" />';
    html += '           <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][store][]_<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></label>';
    html += '       </div>';
    html += '       <?php } ?>';
    html += '       </div></td>';
    html += '   </tr>';
    html += '   <tr>';
    html += '       <td><?php echo $entry_min_total; ?></td>';
    html += '       <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][min_total]" value="" /></td>';
    html += '   </tr>';
    html += '   <tr>';
    html += '       <td><?php echo $entry_max_total; ?></td>';
    html += '       <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][max_total]" value="" /></td>';
    html += '   </tr>';
    html += '   <tr>';
    html += '       <td><?php echo $entry_order_status; ?></td>';
    html += '       <td><select name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][order_status_id]">';
    html += '       <?php foreach ($order_statuses as $order_status) { ?>';
    html += '           <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>';
    html += '       <?php } ?>';
    html += '   </select></td>';
    html += '   </tr>';

    html += '   <tr>';
    html += '       <td><?php echo $entry_geo_zone; ?></td>';
    html += '       <td>';
    html += '           <div class="scrollbox">';
    html += '       <?php foreach ($geo_zones as $geo_zone) { ?>';
    html += '       <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>';
    html += '       <div class="<?php echo $class; ?>">';
    html += '           <input id="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />';
    html += '           <label for="<?php echo $name; ?>_module[<?php echo $module_row; ?>][geo_zone][]_<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></label>';
    html += '       </div>';
    html += '       <?php } ?>';
    html += '       </div></td>';
    html += '   </tr>';

	html += '    <tr>';
	html += '      <td><?php echo $entry_status; ?></td>';
	html += '      <td><select name="<?php echo $name; ?>_module[' + module_row + '][status]">';
	html += '        <option value="1"><?php echo $text_enabled; ?></option>';
	html += '        <option value="0"><?php echo $text_disabled; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_sort_order; ?></td>';
	html += '      <td><input type="text" name="<?php echo $name; ?>_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    </tr>';
	html += '</table>';
	html += '</div>';


	$('#form').append(html);

    <?php foreach ($languages as $language) { if ($language["status"] == 1) {  ?>
        CKEDITOR.replace('<?php echo $name; ?>_module_' + module_row + '_info_<?php echo $language['language_id']; ?>', {
            filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
        });
    <?php } } ?>
	
	$('#language-' + module_row + ' a').tabs();

	$('#module-add').before('<a href="#tab-module-' + module_row + '" id="module-' + module_row + '"><?php echo $tab_module; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');

	$('.vtabs a').tabs();

	$('#module-' + module_row).trigger('click');

    module_row++;
}
//--></script>


<script type="text/javascript"><!--
    function image_upload(field, thumb) {
        $('#dialog').remove();

        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

        $('#dialog').dialog({
            title: '<?php echo $text_image_manager; ?>',
            close: function (event, ui) {
                if ($('#' + field).attr('value')) {
                    $.ajax({
                        url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
                        dataType: 'text',
                        success: function(text) {
                            $('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
                        }
                    });
                }
            },
            bgiframe: false,
            width: 1024,
            height: 550,
            resizable: false,
            modal: false,
            dialogClass: 'dlg'
        });
    };
//--></script>

<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?>
//--></script>


<?php echo $footer; ?>