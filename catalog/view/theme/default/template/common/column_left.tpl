<?php if ($modules) { ?>
	
	<? 
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
			} else {
			$route = 'common/home';
		}
		
		
		$show_left_column_azaza = ($this->config->get('show_menu_in_left') && $route == 'common/home' && SITE_NAMESPACE == 'KITCHEN');	
	?>	
	<? if ($show_left_column_azaza) { ?>
	<style>
		@media only screen and (max-width:  789px) {
			.m-menu-group.hidden-left-block:not(.active) .m-menu-group-button{
				margin:0 0 0 476px !important;
			}
			.m-menu-group.hidden-left-block.active .m-menu-group-button.active{
				margin:0 0 0 268px !important;
			}
		}
	</style>
	<? } ?>
	
	<div id="column-left" class="m-menu-group hidden-left-block" <? if ($show_left_column_azaza) { ?>style="display:block !important;"<? } ?> >
		<div class="m-menu-grop-overflow">
			<?php foreach ($modules as $module) { ?>		
				<?php echo $module; ?>        		
			<?php } ?>
		</div>
	</div>
<?php } ?>