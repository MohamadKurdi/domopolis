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
<? require_once(DIR_TEMPLATEINCLUDE . 'structured/translate.js.tpl'); ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>

	<script type="text/javascript" src="view/javascript/fileuploader.js"></script>
	<link rel="stylesheet" type="text/css" href="view/stylesheet/fileuploader.css" />

	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/product.png" alt="" /> <?php echo $product_id; ?> / <? echo $model; ?> <?php if ($asin) { ?> / <?php echo $asin; ?> <? } ?></h1>

			<div style="float:left; padding-left:30px;">
				<?php if ($this->session->data['config_rainforest_asin_deletion_mode']) { ?>
					<small style="color:#cf4a61;display: block;"><i class="fa fa-info-circle"></i> Включен режим исключения ASIN. Товары, которые будут удалены - никогда более не будут добавлены с Amazon!</small>
				<?php } ?>
				<?php if ($this->session->data['config_rainforest_variant_edition_mode']) { ?>
					<small style="color:#cf4a61;display: block;"><i class="fa fa-info-circle"></i> Включен режим группового редактирования вариантов. Удаление одного варианта удалит и остальные!</small>
				<?php } ?>
				<?php if ($this->session->data['config_rainforest_translate_edition_mode']) { ?>
					<small style="color:#cf4a61;display: block;"><i class="fa fa-info-circle"></i> Включен режим коррекции переводов. Одинаковые значения цветов, материалов, атрибутов будут перезаписаны!</small>
				<?php } ?>
			</div>
			
			<div class="buttons"><a onclick="apply()" class="button"><span>Применить</span></a>
				<script language="javascript">
					function apply(){
						$('#form').append('<input type="hidden" id="apply" name="apply" value="1"  />');
						$('#form').submit();
					}
				</script><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
			</div>
			<div class="content">
				<div id="tabs" class="htabs">
					<a href="#tab-general" style="background-color:#ff7815; color:#FFF;">Текст</a>
					<a href="#tab-data" style="background-color:#ff7815; color:#FFF;">Товар</a>
					<a href="#tab-price" style="background-color:#00ad07; color:#FFF;">Цены</a>		
					
					<?php if ($this->config->get('config_priceva_enable_api')) { ?>			
						<a href="#tab-priceva" style="background-color:#7F00FF; color:#FFF;"><i class="fa fa-product-hunt"></i> Priceva</a>					
					<?php } ?>

					<?php if ($this->config->get('ukrcredits_status')) { ?>
						<a href="#tab-ukrcredits" style="background-color:#00ad07; color:#FFF;" ><?php echo $tab_ukrcredits; ?></a>
					<?php } ?>

					<a href="#tab-amazon" style="background-color:#FF9900; color:#FFF;"><i class="fa fa-amazon"></i> Amazon</a>
					
					<?php if ($this->config->get('config_country_id') == 176) { ?>
						<a href="#tab-yandex-market" style="background-color:#cf4a61; color:#FFF;"><i class="fa fa-yahoo"></i> Yandex.Market</a>
					<? } ?>

					<a href="#tab-special" style="background-color:#00ad07; color:#FFF;">Скидки</a>
					<a href="#tab-discount" style="background-color:#00ad07; color:#FFF;">Дискаунт</a>
					<a href="#tab-markdown" style="background-color:#00ad07; color:#FFF;">Уценка</a>
					<a href="#tab-additional-offer" style="background-color:#00ad07; color:#FFF;">Специальные</a>					
					<a href="#tab-reward" style="background-color:#00ad07; color:#FFF;">Бонусы</a>
					<a href="#tab-parsing" style="background-color:#7F00FF; color:#FFF;">Фиды, парсинг</a>
					<a href="#tab-stock" style="background-color:#7F00FF; color:#FFF;">Склад</a>
					<a href="#tab-dimensions" style="background-color:#7F00FF; color:#FFF;">Габариты, цвет</a>
					<a href="#tab-variants" style="background-color:#ff7815; color:#FFF;">Варианты</a>
					<a href="#tab-links" style="background-color:#ff7815; color:#FFF;">Связи</a>
					<a href="#tab-attribute" style="background-color:#ff7815; color:#FFF;">Атрибуты</a>
					<?php if ($this->config->get('config_use_separate_table_for_features')) { ?>
						<a href="#tab-feature" style="background-color:#ff7815; color:#FFF;">Особенности</a>
					<?php } ?>				
					<a href="#tab-image" style="background-color:#ff7815; color:#FFF;">Картинки</a>
					<a href="#tab-videos" style="background-color:#ff7815; color:#FFF;">Видео</a>
					<?php if ($this->config->get('config_product_options_enable')) { ?>
						<a href="#tab-option" style="background-color:#ff7815; color:#FFF;">Опции</a>
					<?php } ?>
					<?php if ($this->config->get('config_product_profiles_enable')) { ?>
						<a href="#tab-profile" style="background-color:#ff7815; color:#FFF;">Профили</a>
					<?php } ?>
					<?php if ($this->config->get('config_option_products_enable')) { ?>
						<a href="#tab-product-option" style="background-color:#ff7815; color:#FFF;">Товары как опции</a>
					<?php } ?>

					<div class="clr"></div>
				</div>
				<div class="th_style"></div>
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-general.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-data.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-price.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-parsing.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-stock.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-dimensions.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-variants.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-links.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-attribute.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-discount.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-special.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-image.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-videos.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-markdown.tpl'); ?>
					<?php require_once(dirname(__FILE__) . '/product_tabs/tab-reward.tpl'); ?>
					

					<?php if ($this->config->get('config_product_options_enable')) { ?>
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-option.tpl'); ?>
					<?php } ?>

					<?php if ($this->config->get('config_product_profiles_enable')) { ?>
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-profile.tpl'); ?>
					<?php } ?>

					<?php if ($this->config->get('config_option_products_enable')) { ?>
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-product-option.tpl'); ?>
					<?php } ?>

					<?php if ($this->config->get('config_use_separate_table_for_features')) { ?>
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-feature.tpl'); ?>
					<?php } ?>

					<?php if ($this->config->get('ukrcredits_status')) { ?>
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-ukrcredits.tpl'); ?>
					<?php } ?>				

					<?php if ($this->config->get('config_priceva_enable_api')) { ?>		
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-priceva.tpl'); ?>
					<?php } ?>

					<?php if ($this->config->get('config_priceva_enable_api')) { ?>		
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-amazon.tpl'); ?>
					<?php } ?>

					<?php if ($this->config->get('config_country_id') != 176) { ?>
						<?php require_once(dirname(__FILE__) . '/product_tabs/tab-yandex-market.tpl'); ?>
					<?php } ?>

				</form>
			</div>
		</div>
	</div>

		<script type="text/javascript">
		$('#tabs a').tabs(); 
		$('#languages a').tabs();
		$('#markdown-languages a').tabs(); 
		$('#vtab-option a').tabs();
		$('#vtab-product-option a').tabs();
	</script>

	<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
	
	<script type="text/javascript">
		<?php foreach ($languages as $language) { ?>
			CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});

			<?php if ($this->config->get('config_rainforest_description_symbol_limit')){ ?>
				CKEDITOR.replace('description_full<?php echo $language['language_id']; ?>', {
					filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
					filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
				});
			<?php } ?>

			CKEDITOR.replace('markdown_appearance<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});

			CKEDITOR.replace('markdown_condition<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});

			CKEDITOR.replace('markdown_pack<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});

			CKEDITOR.replace('markdown_equipment<?php echo $language['language_id']; ?>', {
				filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
				filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
			});
		<?php } ?>
	</script> 
 
	<script type="text/javascript">
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});
		$('.time').timepicker({timeFormat: 'h:m'});
	</script> 


	<script language="javascript">
		$(document).ready(function(){
			var hash = window.location.hash;
			$('a[href="'+hash+'"]').click();
		});
	</script>

	<?php if ($this->config->get('config_load_ocfilter_in_product')) { ?>
		<script type="text/javascript">
			ocfilter.php = {
				text_select: '<?php echo $text_select; ?>',
				ocfilter_select_category: '<?php echo $ocfilter_select_category; ?>',
				entry_values: '<?php echo $entry_values; ?>',
				tab_ocfilter: '<?php echo $tab_ocfilter; ?>'
			};

			ocfilter.php.languages = [];

			<?php foreach ($languages as $language) { ?>
				ocfilter.php.languages.push({
					'language_id': <?php echo $language['language_id']; ?>,
					'name': '<?php echo $language['name']; ?>',
					'image': '<?php echo $language['image']; ?>'
				});
			<?php } ?>
		</script>
	<?php } ?>

<?php echo $footer; ?>																																		