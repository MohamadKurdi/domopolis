

<div id="footer_app" class="app_wrap" style="display: none;">
	<div class="wrap">
		<span class="text"><?php echo $text_retranslate_app_block; ?></span>
		<button id="download_app" class="app_btn">
			<i class="fas fa-cloud-download-alt"></i>
			<?php echo $text_retranslate_app_install; ?>
		</button>
	</div>
</div>

<?php if ($this->config->get('config_android_playstore_enable')) { ?>
	<div id="footer_app_google_play" class="app_wrap" style="display: none;">
		<div class="wrap">
			<span class="text"><?php echo $text_retranslate_app_block; ?></span>
			
			<a href="<?php echo $this->config->get('config_android_playstore_link'); ?>" target="_blank" rel="noindex nofollow" style="margin-left: 15px; margin-right: 15px; display: flex;"> 
				<?php if ($this->config->get('config_language_id') == 6) { ?>
					<img src="/catalog/view/theme/default/img/gplay_ua.svg" width="125" alt="logo google play" loading="lazy">
					<?php } else { ?>
					<img src="/catalog/view/theme/default/img/gplay_ru.svg" width="125" alt="logo google play" loading="lazy">
				<?php } ?>
			</a> 
			<?php if ($this->config->get('config_store_id') == 0) {?>
				<img src="/catalog/view/theme/default/img/qr-code-ru.gif" width="80" alt="google play" loading="lazy">
			<?php } ?>
			<?php if ($this->config->get('config_store_id') == 1) {?>
				<img src="/catalog/view/theme/default/img/qr-code-ua.gif" width="80" alt="google play" loading="lazy">
			<?php } ?>
			<?php if ($this->config->get('config_store_id') == 2) {?>
				<img src="/catalog/view/theme/default/img/qr-code-kz.gif" width="80" alt="google play" loading="lazy">
			<?php } ?>
			<?php if ($this->config->get('config_store_id') == 5) {?>
				<img src="/catalog/view/theme/default/img/qr-code-by.gif" width="80" alt="google play" loading="lazy">
			<?php } ?>
		</div>
	</div>
<?php } ?>
