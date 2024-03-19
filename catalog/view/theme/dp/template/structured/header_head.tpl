		<?php if ($preconnect_domains) { ?>
			<? foreach ($preconnect_domains as $preconnect_domain) { ?>
				<link rel="dns-prefetch" href="<?php echo $preconnect_domain;  ?>" >
				<link rel="preconnect" href="<?php echo $preconnect_domain;  ?>" >
			<? } ?>
		<? } ?>
		
		<?php if ($preload_links) { ?>
			<? foreach ($preload_links as $preload_link) { ?>
				<?php if (strpos($preload_link, '.css')) { ?>
					<link rel="preload" href="<?php echo $preload_link; ?>" as="style" crossorigin >
					<?php } elseif (strpos($preload_link, '.js')) { ?>
					<link rel="preload" href="<?php echo $preload_link; ?>" as="script"  crossorigin >
					<?php } elseif (strpos($preload_link, '.woff')) { ?>
					<link rel="preload" href="<?php echo $preload_link; ?>" as="font" type="font/ttf" crossorigin >
					<?php } elseif (strpos($preload_link, '.ttf')) { ?>
					<link rel="preload" href="<?php echo $preload_link; ?>" as="font" crossorigin >
				<? } ?>
			<? } ?>
		<? } ?>
		
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $title; ?></title>
		<base href="<?php echo $base; ?>" />		
		<?php if ($description) { ?>
			<meta name="description" content="<?php echo $description; ?>"/>
		<?php } ?>
		<?php if ($keywords) { ?>
			<meta name="keywords" content="<?php echo $keywords; ?>"/>
		<?php } ?>
		<?php if ($noindex) { ?>
			<meta name="robots" content="noindex,nofolow"/>
		<?php } ?>
		<?php if ($robots) { ?>
			<meta name="robots" content="<? echo $robots; ?>" />
		<?php } ?>
		<meta name="Copyright" content="&copy; 2021-<? echo date('Y'); ?> <?php echo $this->config->get('config_name'); ?>" />
		
		<meta property="og:site_name" content="<?php echo $config_title; ?>"/>
		<meta property="og:company" content="<?php echo $config_title; ?>" />
		<? if ($phone) { ?>
			<meta property="og:phone_number" content="<?php echo $phone; ?>" />
		<? } ?>
		<? if ($phone2) { ?>
			<meta property="og:phone_number" content="<?php echo $phone2; ?>" />
		<? } ?>
		<? if ($phone3) { ?>
			<meta property="og:phone_number" content="<?php echo $phone3; ?>" />
		<? } ?>
		<?php foreach ($opengraphs as $opengraph) { ?>
			<meta property="<?php echo  $opengraph['property']; ?>" content="<? echo $opengraph['content']; ?>"/>
		<?php } ?>
		<? if (isset($social_auth)) { ?>
			<? echo html_entity_decode($social_auth); ?>
		<? } ?>

		<link rel="icon" href="/icon/dp/favicon-32x32.png?v=1107" />
		<link rel="apple-touch-icon" sizes="180x180" href="/icon/dp/apple-touch-icon.png?v=1107">
		<link rel="icon" type="image/png" sizes="32x32" href="/icon/dp/favicon-32x32.png?v=1107">
		<link rel="icon" type="image/png" sizes="194x194" href="/icon/dp/favicon-194x194.png?v=1107">
		<link rel="icon" type="image/png" sizes="192x192" href="/icon/dp/android-chrome-192x192.png?v=1107">
		<link rel="icon" type="image/png" sizes="16x16" href="/icon/dp/favicon-16x16.png?v=1107">
		<link rel="mask-icon" href="/icon/dp/safari-pinned-tab.svg?v=1107" color="#51a62d">
		<link rel="shortcut icon" href="/icon/dp/favicon.ico?v=1107">
		<meta name="apple-mobile-web-app-title" content="<?php echo $this->config->get('config_name'); ?>">
		<meta name="application-name" content="<?php echo $this->config->get('config_name'); ?>">
		<meta name="msapplication-TileColor" content="#00a300">
		<meta name="msapplication-TileImage" content="/icon/dp/mstile-144x144.png?v=1107">
		<meta name="msapplication-config" content="/icon/dp/browserconfig.xml?v=1107">
		<meta name="theme-color" content="#51a62d">

		<?php foreach ($links as $link) { ?>
			<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" <? if ($link['hreflang']) { ?>hreflang="<?php echo $link['hreflang']; ?>"<? } ?> />
		<?php } ?>
		
		<script type="application/ld+json">
			{
				"@context": "http://schema.org",
				"@type": "Organization",
				"url": "<?php echo $base; ?>",
				"logo": "<?php echo $logo; ?>",
				"sameAs" : ["https://www.facebook.com/domopolis.ua"]
			}
		</script>
		<script type="application/ld+json">
			{
				
				"@context":"https://schema.org",
				"@graph":[
				{
					"@type":"WebSite",
					"@id":"<?php echo $base; ?>#website",
					"url":"<?php echo $base; ?>",
					"name":"<?php echo $this->config->get('config_name'); ?>",
					"description":"",
					"potentialAction":[
					{
						"@type":"SearchAction",
						"target":"<?php echo $base; ?>search?search={search_term_string}",
						"query-input":"required name=search_term_string"
					}
					],
					"inLanguage":"<?php echo $lang; ?>"
				}]
			}
			
		</script>
		<script>
			window.dataLayer = window.dataLayer || [];
		</script>
		
		<? if (!CRAWLER_SESSION_DETECTED) { ?>
			<? echo $google_analytics_header; ?>
			<? echo $config_fb_pixel_header; ?>
			<? echo $config_gtm_header; ?>
		<? } ?>