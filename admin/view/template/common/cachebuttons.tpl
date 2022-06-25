<style>
	.cache-button-good{background:#00ad07; border-color:#00ad07; color: white;}
	.cache-button-warn{background:#ff7815; border-color:#ff7815;color:white;}
	.cache-button-bad{background:#f91c02; border-color:#f91c02;color: white;}
	a.link_headr{margin-left:5px;color: white!important;}
</style>

<?php if ($this->user->getUserGroup() == 1) { ?>

	<a class="link_headr <? if ($asinDeletionMode) { ?>link_enter cache-button-bad<? } else { ?> cache-button-good<?php } ?>" onclick="$('#asinDMode').load('<? echo $setAsinDeletionMode ?>');" >
		<i class="fa fa-amazon" aria-hidden="true"></i> ASIN <span id="asinDMode"></span>
	</a>

	<a class="link_headr link_enter cache-button-good" href="<? echo $product_ext; ?>" >
		<i class="fa fa-list" aria-hidden="true"></i> <?php echo $totalProducts; ?>
		<?php if (!empty($totalProductsInTechnicalCategory)) { ?>
			/ <?php echo $totalProductsInTechnicalCategory; ?>
		<?php } ?>
	</a>

<? } ?>

<a class="link_headr cache-button-warn" onclick="$('#clearCacheR').load('<? echo $clear_memcache ?>');" >
	<i class="fa fa-eraser" aria-hidden="true"></i> БД <span id="clearCacheR"></span>
</a>


<?php if ($this->user->getUserGroup() == 1) { ?>

	<a class="link_headr <? if ($noCacheMode) { ?>link_enter cache-button-bad<? } else { ?> cache-button-good<?php } ?>" onclick="$('#noCacheR').load('<? echo $noCacheModeLink ?>');">L1 <span id='noCacheR'><? echo ($noCacheMode?'OFF':'ON'); ?> <?php if (!empty($noCacheModeDuration)) { ?><i class="fa fa-clock-o"></i> <? echo $noCacheModeDuration ?><? } ?></span></a>
	<a class="link_headr <? if ($noPageCacheMode) { ?>link_enter cache-button-bad<? } else { ?> cache-button-good<?php } ?>" onclick="$('#noPageCacheR').load('<? echo $noPageCacheModeLink ?>');">FPC <span id='noPageCacheR'><? echo ($noPageCacheMode?'OFF':'ON'); ?> <?php if (!empty($noPageCacheModeDuration)) { ?><i class="fa fa-clock-o"></i> <? echo $noPageCacheModeDuration ?> / <? } ?> <?php echo $noPageCacheModeTTL; ?></span>
	</a>
	<a class="link_headr cache-button-<?php echo $serverResponceTime['class']?>" href="<?php echo $panelLink; ?>"><i class="fa fa-rocket"></i> <?php echo $serverResponceTime['body'];?></a><a class="hidden-xs link_headr cache-button-<?php echo $redisMem['class']?>" href="<?php echo $panelLink; ?>"><i class="fa fa-cog" aria-hidden="true"></i> <?php echo $redisMem['body'];?></a><a class="hidden-xs link_headr cache-button-<?php echo $serverResponceTime['class']?>" href="<?php echo $panelLink; ?>"><i class="fa fa-code" aria-hidden="true"></i> <?php echo $serverResponceTime['engine'];?></a>

<?php } ?>