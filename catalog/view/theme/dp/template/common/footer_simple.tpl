<!--footer__down-->

<style type="text/css">
	.footer__payments{
		margin-bottom: 0;
	    margin-left: auto;
	    background: #fff;
	    display: flex;
	    align-items: center;
	    padding: 10px 20px;
	    border-radius: 5px;
	}
	.footer__payments li{
		display: flex;
	}
</style>

<div class="footer__down">
	<?php if ($this->config->get('config_store_id') == 2) {?>
		<div class="snow-left"></div>
		<div class="snow-right"></div>
	<?php } ?>
	<div class="wrap">
		
		<div class="footer__container">
			
			<div class="footer__copyright">
				<div class="footer__language">
					
					
				</div>
				<p>&copy;Domopolis 2010 - <?php echo date('Y'); ?> </p>
			</div>
			<div class="footer__agreement">
				<ul>
					<li><a href="<?php echo $href_polzovatelskoe; ?>" title="<?php echo $text_retranslate_19; ?>"><?php echo $text_retranslate_19; ?></a></li>
					<li><a href="<?php echo $href_personaldata; ?>" title="<?php echo $text_retranslate_20; ?>"><?php echo $text_retranslate_20; ?></a>
					</li>
				</ul>
			</div>
			
			
			<ul class="footer__payments" style="margin-bottom: 0;margin-left: auto;">
				<li>
					<svg xmlns="http://www.w3.org/2000/svg" width="42" height="26" viewBox="0 0 42 26" fill="none">
						<path d="M41.0905 13.0903C41.0905 19.8544 35.5907 25.4174 28.7634 25.4174C21.9993 25.4174 16.4363 19.8544 16.4363 13.0903C16.4363 6.32618 21.9361 0.763184 28.7002 0.763184C35.5907 0.763184 41.0905 6.32618 41.0905 13.0903Z" fill="#F9B50B"/>
						<path d="M24.591 13.1535C24.591 12.2685 24.4646 11.3835 24.3381 10.5617H16.7522C16.8154 10.1191 16.9419 9.73984 17.0683 9.23412H23.8324C23.706 8.79161 23.5163 8.34909 23.3267 7.90658H17.574C17.7637 7.46407 18.0165 7.08478 18.2694 6.57905H22.6313C22.3784 6.13654 22.0624 5.69403 21.6831 5.25151H19.2809C19.6602 4.809 20.0394 4.42971 20.5452 3.9872C18.3958 1.96429 15.4879 0.763184 12.2639 0.763184C5.563 0.952831 0 6.32618 0 13.0903C0 19.8544 5.49978 25.4174 12.3271 25.4174C15.5511 25.4174 18.3958 24.1531 20.6084 22.1934C21.0509 21.8141 21.4302 21.3716 21.8727 20.8658H19.3441C19.028 20.4866 18.7119 20.044 18.459 19.6015H22.7577C23.0106 19.2222 23.2635 18.7797 23.4531 18.274H17.7005C17.5108 17.8947 17.3212 17.4522 17.1947 16.9465H23.9588C24.3381 15.8086 24.591 14.5443 24.591 13.1535Z" fill="#C8191C"/>
						<path d="M16.689 16.2512L16.8786 15.1133C16.8154 15.1133 16.689 15.1765 16.5625 15.1765C16.12 15.1765 16.0568 14.9237 16.12 14.7972L16.4993 12.5214H17.1947L17.3843 11.2571H16.7522L16.8786 10.4985H15.5511C15.5511 10.4985 14.7925 14.7972 14.7925 15.3029C14.7925 16.0615 15.235 16.4408 15.8672 16.4408C16.2464 16.4408 16.5625 16.3144 16.689 16.2512Z" fill="white"/>
						<path d="M17.1316 14.1653C17.1316 15.9985 18.3959 16.441 19.4074 16.441C20.3556 16.441 20.7981 16.2514 20.7981 16.2514L21.051 14.9871C21.051 14.9871 20.3556 15.3031 19.6602 15.3031C18.2063 15.3031 18.4591 14.2285 18.4591 14.2285H21.1142C21.1142 14.2285 21.3038 13.4067 21.3038 13.0274C21.3038 12.2056 20.8613 11.1309 19.4074 11.1309C18.143 11.0677 17.1316 12.5216 17.1316 14.1653ZM19.4074 12.2688C20.1027 12.2688 19.9763 13.0906 19.9763 13.1538H18.5223C18.5856 13.0906 18.712 12.2688 19.4074 12.2688Z" fill="white"/>
						<path d="M27.6888 16.251L27.9416 14.7971C27.9416 14.7971 27.3095 15.1131 26.8037 15.1131C25.9187 15.1131 25.4762 14.4178 25.4762 13.5959C25.4762 12.0156 26.2348 11.1937 27.183 11.1937C27.8152 11.1937 28.3841 11.573 28.3841 11.573L28.5738 10.1823C28.5738 10.1823 27.8152 9.86621 27.0566 9.86621C25.5394 9.86621 24.0854 11.1937 24.0854 13.6592C24.0854 15.3028 24.844 16.3775 26.4244 16.3775C26.9934 16.4407 27.6888 16.251 27.6888 16.251Z" fill="white"/>
						<path d="M9.5455 11.0674C8.66048 11.0674 7.9651 11.3202 7.9651 11.3202L7.77545 12.4581C7.77545 12.4581 8.3444 12.2053 9.22942 12.2053C9.67193 12.2053 10.0512 12.2685 10.0512 12.6478C10.0512 12.9006 9.98801 12.9639 9.98801 12.9639H9.41907C8.28118 12.9639 7.08008 13.4064 7.08008 14.9236C7.08008 16.1247 7.83867 16.3775 8.3444 16.3775C9.22942 16.3775 9.67193 15.8086 9.73515 15.8086L9.67193 16.3143H10.7466L11.2523 12.711C11.2523 11.1306 9.98801 11.0674 9.5455 11.0674ZM9.79836 13.9753C9.79836 14.165 9.67193 15.2396 8.91334 15.2396C8.53404 15.2396 8.40761 14.9236 8.40761 14.7339C8.40761 14.4178 8.59726 13.9753 9.60871 13.9753C9.73515 13.9753 9.79836 13.9753 9.79836 13.9753Z" fill="white"/>
						<path d="M12.5169 16.3777C12.833 16.3777 14.4766 16.441 14.4766 14.6709C14.4766 13.0273 12.8962 13.3434 12.8962 12.7112C12.8962 12.3951 13.1491 12.2687 13.5916 12.2687C13.7812 12.2687 14.4766 12.3319 14.4766 12.3319L14.6663 11.1308C14.6663 11.1308 14.2238 11.0044 13.4019 11.0044C12.4537 11.0044 11.4423 11.3837 11.4423 12.7112C11.4423 14.2284 13.0859 14.102 13.0859 14.6709C13.0859 15.0502 12.6434 15.1134 12.3273 15.1134C11.7583 15.1134 11.1262 14.9238 11.1262 14.9238L10.9365 16.1249C11.063 16.2513 11.4423 16.3777 12.5169 16.3777Z" fill="white"/>
						<path d="M38.5618 10.0562L38.3089 11.8262C38.3089 11.8262 37.8032 11.194 37.0446 11.194C35.8435 11.194 34.832 12.648 34.832 14.3548C34.832 15.4295 35.3378 16.5042 36.4756 16.5042C37.2342 16.5042 37.74 15.9984 37.74 15.9984L37.6767 16.441H39.0043L39.9525 10.1826L38.5618 10.0562ZM37.9296 13.4698C37.9296 14.1652 37.6135 15.1134 36.8549 15.1134C36.4124 15.1134 36.1596 14.7341 36.1596 14.0388C36.1596 12.9641 36.6021 12.3319 37.2342 12.3319C37.6767 12.3951 37.9296 12.7112 37.9296 13.4698Z" fill="white"/>
						<path d="M2.40226 16.3145L3.16085 11.6366L3.28728 16.3145H4.1723L5.87913 11.6366L5.18376 16.3145H6.57451L7.64918 10.0562H5.49984L4.1723 13.9123L4.10909 10.0562H2.21261L1.13794 16.3145H2.40226Z" fill="white"/>
						<path d="M22.6312 16.3143C23.0105 14.165 23.0737 12.3949 24.022 12.711C24.1484 11.8892 24.338 11.5099 24.4645 11.1938H24.2116C23.6427 11.1938 23.1369 11.9524 23.1369 11.9524L23.2634 11.2571H21.9991L21.1772 16.3143H22.6312Z" fill="white"/>
						<path d="M30.7227 11.0674C29.8377 11.0674 29.1423 11.3202 29.1423 11.3202L28.9527 12.4581C28.9527 12.4581 29.5216 12.2053 30.4067 12.2053C30.8492 12.2053 31.2285 12.2685 31.2285 12.6478C31.2285 12.9006 31.1653 12.9639 31.1653 12.9639H30.5963C29.4584 12.9639 28.2573 13.4064 28.2573 14.9236C28.2573 16.1247 29.0159 16.3775 29.5216 16.3775C30.4067 16.3775 30.8492 15.8086 30.9124 15.8086L30.8492 16.3143H32.0503L32.556 12.711C32.556 11.1306 31.1653 11.0674 30.7227 11.0674ZM31.0388 13.9753C31.0388 14.165 30.9124 15.2396 30.1538 15.2396C29.7745 15.2396 29.6481 14.9236 29.6481 14.7339C29.6481 14.4178 29.8377 13.9753 30.8492 13.9753C30.9756 13.9753 30.9756 13.9753 31.0388 13.9753Z" fill="white"/>
						<path d="M33.5677 16.3143C33.947 14.165 34.0102 12.3949 34.9585 12.711C35.0849 11.8892 35.2746 11.5099 35.401 11.1938H35.1481C34.5792 11.1938 34.0735 11.9524 34.0735 11.9524L34.1999 11.2571H32.9356L32.1138 16.3143H33.5677Z" fill="white"/>
						</svg>
				</li>
				<li>
					<svg xmlns="http://www.w3.org/2000/svg" width="50" height="17" viewBox="0 0 50 17" fill="none">
					<path d="M46.5203 1.16943H43.4205C42.4834 1.16943 41.7625 1.45779 41.3299 2.39494L35.4187 15.8755H39.5998C39.5998 15.8755 40.3207 14.0732 40.4649 13.6407C40.8974 13.6407 45.0064 13.6407 45.5832 13.6407C45.7273 14.1453 46.0878 15.8034 46.0878 15.8034H49.8364L46.5203 1.16943ZM41.6183 10.613C41.9787 9.74794 43.2042 6.50397 43.2042 6.50397C43.2042 6.57606 43.5647 5.63891 43.7089 5.13429L43.9972 6.43188C43.9972 6.43188 44.7902 9.96421 44.9344 10.6851H41.6183V10.613Z" fill="#3362AB"/>
					<path d="M35.7072 11.046C35.7072 14.0737 32.9678 16.0922 28.7146 16.0922C26.9124 16.0922 25.1823 15.7317 24.2451 15.2992L24.8218 11.9831L25.3264 12.1994C26.624 12.7761 27.4891 12.9924 29.075 12.9924C30.2284 12.9924 31.454 12.5598 31.454 11.5506C31.454 10.9018 30.9493 10.4693 29.3634 9.74839C27.8495 9.02751 25.8311 7.87409 25.8311 5.78353C25.8311 2.9 28.6425 0.953613 32.6074 0.953613C34.1212 0.953613 35.4188 1.24197 36.2118 1.60241L35.6351 4.77429L35.3467 4.48594C34.6258 4.19759 33.6887 3.90923 32.319 3.90923C30.8052 3.98132 30.0843 4.63012 30.0843 5.20682C30.0843 5.85562 30.9493 6.36024 32.319 7.00903C34.6258 8.09036 35.7072 9.31586 35.7072 11.046Z" fill="#3362AB"/>
					<path d="M0.527832 1.31374L0.59992 1.02539H6.79952C7.66458 1.02539 8.31337 1.31374 8.52964 2.25089L9.89931 8.73884C8.52964 5.2786 5.35775 2.46716 0.527832 1.31374Z" fill="#F9B50B"/>
					<path d="M18.6222 1.17023L12.3505 15.8042H8.09734L4.49292 3.54915C7.0881 5.20718 9.25075 7.80236 10.0437 9.60457L10.4763 11.1184L14.369 1.09814H18.6222V1.17023Z" fill="#3362AB"/>
					<path d="M20.2802 1.09814H24.245L21.7219 15.8042H17.7571L20.2802 1.09814Z" fill="#3362AB"/>
					</svg>
				</li>
				<li>
					<img src="/catalog/view/theme/dp/img/g-pay.png" alt="g-pay">
				</li>
				<li>
					<img src="/catalog/view/theme/dp/img/i-pay.png" alt="i-pay">
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

<? if (!CRAWLER_SESSION_DETECTED) { ?>
	<?php echo ($google_analytics); ?>
	<?php if ($config_vk_enable_pixel) { ?>
		<? echo $config_vk_pixel_header; ?>
	<?php } ?>
<? } ?>


<? /* NPROGRESS */ ?>
<script src="/catalog/view/theme/dp/js/nprogress/nprogress.js" async="async" defer></script>
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