<div id="listing_app" class="app_wrap product__item" style="display: none;">
	<div class="wrap">
		<span class="text"><?php echo $text_retranslate_app_block; ?></span>
		<button id="listing_download_app" class="app_btn">
			<i class="fas fa-cloud-download-alt"></i>
			<?php echo $text_retranslate_app_install; ?>
		</button>
	</div>
</div>

<?php if ($this->config->get('config_android_playstore_enable')) { ?>
	<div id="listing_app_google_play" class="app_wrap product__item" style="display: none;">
		<div class="wrap">
			<span class="text"><?php echo $text_retranslate_app_block; ?></span>
			
			<a href="<?php echo $this->config->get('config_android_playstore_link'); ?>" target="_blank" rel="noindex nofollow" style="margin-left: 15px;"> 
				<img src="/catalog/view/theme/dp/img/gplay_ua.svg" width="125" alt="logo google play"> 	
			</a> 
		</div>
	</div>
<?php } ?>