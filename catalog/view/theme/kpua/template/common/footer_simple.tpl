<!--footer__down-->
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/footer_pwainstall.tpl')); ?>

    <?php if ($this->config->get('social_auth_google_app_id') && $this->config->get('social_auth_google_enable_sso_widget')) { ?> 
    <div class="hidden ajax-module-reloadable" data-modpath="api/google/widget" data-reloadable-group="customer"></div>  
    <?php } ?>

<div class="footer__down">
	<div class="wrap">
		
		<div class="footer__container">
			
			<div class="footer__copyright">
				<div class="footer__language">
					
				</div>
				<p>&copy;<?php echo $this->config->get('config_name'); ?>
				2010 - <?php echo date('Y'); ?> </p>
			</div>
			<div class="footer__agreement">
				<ul>
					<li><a href="<?php echo $href_polzovatelskoe; ?>" title="<?php echo $text_retranslate_19; ?>"><?php echo $text_retranslate_19; ?></a></li>
					<li><a href="<?php echo $href_personaldata; ?>" title="<?php echo $text_retranslate_20; ?>"><?php echo $text_retranslate_20; ?></a>
					</li>
				</ul>
			</div>
			
			
			<ul class="footer__payments">
					<li>
						<img src="/catalog/view/theme/kpua/img/master-card.svg" alt="">
					</li>
					<li>
						<img src="/catalog/view/theme/kpua/img/visa.svg" alt="">
					</li>
					<li>
						<img src="/catalog/view/theme/kpua/img/LIQPAY.png" alt="" width="47">
					</li>
			</ul>
		</div>
	</div>
</div>
<!--/footer__down-->
<div id="main-overlay-popup" class="overlay_popup"></div>
<div id="popup-cart-trigger" class="do-popup-element" data-target="popup-cart" class="hidden"></div>
<div id="popup-cart" class="popup-form" style="display:none;"></div>

<?php if ($general_minified_css_uri) { ?>
    <link href="<? echo $general_minified_css_uri; ?>" rel="stylesheet" media="screen" />
<?php } ?>

<?php if ($general_minified_js_uri) { ?>
    <script src="<? echo $general_minified_js_uri; ?>"></script>
<?php } ?>

<?php  foreach ($incompatible_scripts as $incompatible_script) { ?>
    <script src="<?php echo $incompatible_script; ?>"></script>
<?php } ?>

<!-- Новое меню -->


<div class="popup-form" id="callback-view">
    <div class="object">
        <div class="overlay-popup-close"><i class="fas fa-times"></i></div>
        <div class="info-order-container">
            <div class="content"></div>  
		</div>
	</div>
</div>


<div class='popup_bg_rev' style="display: none;position: fixed;left: 0;top: 0;z-index: 999;"></div>

<script>    
    $( document ).ready(function() {
        <? if (mb_strlen($mask, 'UTF-8') > 1) { ?>
            $('#phone-report_bug').mask("<?php echo $mask; ?>");
		<? } ?>
		
	});
</script>

<script>
    $( document ).ready(function() {
        $('.social-contact #account_header').on('click', function(){
            $('.social-contact #account_header .user_logOut').toggleClass('active');
		});
		
		const   accauntHeaderBtnMob = $('.social-contact #account_header'),
		accauntMenuMob     = $('.social-contact #account_header .content');           
		
		accauntHeaderBtnMob.on('click', function() {
			if ( $(this).hasClass('is-active') ) {
				$(this).removeClass('is-active');
				accauntMenuMob.slideUp();
				} else {
				$(this).addClass('is-active');
				accauntMenuMob.slideDown();
			}
		});   
		
		$(document).mouseup(function (e){ 
			
			if (!accauntHeaderBtnMob.is(e.target) 
			&& accauntHeaderBtnMob.has(e.target).length === 0) { 
				accauntMenuMob.slideUp();
				accauntHeaderBtnMob.removeClass('is-active');
			}
		});  
        
	}); 
</script>

<script>
    $( document ).ready(function() {
    	$(".tabs__caption .loadLoginTab").on("click", function(){
	     	$.ajax({
		        url: "index.php?route=account/otp",
		        method: "GET",
		        success: function(response){
		            $("#auth_with_phone").html(response);
		        },
		        error: function(error){
		            console.log("error--> ", error);
		        }
		    });
		 });

        $('.element-target-click-event').click(function(e){
            let elem = $(this);
            let gaEvent = elem.attr('data-gtm-event');
            let ymGoal = elem.attr('data-ym-event');
            
            if (window['google_tag_manager']) {
                e.preventDefault();
                targetUrl = e.target.href;
                window.dataLayer = window.dataLayer || [];
                console.log('dataLayer.push ' + gaEvent);
                dataLayer.push({
                    'event' : gaEvent,
                    'data-href' : e.target.href,
                    'eventCallback' : function() {
                        <?php if ($this->config->get('config_metrika_counter')) { ?>                       
                            if (typeof ym !== 'undefined'){
                                ym(<?php echo $this->config->get('config_metrika_counter'); ?>,'reachGoal', ymGoal);
							}
						<?php } ?>
                        window.location = targetUrl
					}              
				});
			}
		});
	});
    
</script>

<? if (ADD_METRICS_TO_FRONT) { ?>
	<?php echo ($google_analytics); ?>
	<?php if ($config_vk_enable_pixel) { ?>
		<? echo $config_vk_pixel_header; ?>
	<?php } ?>
	
<? } ?>


<script>
	window.addEventListener('beforeunload', function(event) {
		console.log('BeforeUnload fired');			
		if (NProgress instanceof Object){				
			NProgress.configure({ showSpinner: false });
			NProgress.start();
			NProgress.inc(0.1);
			setTimeout(function () {
				NProgress.inc(0.5);
			}, 100);
			setTimeout(function () {
				NProgress.done();
				$(".fade").removeClass("out");
			}, 1000);			
		}
	});
</script>
<script>
	window.___gcfg = {
		lang: '<?php echo $language_code; ?>'
	};
</script>
<script>	
	function openCart(e) {	
		if (NProgress instanceof Object){				
			NProgress.configure({ showSpinner: false });
			NProgress.start();
			NProgress.inc(0.4);
		}
		
		$('#popup-cart').load('<?php echo $popupcart; ?>', function(){ $('#popup-cart-trigger').click(); if (NProgress instanceof Object){ NProgress.done();  $(".fade").removeClass("out"); } });	
	}
	$(document).ready(function () {
		if(document.documentElement.clientWidth < 560) {
			$('.simplecheckout-block.order-cart').find('.order-cart__item').last().addClass('last__item')
		};       
	}); 
</script>
<?php if ($this->config->get('config_helpcrunch_enable')) { ?>
	<span class="ajax-module-reloadable" data-modpath="api/helpcrunch" data-reloadable-group="customer"></span>
<?php } ?>
<?php if ($this->config->get('config_metrika_counter')) { ?>
    <script type="text/javascript" >
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
			};
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
		})(window, document, "script", "https://cdn.jsdelivr.net/npm/yandex-metrica-watch/tag.js", "ym");
        ym(<?php echo $this->config->get('config_metrika_counter'); ?>, "init", {
            clickmap: <?php if ($this->config->get('config_clickmap_enable')) { ?>true<?php } else { ?>false<?php } ?>,
            trackLinks: <?php if ($this->config->get('config_clickmap_enable')) { ?>true<?php } else { ?>false<?php } ?>,
            accurateTrackBounce: <?php if ($this->config->get('config_clickmap_enable')) { ?>true<?php } else { ?>false<?php } ?>,
            webvisor: <?php if ($this->config->get('config_webvisor_enable')) { ?>true<?php } else { ?>false<?php } ?>,
            ecommerce: "yandexDataLayer"
		});</script> <noscript><div><img src="https://mc.yandex.ru/watch/<?php echo $this->config->get('config_metrika_counter'); ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<?php } ?>  

</body>
</html>    