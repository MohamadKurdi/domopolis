<head>
		<? require_once(dirname(__FILE__) . '/pwa.tpl'); ?>
		
		<meta charset="utf-8">
		<meta name="viewport"
		content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $title; ?></title>
		<link href="<? echo FAVICON; ?>" rel="icon" type="image/x-icon" />
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
		<link rel="stylesheet" href="view/stylesheet/font-awesome-4.7.0/css/font-awesome.min.css">
		<link type="text/css" href="view/javascript/jquery/ui/themes/redmond/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/<? echo FILE_STYLE; ?>?v=<?php echo mt_rand(0,10000); ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/<? echo FILE_STYLE2; ?>?v=<?php echo mt_rand(0,10000); ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/mobile.css?v=<?php echo mt_rand(0,10000); ?>" />
		<link rel="stylesheet" type="text/css" href="view/stylesheet/tickets.css" />
		<?php foreach ($styles as $style) { ?>
			<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
		<?php } ?>
		
		<script type="text/javascript" src="view/javascript/jquery/jquery-1.8.1.min.js"></script>
		<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.9.2.custom.min.js"></script>
		<script type="text/javascript" src="view/javascript/common.js"></script>
		<script type="text/javascript" src="view/javascript/script-mobile.js"></script>
		
		<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic" rel="stylesheet"> 
		<?php foreach ($scripts as $script) { ?>
			<script type="text/javascript" src="<?php echo $script; ?>"></script>
		<?php } ?>
		<script type="text/javascript">
			//-----------------------------------------
			// Confirm Actions (delete, uninstall)
			//-----------------------------------------
			$(document).ready(function(){
				// Confirm Delete
				$('#form').submit(function(){
					if ($(this).attr('action').indexOf('delete',1) != -1) {
						if (!confirm('<?php echo $text_confirm; ?>')) {
							return false;
						}
					}
				});
				// Confirm Uninstall
				$('a').click(function(){
					if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
						if (!confirm('<?php echo $text_confirm; ?>')) {
							return false;
						}
					}
				});
			});
		</script>
		
		<!-- Admin Header Notices 1.0 -->
		<style type="text/css">
			.pull-right {
            float:right;
			}
			.label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: bold;
            line-height: 1;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
			}
			.label-success {
            background-color: #5cb85c;
			}
			.label-info {
            background-color: #5bc0de;
			}
			.label-warning {
            background-color: #f0ad4e;
			}
			.label-danger {
            background-color: #d9534f;
			}
		</style>
		<!-- Admin Header Notices 1.0 -->
		
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
		
		<link rel="Stylesheet" type="text/css" href="view/stylesheet/jpicker-1.1.6.min.css" />
		<link rel="Stylesheet" type="text/css" href="view/stylesheet/jpicker.css" />
		<script src="view/javascript/jquery/jpicker-1.1.6.min.js" type="text/javascript"></script>
	</head>