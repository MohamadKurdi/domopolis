<?php 
    $popup_name = $moduleName.'[DiscountOnLeave]['.$popup['id'].']';
    $popup_data = isset($moduleData['DiscountOnLeave'][$popup['id']]) ? $moduleData['DiscountOnLeave'][$popup['id']] : array();
    $popup_default = '
    <div style="text-align: center;">  <h2 style="text-align: center; font-size: 34px; line-height: 38px; font-weight: normal; margin: 0; padding: 0 0 40px 0; color: #000;">Hey! Don\'t miss out</h2>
        </div>
        <div style="text-align: center;">  <h2 style="text-align: center; margin: 0; padding: 3px 0; font-size: 42px; line-height: 46px; font-weight: bold; text-transform: uppercase;color: #E40F37;">Get 25% OFF</h2>
          <h3 style="text-align: center; margin: 0; padding: 3px 0; font-size: 24px; line-height: 26px; font-weight: normal;text-transform:uppercase; color: #000;">Your first purchase</h3>
        </div>
        <div style="text-align: center; margin: 40px 0;">  <span style="display: block; margin: 0; padding: 0; text-transform: uppercase; color: #000; font-weight: normal;font-size: 20px; line-height: 24px;">Use discount code</span>  <span style="font-size: 22px; line-height: 22px; display: inline-block; margin: 10px 0 20px 0; padding: 15px 55px; vertical-align: middle;  background-color: #0DC775;color: #fff; text-transform: uppercase; font-weight: bold;">DOL14ISX11</span>  <a href="" style="font-size: 16px;line-height: 16px; margin: 0; padding: 5px 0; text-decoration: none; color: #0da0f4; display: block; text-align: center;">Go back to our store »</a></div>
        <div>  <p style="margin: 0; padding: 0; font-size: 13px; font-style: italic; color: #686868;">* The offer is valid until Saturday, 08 November 2014. The discount code can be used as much times as you want and can be applied for all products in our store.</p>
     </div>
    ';
?>

<div id="popup_<?php echo $popup['id']; ?>" class="tab-pane popups" style="width:99%">
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Окошко <?php echo $popup['id']; ?> статус:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Вкл/выкл</span>
		</div>
		<div class="col-md-3">
 			<select name="<?php echo $popup_name; ?>[status]" class="form-control">
				<option value="0" <?php echo (!empty($popup_data['status']) && $popup_data['status'] == '0') ? 'selected=selected' : '' ?>>Выключен</option>
				<option value="1" <?php echo (!empty($popup_data['status']) && $popup_data['status'] == '1') ? 'selected=selected' : '' ?>>Включен</option>
			</select>
       	</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Метод показа:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;На каких страницах показывать</span>
		</div>
		<div class="col-md-3">
			<select name="<?php echo $popup_name; ?>[method]" class="form-control">
				<option value="0" <?php echo (!empty($popup_data['method']) && $popup_data['method'] == '0') ? 'selected=selected' : '' ?>>Домашняя</option>
				<option value="1" <?php echo (!empty($popup_data['method']) && $popup_data['method'] == '1') ? 'selected=selected' : '' ?>>Все страницы</option>
				<option value="2" <?php echo (!empty($popup_data['method']) && $popup_data['method'] == '2') ? 'selected=selected' : '' ?>>Заданные страницы</option>
			</select>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Показывать залогиненным:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;показывать залогиненным пользователям, или нет</span>
		</div>
		<div class="col-md-3">
			<select name="<?php echo $popup_name; ?>[show_to_logged]" class="form-control">
				<option value="0" <?php echo (!empty($popup_data['show_to_logged']) && $popup_data['show_to_logged'] == '0') ? 'selected=selected' : '' ?>>Не показывать</option>
				<option value="1" <?php echo (!empty($popup_data['show_to_logged']) && $popup_data['show_to_logged'] == '1') ? 'selected=selected' : '' ?>>Показывать</option>
			</select>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Показывать ТОЛЬКО залогиненным:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Покажет ТОЛЬКО залогиненным пользователям</span>
		</div>
		<div class="col-md-3">
			<select name="<?php echo $popup_name; ?>[only_show_to_logged]" class="form-control">
				<option value="y" <?php echo (!empty($popup_data['only_show_to_logged']) && $popup_data['only_show_to_logged'] == 'y') ? 'selected=selected' : '' ?>>Да</option>
				<option value="n" <?php echo (!empty($popup_data['only_show_to_logged']) && $popup_data['only_show_to_logged'] == 'n') ? 'selected=selected' : '' ?>>Нет</option>
			</select>
		</div>
	</div>
	<br />
	<div class="row url_method" style="margin-bottom: 15px;">
		<div class="col-md-4">
			<h5><strong>URLs:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;In this box you can type in the URLs you wish the teaser to show up, separated by a new line</span>
		</div>
		<div class="col-md-8">
			<textarea rows="5" placeholder="http://" type="text" class="form-control" name="<?php echo $popup_name; ?>[url]" /><?php if(!empty($popup_data['url'])) echo $popup_data['url']; else echo ""; ?></textarea>
		</div>
	</div>
	<div class="row allpages_method" style="margin-bottom: 15px;">
		<div class="col-md-4">
			<h5><strong>Excluded URLs:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;In this box you can type in the URLs you wish to exclude, separated by a new line</span>
		</div>
		<div class="col-md-8">
			<textarea rows="5" placeholder="http://" type="text" class="form-control" name="<?php echo $popup_name; ?>[excluded_urls]"/><?php if(!empty($popup_data['excluded_urls'])) echo $popup_data['excluded_urls']; else echo ""; ?></textarea>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Ширина:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Define the width for the selected teaser</span>
		</div>
		<div class="col-md-2">
			<div class="input-group">
 				<input placeholder="Width" type="number" class="form-control" name="<?php echo $popup_name; ?>[width]" value="<?php if(!empty($popup_data['width'])) echo $popup_data['width']; else echo"420"; ?>" />
				<span class="input-group-addon">px</span>
			</div>
		</div>
     </div>
     <br />
     <div class="row">
		<div class="col-md-4">
			<h5><strong>Высота:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Define the height for the selected teaser</span>
		</div>
		<div class="col-md-2">
			<div class="input-group">
				<input placeholder="Height" type="number" class="form-control" name="<?php echo $popup_name; ?>[height]" value="<?php if(!empty($popup_data['height'])) echo $popup_data['height']; else echo"510"; ?>" />
				<span class="input-group-addon">px</span>
			</div>
		</div>
     </div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Повторять показ:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Частота показа</span>
		</div>
		<div class="col-md-3">
 			<select name="<?php echo $popup_name; ?>[repeat]" class="form-control repeatSelect">
				<option value="0" <?php echo (!empty($popup_data['repeat']) && $popup_data['repeat'] == '0') ? 'selected=selected' : '' ?>>Показывать всегда</option>
				<option value="1" <?php echo (!empty($popup_data['repeat']) && $popup_data['repeat'] == '1') ? 'selected=selected' : '' ?>>Только раз за сессию</option>
				<option value="2" <?php echo (!empty($popup_data['repeat']) && $popup_data['repeat'] == '2') ? 'selected=selected' : '' ?>>Раз в Х дней</option>
				<option value="3" <?php echo (!empty($popup_data['repeat']) && $popup_data['repeat'] == '3') ? 'selected=selected' : '' ?>>Раз в X страниц за сессию</option>
			</select>
			<div class="daysPicker">
			</div>
			<br/>
				<div class="input-group">
					<input type="number" min="1" class="form-control" name="<?php echo $popup_name; ?>[days]" value="<?php if(!empty($popup_data['days'])) echo $popup_data['days']; else echo"1"; ?>" />
					<span class="input-group-addon">дней / страниц</span>
				</div>

       	</div>
	</div>
	<br />	
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Показывать на N просмотренной странице:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>Работает совместно с "показывать раз в сессию" и "раз в Х дней. Если включен MouseOut, то обработчик включается НАЧИНАЯ с этой страницы"</span>
		</div>
		<div class="col-md-2">
			<div class="input-group">
 				<input placeholder="1" type="number" class="form-control" name="<?php echo $popup_name; ?>[num_page]" value="<?php if(!empty($popup_data['num_page'])) echo $popup_data['num_page']; else echo"1"; ?>" />
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Способ открытия</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>Откроется ли окно само?</span>
		</div>
		<div class="col-md-2">
			<div class="input-group">
 				<select name="<?php echo $popup_name; ?>[mouseout]" class="form-control">
					<option value="0" <?php echo (!empty($popup_data['mouseout']) && $popup_data['mouseout'] == '0') ? 'selected=selected' : '' ?>>MouseOUT</option>
					<option value="1" <?php echo (!empty($popup_data['mouseout']) && $popup_data['mouseout'] == '1') ? 'selected=selected' : '' ?>>Document.ready()</option>
				</select>
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Задержка при открытии</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Работает вместе с методом открытия (document.ready())</span>
		</div>
		<div class="col-md-2">
			<div class="input-group">
 				<input placeholder="1" type="number" class="form-control" name="<?php echo $popup_name; ?>[delay]" value="<?php if(!empty($popup_data['delay'])) echo $popup_data['delay']; else echo"1000"; ?>" />
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Position:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Set the position of the teaser</span>
		</div>
		<div class="col-md-3">
 			<select name="<?php echo $popup_name; ?>[position]" class="form-control">
				<option value="0" <?php echo (!empty($popup_data['position']) && $popup_data['position'] == '0') ? 'selected=selected' : '' ?>>Center</option>
				<option value="1" <?php echo (!empty($popup_data['position']) && $popup_data['position'] == '1') ? 'selected=selected' : '' ?>>Top left</option>
				<option value="2" <?php echo (!empty($popup_data['position']) && $popup_data['position'] == '2') ? 'selected=selected' : '' ?>>Top right</option>
				<option value="3" <?php echo (!empty($popup_data['position']) && $popup_data['position'] == '3') ? 'selected=selected' : '' ?>>Bottom left</option>
				<option value="4" <?php echo (!empty($popup_data['position']) && $popup_data['position'] == '4') ? 'selected=selected' : '' ?>>Bottom right</option>
				<option value="5" <?php echo (!empty($popup_data['position']) && $popup_data['position'] == '5') ? 'selected=selected' : '' ?>>Top center</option>
				<option value="6" <?php echo (!empty($popup_data['position']) && $popup_data['position'] == '6') ? 'selected=selected' : '' ?>>Bottom center</option>
			</select>
       	</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-4">
			<h5><strong>Prevent closing:</strong></h5>
			<span class="help"><i class="fa fa-info-circle"></i>&nbsp;Prevent closing when clicking outside the popup</span>
		</div>
		<div class="col-md-3">
 			<select name="<?php echo $popup_name; ?>[preventclose]" class="form-control">
				<option value="0" <?php echo (!empty($popup_data['preventclose']) && $popup_data['preventclose'] == '0') ? 'selected=selected' : '' ?>>Disabled</option>
				<option value="1" <?php echo (!empty($popup_data['preventclose']) && $popup_data['preventclose'] == '1') ? 'selected=selected' : '' ?>>Enabled</option>
			</select>
       	</div>
	</div>
	<br />
    <div class="row">
		<div class="col-md-4">
			<h5><strong>Custom CSS:</strong></h5>
		</div>
		<div class="col-md-3">
        				<textarea rows="4" class="form-control" name="<?php echo $popup_name; ?>[custom_css]"><?php if(!empty($popup_data['custom_css'])) echo $popup_data['custom_css']; else echo"#dl-custom-popup-".$popup['id']."{}"; ?></textarea>
       	</div>
	</div>
	<br />
    <ul class="nav nav-tabs popup_tabs">
		<?php $i=0; foreach ($languages as $language) { ?>
			<li <?php if ($i==0) echo 'class="active"'; ?>><a href="#tab-<?php echo $popup['id']; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>"/> <?php echo $language['name']; ?></a></li>
		<?php $i++; }?>
	</ul>
     <div class="tab-content">
		<?php $i=0; foreach ($languages as $language) { ?>
            <div id="tab-<?php echo $popup['id']; ?>-<?php echo $language['language_id']; ?>" language-id="<?php echo $language['language_id']; ?>" class="row-fluid tab-pane language <?php if ($i==0) echo 'active'; ?>">
           		<div class="row">
                    <div class="col-md-2">
                        <h5><strong>Popup Title:</strong></h5>
                    </div>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="title_<?php echo $popup['id']; ?>_<?php echo $language['language_id']; ?>" name="<?php echo $popup_name; ?>[title][<?php echo $language['language_id']; ?>]" value="<?php if(!empty($popup_data['title'][$language['language_id']])) echo $popup_data['title'][$language['language_id']]; else echo ''; ?>" />
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-2">
                        <h5><strong>Popup Content:</strong></h5>
                    </div>
                    <div class="col-md-10">
                        <textarea id="message_<?php echo $popup['id']; ?>_<?php echo $language['language_id']; ?>" name="<?php echo $popup_name; ?>[content][<?php echo $language['language_id']; ?>]">
                            <?php if(!empty($popup_data['content'][$language['language_id']])) echo $popup_data['content'][$language['language_id']]; else echo $popup_default; ?>
                        </textarea>
                    </div>
                </div>
			</div>
        <?php $i++; } ?>
	</div>
	<?php if (isset($newAddition) && $newAddition==true) { ?>
		<script type="text/javascript">
            <?php foreach ($languages as $language) { ?>
                $('#message_<?php echo $popup['id']; ?>_<?php echo $language['language_id']; ?>').summernote({
					height:300
					});
            <?php } ?>
        </script>
	<?php } ?>
</div>