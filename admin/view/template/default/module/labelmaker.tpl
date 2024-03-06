<?php echo $header; ?>
<style>
#menu{height:35px;}
</style>
<div id="content" class="LabelMaker"> 
	<script type="text/javascript">
		NProgress.configure({
			showSpinner: false,
			ease: 'ease',
			speed: 500,
			trickleRate: 0.2,
			trickleSpeed: 200 
		});
	</script>
	<!-- START BREADCRUMB -->
	<?php require_once(DIR_APPLICATION.'view/template/module/labelmaker/breadcrumb.php'); ?>
	<!-- END BREADCRUMB --> 
	<!-- START FLASHMESSAGE -->
	<?php require_once(DIR_APPLICATION.'view/template/module/labelmaker/flashmessage.php'); ?>
	<!-- END FLASHMESSAGE -->
	<div class="box">
		<?php
			$dirname = DIR_APPLICATION.'view/template/module/labelmaker/';
			
			$tab_files = scandir($dirname);
			$tabs = array();
			foreach ($tab_files as $key => $file) {
				if (strpos($file,'tab_') !== false) {
					$tabs[] = array(
						'file' => $dirname.$file,
						'name' => ucwords(str_replace('.php','',str_replace('_',' ',str_replace('tab_','',$file))))
					);
				} 
			}
			foreach ($tabs as $key => $tab) {
				if ($tab['name'] == 'Support' && $key < count($tabs) - 1) {
					$temp = $tabs[count($tabs) - 1];
					$tabs[count($tabs) - 1] = $tab;
					$tabs[$key] = $temp;
					break;
				}
			}
		?>
		<div class="box-heading">
			<h1><i class="icon-picture"></i> <?php echo $heading_title; ?></h1>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div class="tabbable">
					<div class="tab-navigation">
						<ul class="nav nav-tabs mainMenuTabs">
							<?php $i=0; foreach($tabs as $tab): ?>
							<li <?php echo ($i == 0) ? 'class="active"' : ''; ?>><a href="#tab_<?php echo str_replace(' ', '_', $tab['name']); ?>" data-toggle="tab"><?php echo $tab['name']; ?></a></li>
							<?php $i++; endforeach; ?>
						</ul>
						<div class="tab-buttons">
							<a onclick="$('#form').submit();" class="btn btn-primary save-changes"><i class="icon-ok"></i>&nbsp;&nbsp;<?php echo $button_save; ?></a>
							<a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></a>
						</div>
					</div>
					<div class="tab-content">
						<?php $i=0; foreach($tabs as $tab): ?>
						<div id="tab_<?php echo str_replace(' ', '_', $tab['name']); ?>" class="tab-pane <?php echo ($i == 0) ? 'active' : ''; ?>">
							<?php require_once($tab['file']); ?>
						</div>
						<?php $i++; endforeach; ?>
					</div>
					<!-- /.tab-content --> 
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
	if (window.localStorage && window.localStorage['currentTab']) {
		$('.mainMenuTabs a[href=' + window.localStorage['currentTab'] + ']').trigger('click');  
	}
	
	if (window.localStorage && window.localStorage['currentStoreTab']) {
		$('.storeTabs a[href=' + window.localStorage['currentStoreTab'] + ']').trigger('click');  
	}
	
	if (window.localStorage && window.localStorage['currentSubTab']) {
		if ($('a[href=' + window.localStorage['currentSubTab'] + ']').length != 0) {
			$('a[href=' + window.localStorage['currentSubTab'] + ']').trigger('click');
		}
	}
	
	$('.mainMenuTabs a[data-toggle="tab"]').click(function() {
		if (window.localStorage) {
			window.localStorage['currentTab'] = $(this).attr('href');
		}
	});
	
	$('.storeTabs a[data-toggle="pill"]').click(function() {
		if (window.localStorage) {
			window.localStorage['currentStoreTab'] = $(this).attr('href');
		}
	});
	
	$('a[data-toggle="tab"]:not(.mainMenuTabs a[data-toggle="tab"])').click(function() {
		if (window.localStorage) {
			window.localStorage['currentSubTab'] = $(this).attr('href');
		}
	});
	
	$(".labelmaker-tab-menu > .btn-group > label").live('click', function(e) {
        e.preventDefault();
		
        var index = $(this).index();
		var parent = $(this).parents('.labelmaker-tab-container');
		
        $(".labelmaker-tab > .labelmaker-tab-content", parent).removeClass("active");
        $(".labelmaker-tab > .labelmaker-tab-content", parent).eq(index).addClass("active");
    });
    
    $(".labelmaker-tab-menu > .btn-group > label.active").click();
});
</script> 

<!-- Modal -->
<div id="help-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="HelpModal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">How to increase my file upload limit?</h4>
		</div>
		<div class="modal-body">
			<p>The maximum upload size is predefined for your server and is different for each hosting provider. If the limit is too low, you can either contact your hosting provider to increase it, or you can try to do it yourself by following one of the methods below.</p>
			<h4>Method 1: Modify your php.ini file with the following entries:</h4>
			<pre>
memory_limit = 256M
upload_max_filesize = 200M
post_max_size = 201M
</pre><br />
		<h4>Method 2: In your /admin/ folder of OpenCart, create an .htaccess file with the following entries:</h4>
		<pre>
php_value memory_limit 256M
php_value upload_max_filesize 200M
php_value post_max_size 201M
</pre>
			<p>You can find additional information in <a href="http://php.net/manual/en/ini.core.php" target="_blank">php.net</a></p>
		</div>
    </div>
  </div>
</div>
<div id="google-webfonts-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Google Web Fonts Modal" aria-hidden="true">
	<div class="modal-dialog modal-md">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Download a font from Google Web Fonts.</h4>
		</div>
		<div class="modal-body">
			<?php if ($allow_url_fopen) { ?>
				<p>Preview all font families here: <a href="http://www.google.com/fonts" target="_blank">Google Fonts</a> <br />After you have chosen your font select it from the dropdown below and click "Download Font". The chosen font with all its weights and styles will be downloaded to your web server in the <b>/vendors/labelmaker/font</b> directory. If you already have a font you would like to use on your label, simply upload it to that directory. Please note that the only compatible font format is <b>.ttf</b></p>
				<?php if (isset($google_webfonts) && $google_webfonts) { ?>
				<div class="bfh-selectbox" data-name="google_webfont" data-filter="true" id="google_webfonts_select" data-value="0">
					<?php foreach($google_webfonts['items'] as $k => $google_webfont) { ?>
						<div data-value="<?php echo $k ?>"><?php echo $google_webfont['family']; ?></div>
					<?php } ?>
				</div>
				<?php } else { ?>
					<div class="alert alert-danger warning">Warning! No Google Web Fonts were loaded, please contact support.</div>
				<?php } ?>
			<?php } else { ?>
				<div class="alert alert-danger warning">Warning! You will need <a target="_blank" href="http://www.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen"><b>allow_url_fopen</b></a> enabled on your server in order to download webfonts. You can try contacting your hosting provider to enable it for you.</div>
			<?php } ?>
		</div>
		<?php if ($allow_url_fopen && isset($google_webfonts) && $google_webfonts) { ?>
		<div class="modal-footer">
			<div class="alert alert-success success">Font downloaded successfully!</div>
			<i class="icon-spinner icon-spin"></i>
			<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="download-font">Download Font</button>
        </div>
		<?php } ?>
    </div>
  </div>
</div>

<?php if ($warning_modal) { ?>
<script type="text/javascript">
$(document).ready(function(e) {
	$('#warning-modal').modal('show')
});
</script>
<div id="warning-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Warning!" aria-hidden="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Warning! LabelMaker is not able to run on your system.</h4>
			</div>
			<div class="modal-body">
				<?php foreach ($warning_modal['errors'] as $error) { ?>
					<?php echo $error; ?><br />
				<?php } ?>
				<hr />
				The above errors are caused by improper file/directory permissions. You can try contacting your hosting provider to set the permissions for you or open a support ticket at <a href="http://isenselabs.com" target="_blank">iSenseLabs</a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<?php echo $footer; ?>