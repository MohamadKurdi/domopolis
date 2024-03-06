<?php echo $header; ?>
<? require_once($this->checkTemplate(dirname(__FILE__), 'structured/translate.js')); ?>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
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
			<h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons"><a onclick="apply()" class="button"><span>Применить</span></a>
				<script language="javascript">
					function apply(){
						$('#form').append('<input type="hidden" id="apply" name="apply" value="1"  />');
						$('#form').submit();
					}
				</script>
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<? echo $categoryocshop; ?>" class="button">Вернуться в дерево</a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
			</div>
			<div class="content">
				<div id="tabs" class="htabs">
					<a href="#tab-general"><?php echo $tab_general; ?></a>
					<a href="#tab-data"><?php echo $tab_data; ?></a>
					<a href="#tab-products">Настройки товаров</a>
					<a href="#tab-reward">Бонусная программа</a>	
					<a href="#tab-taxonomies" style="color:#7F00FF;font-weight:700;"><i class="fa fa-link"></i> Таксономия, связи</a>			
					<a href="#tab-amazon-sync" style="color:#FF9900;font-weight:700;">
						<i class="fa fa-amazon"></i> Синхронизация Amazon
						<?php if ($this->config->get('config_country_id') == 176) { ?>,<span style="color:#cf4a61"><i class="fa fa-yahoo"></i> Yandex Market</span><?php } ?>
					</a>
					<a href="#tab-amazon-auto" style="color:#00AD07;font-weight:700;"><i class="fa fa-star"></i> Автонаполнение Amazon</a>		
					<a href="#tab-related-data">Умные подборы</a>
					<a href="#tab-design"><?php echo $tab_design; ?></a>
					<a href="#tab-menucontent">Контент в меню</a><div class="clr"></div>
				</div>
				<div class="th_style"></div>
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-general')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-products')); ?>				
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-reward')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-taxonomies')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-amazon-sync')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-amazon-auto')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-related-data')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-data')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-design')); ?>
					<?php require_once($this->checkTemplate(dirname(__FILE__) , '/catalog/category_tabs/tab-menucontent')); ?>
				</form>
			</div>
		</div>
	</div>

	
	<script type="text/javascript">
		function image_upload(field, thumb) {
			$('#dialog').remove();
			
			$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
			
			$('#dialog').dialog({
				title: '<?php echo $text_image_manager; ?>',
				close: function (event, ui) {
					if ($('#' + field).attr('value')) {
						$.ajax({
							url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
							dataType: 'text',
							success: function(data) {
								$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
							}
						});
					}
				},	
				bgiframe: false,
				width: <?php echo $this->config->get('pim_width')?$this->config->get('pim_width'):800;?>,
				height: <?php echo $this->config->get('pim_height')?$this->config->get('pim_height'):400;?>,
				resizable: false,
				modal: false
			});
		};
	</script> 
	<script type="text/javascript">
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('#tabs a').tabs(); 
		$('#languages a').tabs();
		$('#languages-menucontent a').tabs();
	</script> 
	<?php echo $footer; ?>																																												