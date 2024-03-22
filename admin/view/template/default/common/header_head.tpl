<head>
		<?php require_once($this->checkTemplate(dirname(__FILE__) , 'common/pwa.tpl')); ?>
		
		<meta charset="utf-8">
		<meta name="viewport"
		content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $title; ?></title>
		<link href="<? echo FAVICON; ?>" rel="icon" type="image/x-icon">
		<base href="<?php echo $base; ?>" />
		<?php if ($description) { ?>
			<meta name="description" content="<?php echo $description; ?>" />
		<?php } ?>
		<?php if ($keywords) { ?>
			<meta name="keywords" content="<?php echo $keywords; ?>" />
		<?php } ?>
		<?php foreach ($links as $link) { ?>
			<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
		<?php } ?>

		<link rel="stylesheet" type="text/css" href="view/stylesheet/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/themes/redmond/jquery-ui-1.9.2.custom.min.css" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/<? echo FILE_STYLE; ?>?v=<?php echo mt_rand(0,10000); ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/<? echo FILE_STYLE2; ?>?v=<?php echo mt_rand(0,10000); ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/mobile.css?v=<?php echo mt_rand(0,10000); ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/tickets.css" />				
		<link rel="stylesheet" type="text/css" href="view/stylesheet/jpicker-1.1.6.min.css" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/jpicker.css" />
		<?php foreach ($styles as $style) { ?>
			<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
		<?php } ?>
		
		<script type="text/javascript" src="view/javascript/jquery/jquery-1.8.1.min.js"></script>
		<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript" src="view/javascript/common.js"></script>
		<script type="text/javascript" src="view/javascript/script-mobile.js"></script>
		<script src="view/javascript/jquery/jpicker-1.1.6.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
		<style>.scrollbox img{height:16px; width:16px;}</style>
		<style>li.divider{border-bottom:1px solid #e3e3e2; height:1px; width: 100%; margin-bottom:5px;}</style>

		<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic" rel="stylesheet"> 
		<?php foreach ($scripts as $script) { ?>
			<script type="text/javascript" src="<?php echo $script; ?>"></script>
		<?php } ?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#form').submit(function(){
					if ($(this).attr('action').indexOf('delete',1) != -1) {
						if (!confirm('<?php echo $text_confirm; ?>')) {
							return false;
						}
					}
				});
				$('a').click(function(){
					if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
						if (!confirm('<?php echo $text_confirm; ?>')) {
							return false;
						}
					}
				});
			});
		</script>
				
		<?php if ($this->config->get('admin_quick_edit_status') && ($this->config->get('aqe_alternate_row_colour') || $this->config->get('aqe_row_hover_highlighting'))) { ?>
			<style type="text/css">
				<?php if ($this->config->get('aqe_alternate_row_colour')) { ?>
					table.list tbody tr:not([class~=filter]):nth-child(even) td {background: #F8F8FB !important}
					table.list tbody tr:not([class~=filter]).selected_row td {background-color:#ffffde !important}
				<?php } ?>
				<?php if ($this->config->get('aqe_row_hover_highlighting')) { ?>
					table[class=list] tbody tr:not([class~=filter]):hover td {background: #faf9f1 !important}
					table[class=list] tbody tr:not([class~=filter]).selected_row:hover td {background: #ffefde !important}
				<?php } ?>
			</style>

			<script type="text/javascript" src="view/javascript/jeditable-1.7.3/jquery.jeditable.js"></script>			
			<? require_once($this->checkTemplate(dirname(__FILE__), 'structured/aqe.js')); ?>
		<?php } ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$('input[type=checkbox][name^="selected"]').change(function () {
					if ($(this).is(':checked')) {
						$(this).parents('tr').first().addClass('selected_row');
						} else {
						$(this).parents('tr').first().removeClass('selected_row');
					}
				});
			});
		</script>
	</head>