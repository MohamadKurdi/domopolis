 <!--checkout-steps__item-->
<div class="checkout-steps__item">
    <div class="checkout-steps__head">
		<div class="checkout-steps__count">1</div>
		<div class="checkout-steps__info">
			<div class="checkout-steps__title"><?php echo $customer_data['firstname']; ?> <?php echo $customer_data['lastname']; ?>, <span><?php echo $customer_data['telephone']; ?></span></div>
			<div class="checkout-steps__edit">
				<a class="link" onclick="$('#simplecheckout_button_prev').trigger('click');">
					<svg width="22" height="22" viewbox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M11 15L7 11M7 11L11 7M7 11H15M1 11C1 16.5228 5.47715 21 11 21C16.5228 21 21 16.5228 21 11C21 5.47715 16.5228 1 11 1C5.47715 1 1 5.47715 1 11Z" stroke="#51A881" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				Изменить</a>
			</div>
		</div>
    </div>
</div>
<!--/checkout-steps__item-->

<script>
	$('.tooltip').tooltipster({
		contentCloning: false,
		plugins: ['follower'],
		trigger: 'custom',
		triggerOpen: {
			click: true,
			tap: true,
			mouseenter: true,
			touchstart: true
		},
		triggerClose: {
			mouseleave: true,
			originClick: true,
			touchleave: true
		},
		maxWidth: 320,
		animationDuration: 0,
		delay: 1,
		delayTouch: [1, 1],
		trackerInterval: 50,
		distance: 3,
		side: ['left']
	});
</script>