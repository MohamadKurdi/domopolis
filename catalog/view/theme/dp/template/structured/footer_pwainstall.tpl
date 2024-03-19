

<div id="footer_app" class="app_wrap" style="display: none;margin-bottom: 0;margin-top: 0;">
	<div class="wrap">
		<span class="text"><?php echo $text_retranslate_app_block; ?></span>
		<button id="download_app" class="app_btn">
			<i class="fas fa-cloud-download-alt"></i>
			<?php echo $text_retranslate_app_install; ?>
		</button>
	</div>
</div>

<?php if ($this->config->get('config_android_playstore_enable')) { ?>
	<div id="footer_app_google_play" class="app_wrap" style="display: none;margin-bottom: 0;margin-top: 0;">
		<div class="wrap">
			<div class="content">
			<span class="text"><?php echo $text_retranslate_app_block; ?></span>
			<a href="<?php echo $this->config->get('config_android_playstore_link'); ?>" target="_blank" rel="noindex nofollow" style="display: flex;"> 
				<img src="/catalog/view/theme/dp/img/gplay_ua.svg" width="170" alt="logo google play" loading="lazy"> 
			</a> 
			</div>
			<img src="/catalog/view/theme/dp/img/footer_pwa_intall_bg_1.png" class="bg" alt="footer_app_google_play" loading="lazy"> 
		</div>
	</div>
<?php } ?>
