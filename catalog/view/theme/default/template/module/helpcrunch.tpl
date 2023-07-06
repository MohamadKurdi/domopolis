<?php if ($this->config->get('config_helpcrunch_enable')) { ?>
	<script type="text/javascript">
		window.helpcrunchSettings = {
			organization: '<?php echo $this->config->get('config_helpcrunch_app_organisation'); ?>',
			appId: '<?php echo $this->config->get('config_helpcrunch_app_id'); ?>',
			<?php if ($this->config->get('config_helpcrunch_send_auth_data') && $this->customer->isLogged()) { ?>
				user: {
     				user_id: 	'<?php echo md5($this->customer->getID()) . $this->customer->getId(); ?>',
      				name: 		'<?php echo prepareEcommString($this->customer->getFirstName() . ' ' . $this->customer->getLastName()); ?>',
      				email: 		'<?php echo prepareEcommString($this->customer->getEmail()); ?>',
      				phone: 		'<?php echo prepareEcommString($this->customer->getTelephone()); ?>',
      				company: 	'',
      				custom_data: {
        				cid: 			'<?php echo $this->customer->getId(); ?>',
        				points_count: 	'<?php echo $this->customer->getRewardTotal(); ?>',
        				order_count: 	'<?php echo $this->customer->getTotalOrders(); ?>',
        				birthday_date: 	'<?php echo $this->customer->getBirthday()?$this->customer->getBirthday():'not_set'; ?>',
        				city: 			'<?php echo prepareEcommString($this->customer->getCity()); ?>',
        				address: 		'<?php echo prepareEcommString($this->customer->getAddress()); ?>',
      				},
      			}
			<?php } ?>
		};
	</script>

	<script type="text/javascript">
		(function(w,d){var hS=w.helpcrunchSettings;if(!hS||!hS.organization){return;}var widgetSrc='https://'+hS.organization+'.widget.helpcrunch.com/';w.HelpCrunch=function(){w.HelpCrunch.q.push(arguments)};w.HelpCrunch.q=[];function r(){if (d.querySelector('script[src="' + widgetSrc + '"')) { return; }var s=d.createElement('script');s.async=1;s.type='text/javascript';s.src=widgetSrc;(d.body||d.head).appendChild(s);}if(d.readyState === 'complete'||hS.loadImmediately){r();} else if(w.attachEvent){w.attachEvent('onload',r)}else{w.addEventListener('load',r,false)}})(window, document)
	</script>
<?php } ?>