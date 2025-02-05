<?php echo $header; ?><?php if( ! empty( $mfilter_json ) ) { echo '<div id="mfilter-json" style="display:none">' . base64_encode( $mfilter_json ) . '</div>'; } ?>
<style type="text/css">

	#promo-code-txt-action {
	    color: #97b63c;
	    white-space: nowrap;
	    padding: 2px 9px;
	    border: 1px dashed;
	    font-weight: 500;
	    cursor: pointer;
	    margin-left: 15px;
	}
		.btn-copy {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDc3Ljg2NyA0NzcuODY3IiBzdHlsZT0iZmlsbDojNTFhODgxOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik0zNDEuMzMzLDg1LjMzM0g1MS4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJ2MjkwLjEzM2MwLDI4LjI3NywyMi45MjMsNTEuMiw1MS4yLDUxLjJoMjkwLjEzMyAgICBjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjEzNi41MzNDMzkyLjUzMywxMDguMjU2LDM2OS42MSw4NS4zMzMsMzQxLjMzMyw4NS4zMzN6IE0zNTguNCw0MjYuNjY3ICAgIGMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3SDUxLjJjLTkuNDI2LDAtMTcuMDY3LTcuNjQxLTE3LjA2Ny0xNy4wNjdWMTM2LjUzM2MwLTkuNDI2LDcuNjQxLTE3LjA2NywxNy4wNjctMTcuMDY3ICAgIGgyOTAuMTMzYzkuNDI2LDAsMTcuMDY3LDcuNjQxLDE3LjA2NywxNy4wNjdWNDI2LjY2N3oiLz4gIDwvZz48L2c+PGc+ICA8Zz4gICAgPHBhdGggIGQ9Ik00MjYuNjY3LDBoLTMwNy4yYy0yOC4yNzcsMC01MS4yLDIyLjkyMy01MS4yLDUxLjJjMCw5LjQyNiw3LjY0MSwxNy4wNjcsMTcuMDY3LDE3LjA2N1MxMDIuNCw2MC42MjYsMTAyLjQsNTEuMiAgICBzNy42NDEtMTcuMDY3LDE3LjA2Ny0xNy4wNjdoMzA3LjJjOS40MjYsMCwxNy4wNjcsNy42NDEsMTcuMDY3LDE3LjA2N3YzMDcuMmMwLDkuNDI2LTcuNjQxLDE3LjA2Ny0xNy4wNjcsMTcuMDY3ICAgIHMtMTcuMDY3LDcuNjQxLTE3LjA2NywxNy4wNjdzNy42NDEsMTcuMDY3LDE3LjA2NywxNy4wNjdjMjguMjc3LDAsNTEuMi0yMi45MjMsNTEuMi01MS4yVjUxLjIgICAgQzQ3Ny44NjcsMjIuOTIzLDQ1NC45NDQsMCw0MjYuNjY3LDB6Ii8+ICA8L2c+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==);
    width: 22px;
    height: 12px;
    background-color: transparent;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    display: inline-flex;
    margin-left: 5px;
    cursor: pointer;
    position: relative;
    filter: brightness(0.5);
	}
	.btn-copy .tooltiptext{
    display: none;
    width: 116px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 9px 6px;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    font-size: 12px;
    top: -50px;
    left: 0;
	}
	@media screen and (max-width:560px){
		.category-8307  .catalog__content .catalog__content-head .open_mob_filter{
			display: none;
		}
		#product__grid #listing_app.product__item, 
		#product__grid .app_wrap{
			margin-top: 0 !important;
			margin-bottom: 10px !important
		}
		.product__grid{
			justify-content: space-between;
		}
		.product__item, .manufacturer-list .manufacturer-content ul li:not(.list), 
		#content-search .catalog__content .product__item, #content-news-product .catalog__content .product__item{
			flex-basis: 49%;
			margin-bottom: 10px;
			padding: 10px
		}
		.product__item:hover .product__btn-cart button, .product__item:hover .product__btn-cart a,
		.product__item .product__btn-cart button, .product__item .product__btn-cart a{
			font-size: 0;
		}
		.product__rating{
			display: none
		}
		.product__title a {
		    font-size: 12px;
			line-height: 14px;
			display: -webkit-box;
			-webkit-line-clamp: 3;
			-webkit-box-orient: vertical;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		.product__item .product__delivery{
			font-size: 10px;
			line-height: 1;
		}
		.product__label > div{
			font-size: 10px;
		    padding: 4px 6px;
		    line-height: 1;
		    height: auto
		}
		/*.product__item .price__sale {
		    right: 10px;
		    left: inherit;
		    top: 10px;
		    font-size: 10px;
		    padding: 4px 6px;
		    line-height: 1;
		}
		.product__photo{
			margin-bottom: 10px
		}
		.product__price-new {
			font-size: 15px;
		    line-height: 1;
		    margin: 0
		}
		.product__price-old {
		    font-size: 12px;
		    line-height: 1;
		    margin: 0;
		    align-self: start;
		}
		.product__price {
		    display: flex;
		    flex-wrap: wrap;
		    align-self: start;
		    flex-direction: column;
		    justify-content: start;
		    align-items: start;
		    text-align: left;
		}
		.product__item .product__btn-cart .number__product__block{
			display: none
		}
		 .product__item .product__btn-cart button{
		 	padding-left: 0;
		 	width: 30px;
			height: 30px;
			display: flex;
			align-items: center;
			justify-content: center;
		 }
		  .product__item .product__btn-cart button svg{
		  	margin: 0 !important;
		  	width: 15px;
		  	height: 15px
		  }
		 .product__item .product__btn-cart {
		    position: absolute;
		    margin: 0;
		    z-index: 1;
		    width: auto;
		    right: 10px;
		    bottom: 10px;
		    height: 30px
		}
		.product_add-favorite {
		    top: 10px;
		    width: 40px;
		    height: 35px;
		    bottom: initial;
		    left: 10px;
		    flex-direction: column;
		}
		.product_add-favorite button{
			margin-left: 0 !important;
			margin-top: 0 !important;
			font-size: 15px !important;
		}
		.product_add-favorite button i{
			font-size: 15px !important;
		}
		.product_add-favorite button svg{
			width: 25px;
			height: 25px
		}
		.product__item:hover .product__btn-cart{
			width: auto
		}
		.product__item:hover .product__btn-cart button{
			padding-left: 0
		}
		.product__item:hover .product__price{
			flex-direction: column;
		}
		.product__item .product__info{
			padding-bottom: 0 !important
		}*/
	}

	@media screen and (max-width:560px){
		#categories-photo{
			flex-direction: row;
		}
	}
	@media screen and (min-width: 768px) {
		.sticky {
		  	position: fixed;
		  	z-index: 101;
		}
		.stop {
		  	position: relative;
		  	z-index: 101;
		}
	}
    @media screen and (min-width: 1280px){
        .product__item.tpl_list .product__line__promocode {
            margin-top: 25px;
            display: none;
      }
        .product__item.tpl_list .product__info {
            position: relative;
            top: 0;
            right: 0;
            left: 0
        } 
        .product__item.tpl_list:hover .product__info {
            position: relative;
            top: 0;
            right: 0;
            left: 0
        }
    }
    .product__item.tpl_list .product__info .reward_wrap {
        top: 6px;
        right: 0;
    }

</style>
<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/breadcrumbs.tpl')); ?>
<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/intersections_list.tpl')); ?>
<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/subcategory_list.tpl')); ?>
<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/subactions_list.tpl')); ?>

<?php echo $content_top; ?><div id="mfilter-content-container">

	<!--tags-->
	<!-- <div class="tags">
		<div class="wrap">
		<div class="tags__row">
		<a href="#" class="tags__link">Классика</a> <a href="#" class="tags__link">Лофт</a>
		<a href="#" class="tags__link">Вино</a> <a href="#" class="tags__link">Чай и кофе</a>
		<a href="#" class="tags__link">Модерн</a> <a href="#" class="tags__link">Ретро и винтаж</a>
		<a href="#" class="tags__link">Полезные мелочи</a> <a href="#" class="tags__link">Индастриал</a>
		<a href="#" class="tags__link">Джем/компот</a> <a href="#" class="tags__link">Выпечка и десерты</a>
		<a href="#" class="tags__link">Завтрак</a> <a href="#" class="tags__link">Гриль</a>
		<a href="#" class="tags__link">Эко-friendly</a> <a href="#" class="tags__link">Скандинавия</a>
		<a href="#" class="tags__link">Салат</a>
		</div>
		</div>
	</div> -->
	<!--/tags-->

	<!--catalog-->
	<?php include($this->checkTemplate(dirname(__FILE__), '/../structured/product_list.tpl')); ?>
	<!--/catalog-->

	<script>

		$(document).ready(function () {

			if ((typeof fbq !== 'undefined')){
				fbq('track', 'ViewContent',
				{
					content_type: 'product_group',
					content_ids: '<?php echo $category_id; ?>'
				});
			}

			$('.mob-menu__btn').click(function () {
				if($(this).hasClass("open")){
					$('.mfilter-free-button').removeClass('hide-btn');
					} else {
					$('.mfilter-free-button').addClass('hide-btn');
				}
			});

		});

	</script>
</div>



<?php if ($this->config->get('site_position') == '1') { ?>
	<?php echo $content_bottom; ?>
<?php } ?>


<script src="/catalog/view/theme/dp/js/jquery.ui.touch-punch.min.js"></script>

<script type="text/javascript">
	function copytext(el) {
		var $tmp = $("<textarea>");
		$("body").append($tmp);
		$tmp.val($(el).text()).select();
		document.execCommand("copy");
		$tmp.remove();
		$(el).next('.btn-copy').find('.tooltiptext').show();
		setInterval(function(){
			$(el).next('.btn-copy').find('.tooltiptext').hide();
		}, 1500);
	}  
if ($(window).width() >= '768'){
	(function(){
	let _hMenu = $('.top-menu').innerHeight()
	var a = document.querySelector('#column-left'), b = null, K = null, Z = 0, P = _hMenu, N = 10;  

	window.addEventListener('scroll', Ascroll, false);
	document.body.addEventListener('scroll', Ascroll, false);


    document.querySelectorAll('.accordion__title').forEach((e) => {
	  	console.log(e);
		e.addEventListener('click', function(){
			setTimeout(function(){
				window.scrollBy(0, 1);
			},300);
		}, false);
	  
	});




	function Ascroll() {
	  var Ra = a.getBoundingClientRect(),
	      R1bottom = document.querySelector('.catalog__content').getBoundingClientRect().bottom;
	  if (Ra.bottom < R1bottom) {
	    if (b == null) {
	      var Sa = getComputedStyle(a, ''), s = '';
	      for (var i = 0; i < Sa.length; i++) {
	        if (Sa[i].indexOf('overflow') == 0 || Sa[i].indexOf('padding') == 0 || Sa[i].indexOf('border') == 0 || Sa[i].indexOf('outline') == 0 || Sa[i].indexOf('box-shadow') == 0 || Sa[i].indexOf('background') == 0) {
	          s += Sa[i] + ': ' +Sa.getPropertyValue(Sa[i]) + '; '
	        }
	      }
	      b = document.createElement('div');
	      b.className = "stop";
	      b.style.cssText = s + ' box-sizing: border-box; width: ' + a.offsetWidth + 'px;';
	      a.insertBefore(b, a.firstChild);
	      var l = a.childNodes.length;
	      for (var i = 1; i < l; i++) {
	        b.appendChild(a.childNodes[1]);
	      }
	      a.style.height = b.getBoundingClientRect().height + 'px';
	      a.style.padding = '0';
	      a.style.border = '0';
	    }
	    var Rb = b.getBoundingClientRect(),
	        Rh = Ra.top + Rb.height,
	        W = document.documentElement.clientHeight,
	        R1 = Math.round(Rh - R1bottom),
	        R2 = Math.round(Rh - W);
	    if (Rb.height > W) {
	      if (Ra.top < K) {  // скролл вниз
	        if (R2 + N > R1) {  // не дойти до низа
	          if (Rb.bottom - W + N <= 20) {  // подцепиться
	            b.className = 'sticky';
	            b.style.top = W - Rb.height - N + 'px';
	            Z = N + Ra.top + Rb.height - W;
	          } else {
	            b.className = 'stop';
	            b.style.top = - Z + 'px';
	          }
	        } else {
	          b.className = 'stop';
	          b.style.top = - R1 +'px';
	          Z = R1;
	        }
	      } else {  // скролл вверх
	        if (Ra.top - P < 0) {  // не дойти до верха
	          if (Rb.top - P >= 0) {  // подцепиться
	            b.className = 'sticky';
	            b.style.top = P + 'px';
	            Z = Ra.top - P;
	          } else {
	            b.className = 'stop';
	            b.style.top = - Z + 'px';
	          }
	        } else {
	          b.className = '';
	          b.style.top = '';
	          Z = 0;
	        }
	      }
	      K = Ra.top;
	    } else {
	      if ((Ra.top - P) <= 0) {
	        if ((Ra.top - P) <= R1) {
	          b.className = 'stop';
	          b.style.top = - R1 +'px';
	        } else {
	          b.className = 'sticky';
	          b.style.top = P + 'px';
	        }
	      } else {
	        b.className = '';
	        b.style.top = '';
	      }
	    }
	    window.addEventListener('resize', function() {
	      a.children[0].style.width = getComputedStyle(a, '').width
	    }, false);
	  }
	}
	})()
}
</script>
<?php echo $footer; ?>