<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="../<?php echo ANYPORT_ADMIN_FOLDER_NAME; ?>/view/stylesheet/anyport.css" />
<div id="content" class="iModuleContent">
  <!-- START BREADCRUMB -->
  <?php require_once(DIR_APPLICATION.'view/template/module/anyport/breadcrumb.php'); ?>
  <!-- END BREADCRUMB -->
  <!-- START FLASHMESSAGE -->
  <?php require_once(DIR_APPLICATION.'view/template/module/anyport/flashmessage.php'); ?>
  <!-- END FLASHMESSAGE -->
  <div class="box">
    <div class="heading order_head">
    <h1><img src="view/image/imodules.png" style="margin-top: -3px;" alt="" /> <span class="iModulesTitle"><?php echo $heading_title; ?></span>
    <ul class="iModuleAdminSuperMenu">
    	<li class="selected">Backup</li>
    	<li>Restore</li>
        <li data-page="auto-backup">Auto Backup</li>
    	<li>Export Products</li>
        <li style="display: none;">Import</li>
    	<li data-page="settings">Settings</li>
        <li>Support</li>
    </ul>
    </h1>
      	<div class="buttons">
        <a class="button AnyPortSubmitButton saveButton"><?php echo $button_save; ?></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
     	</div>
    </div>
    <!-- START NOT ACTIVATED CHECK -->
    <?php require_once(DIR_APPLICATION.'view/template/module/anyport/notactivated.php'); ?>
    <!-- END NOT ACTIVATED CHECK -->
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <input type="hidden" name="AnyPort[Activated]" value="<?= empty($data['AnyPort']['Activated']) ? 'no' : $data['AnyPort']['Activated'] ?>"/>
	  <input type="hidden" class="selectedTab" name="selectedTab" value="<?=(empty($_GET['tab'])) ? 0 : $_GET['tab'] ?>" />
      <ul class="iModuleAdminSuperWrappers">
      	<li>
        	<!-- START TAB BACKUP -->
        	<?php require_once(DIR_APPLICATION.'view/template/module/anyport/tab_backup.php'); ?>
        	<!-- END TAB BACKUP -->
      	</li>
      	<li>
        	<!-- START TAB RESTORE -->
        	<?php require_once(DIR_APPLICATION.'view/template/module/anyport/tab_restore.php'); ?>
        	<!-- END TAB RESTORE -->
      	</li>
        <li>
        	<!-- START TAB AUTOMATIC BACKUP -->
        	<?php require_once(DIR_APPLICATION.'view/template/module/anyport/tab_auto_backup.php'); ?>
        	<!-- END TAB AUTOMATIC BACKUP -->
      	</li>
      	<li>
        	<!-- START TAB EXPORT -->
        	<?php require_once(DIR_APPLICATION.'view/template/module/anyport/tab_export.php'); ?>
        	<!-- END TAB EXPORT -->
      	</li>
        <li>
        	<!-- START TAB IMPORT -->
        	<?php require_once(DIR_APPLICATION.'view/template/module/anyport/tab_import.php'); ?>
        	<!-- END TAB IMPORT -->
        </li>
      	<li>
        	<!-- START TAB SETTINGS -->
        	<?php require_once(DIR_APPLICATION.'view/template/module/anyport/tab_settings.php'); ?>
        	<!-- END TAB SETTINGS -->
      	</li>
        <li>
        	<!-- START TAB SUPPORT -->
        	<?php require_once(DIR_APPLICATION.'view/template/module/anyport/tab_support.php'); ?>
        	<!-- END TAB SUPPORT -->
        </li>
      </ul>
      </form>
    </div>
  </div>
</div>
<script>
	var selectedTab = $('.selectedTab').val();
	var accordionDivs = [jQuery('#accordion_restore'), jQuery('#accordion_backup')];
	var anyportPopup = null;

	var refreshDisabled = function(myaccordion, next) {
		var index = $(myaccordion).accordion( "option", "active" );
		if (next) index = next;
		$.each($(myaccordion).children('h3'), function() {
			if (!$(this).hasClass('permanent-disable')) $(this).removeClass("ui-state-disabled");
		})
		$(myaccordion).children('h3:gt('+index+')').addClass("ui-state-disabled");
		
		return index;
	}
	
	var getIndexOf = function(myclass, myaccordion) {
		var found = false;
		$.each($(myaccordion).children('div'), function(index, value) {
			if ($(this).hasClass(myclass)) {
				found = index;
			} 
		});	
		
		return found;
	}

	$('.iModuleAdminSuperMenu li').removeClass('selected').eq(selectedTab).addClass('selected');
	$('.iModuleAdminSuperWrappers > li').hide().eq(selectedTab).show();
	
	$('.iModuleAdminMenu li').click(function() {
		$('.iModuleAdminMenu li',$(this).parents('li')).removeClass('selected');
		$(this).addClass('selected');
		$($('.iModuleAdminWrappers li',$(this).parents('li')).hide().get($(this).index())).fadeIn(200);
	});
	
	$('.iModuleAdminSuperMenu li').click(function() {
		$('.iModuleAdminSuperMenu > li',$(this).parents('h1')).removeClass('selected');
		$(this).addClass('selected');
		
		$($('.iModuleAdminSuperWrappers > li',$(this).parents('#content')).hide().get($(this).index())).fadeIn(200);
		$('.selectedTab').val($(this).index());
		if ($(this).attr('data-page') == 'settings' || $(this).attr('data-page') == 'auto-backup') { 
			$('.saveButton').show(); 
			if ($(this).attr('data-page') == 'settings') $('.selectedTab').val(0);
			else $('.selectedTab').val($(this).index());
		} else {
			$('.saveButton').hide();
			$('.selectedTab').val($(this).index());
		}
	});
	
	$('.needMoreSize').click(function() {
		window.open('../vendors/anyport/help_increase_size.php', '_blank', 'location=no,width=830,height=650,resizable=no');
	});
	
	$('.showWarnings').click(function() {
		$(this).parent().children('.warningContainer').slideToggle();	
	});
	
	$.each(accordionDivs, function(index, value) {
		var $this = $(this);
		
		$this.accordion({
			changestart : function(event, ui) {
				refreshDisabled($this);
			}
		});
		
		var accordion = $this.data("accordion");
		
		accordion._std_clickHandler = accordion._clickHandler;
		accordion._clickHandler = function( event, target ) {
			var clicked = $( event.currentTarget || target );
			if (! clicked.hasClass("ui-state-disabled")) {
				this._std_clickHandler(event, target);
			}
		};
		
		$('.continueAction', $this).click(function() {
			if ($(this).attr('data-notalone') != 'true') $this.accordion( "option", "active", refreshDisabled($this, $this.accordion( "option", "active") + 1));
		});
	});
	
	$('#anyPortDialog').dialog({
		autoOpen: false,
		modal: true,
		minWidth: 695,
		minHeight: 465,
		resizable: false
	});
	
	if ($('.iModuleAdminSuperMenu li').eq($('.selectedTab').val()).attr('data-page') != 'settings' && $('.iModuleAdminSuperMenu li').eq($('.selectedTab').val()).attr('data-page') != 'auto-backup') $('.saveButton').hide();
</script>
<!-- START AJAX BACKUP/RESTORE -->
<?php require_once(DIR_APPLICATION.'view/template/module/anyport/ajax_backup_restore.php'); ?>
<!-- END AJAX BACKUP/RESTORE -->
<?php echo $footer; ?>