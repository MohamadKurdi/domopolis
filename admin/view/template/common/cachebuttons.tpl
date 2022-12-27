<style>
	.cache-button-good{background:#00ad07; border-color:#00ad07; color: white;}
	.cache-button-warn{background:#ff7815; border-color:#ff7815;color:white;}
	.cache-button-bad{background:#cf4a61; border-color:#cf4a61;color: white;}
	a.link_headr{margin-left:5px;color: white!important;}
</style>

<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>

	<?php foreach ($admin_modes as $mode_name => $mode_config) { ?>
		<a class="hidden-xs link_headr <? if (${$mode_name}) { ?>link_enter cache-button-bad<? } else { ?> cache-button-good<?php } ?>" onclick="$('#<?php echo $mode_name; ?>').load('<? echo ${'set_' . $mode_name}; ?>');" >
			<i class="fa <?php echo $mode_config['icon']; ?>" aria-hidden="true"></i> <?php echo $mode_config['btn_text']; ?> <span id="<?php echo $mode_name; ?>"></span>
		</a>
	<?php } ?>

	<?php if ($this->config->get('config_amazon_product_stats_enable')) { ?>
		<a class="link_headr link_enter cache-button-good" href="<? echo $product_ext; ?>" >
			<i class="fa fa-list" aria-hidden="true"></i> <?php echo $totalProducts; ?>
			<?php if (!empty($totalProductsInTechnicalCategory)) { ?>
				/ <?php echo $totalProductsInTechnicalCategory; ?>
			<?php } ?>
		</a>
	<?php } ?>

<? } ?>

<?php if ($refeedsCount > 1) { ?>
<a class="hidden-xs link_headr cache-button-good" href="<?php echo $refeedsCountLink; ?>"><i class="fa fa-google"></i> <?php echo $refeedsCount;?></a>
<?php } ?>

<?php if (!empty($clearMemCache)) { ?>
	<a class="link_headr cache-button-warn" onclick="$('#clearCacheR').load('<? echo $clearMemCache ?>');" >
		<i class="fa fa-eraser" aria-hidden="true"></i><span class="hidden-xs"> БД </span><span id="clearCacheR"></span>
	</a>
<?php } ?>


<?php if (!empty($noPageCacheModeLink)) { ?>

	<a class="link_headr <? if ($noPageCacheMode) { ?>link_enter cache-button-bad<? } else { ?> cache-button-good<?php } ?>" onclick="$('#noPageCacheR').load('<? echo $noPageCacheModeLink ?>');">FPC 
		<span id='noPageCacheR'>
			<? echo ($noPageCacheMode?'OFF':'ON'); ?> 
			<?php if (!empty($noPageCacheModeDuration)) { ?><i class="fa fa-clock-o"></i> <? echo $noPageCacheModeDuration ?>/<?php echo $noPageCacheModeTTL; ?><? } ?> 		
		</span>
	</a>

	<a class="hidden-xs link_headr cache-button-<?php echo $pageCacheInfo['class']?>" href="<?php echo $panelLink; ?>"><i class="fa fa-server"></i> <?php echo $pageCacheInfo['used'] . ' of ' . $pageCacheInfo['total'];?></a>
	<?php /* ?>
	<a class="link_headr cache-button-<?php echo $serverResponceTime['class']?>" href="<?php echo $panelLink; ?>"><i class="fa fa-rocket"></i> <?php echo $serverResponceTime['body'];?></a>
	<?php */ ?>
	<a class="hidden-xs link_headr cache-button-<?php echo $redisMem['class']?>" href="<?php echo $panelLink; ?>"><i class="fa fa-cog" aria-hidden="true"></i> <?php echo $redisMem['body'];?></a>

	<?php /* ?>
	<a class="hidden-xs link_headr cache-button-<?php echo $serverResponceTime['class']?>" href="<?php echo $panelLink; ?>"><i class="fa fa-code" aria-hidden="true"></i> <?php echo $serverResponceTime['engine'];?></a>
	<?php */ ?>

<?php } ?>