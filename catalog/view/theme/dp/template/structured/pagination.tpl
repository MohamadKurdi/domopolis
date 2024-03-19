<!--pages-->
<?php if ($pagination) { ?>
	
	<div class="pages" id="pagination__wrap">
		
		<?php if (!empty($pagination_text)) { ?>
			<div class="text-center text-pagination-info"><?php echo $pagination_text; ?></div>
		<?php } ?>
		
		
		<button id="load-more_btn" class="pages__show-more btn-border" onclick="getMoreProducts();"><?php echo $text_show_more; ?> <?php echo $limit; ?></button>
		<div class="pages__navigation">
			<div class="pages__counts">
				<?php echo $pagination; ?>
			</div>
		</div>
	</div>
	
	<script>
		
		if (!$("#pagination-next").length){
			$('#load-more_btn').hide();
		}
		function getMoreProducts(){	
			
			var $button = $('#load-more_btn');
			var $productGrid = $('#product__grid');
			var $paginationWrap = $('#pagination__wrap');
			
			var _href = $('#pagination-next').attr('href');							
			var _buttonDefaultText = $button.text();
			
			var positionPagination = $('#pagination__wrap').last();
			var _hTopMenu = $('.top-menu').innerHeight();
			
			var offsetPaginationTop = positionPagination.offset();
			
			console.log("top: " + offsetPaginationTop.top);	
			
			
			
			
			
			$.ajax({
				type : 'GET',
				url : _href,
				dataType : 'html',
				beforeSend : function(){		
					$button.addClass('load_product');						
					$button.html("<img src='/catalog/view/theme/dp/img/load_more_white.svg'>");
					$button.on('click', function(){										
					});
					$productGrid.css({ "opacity": "0.5" }).addClass('load_product_grid');
				},
				complete : function(){									
					$button.text(_buttonDefaultText);
					$productGrid.css({ "opacity": "1" }).removeClass('load_product_grid');
					$button.on('click', function(){										
						getMoreProducts();
					});
					$button.removeClass('load_product');
					history.pushState({ foo: "bar" }, '', _href);
					
					if (!$("#pagination-next").length){
						$('#load-more_btn').hide();
					}
					
					
				},
				error : function(e){
					console.log(e);
				},
				success : function(_html){
					var $html = $(_html);
					var _newPagination = $html.find('div[id=pagination__wrap]').html();
					$paginationWrap.html(_newPagination);
					
					$("html, body").animate({scrollTop: offsetPaginationTop.top-_hTopMenu}, 400);	
					
					$sectionElem = $html.find('section#section-catalog');
					
					$sectionElem.find('div[class~=product__item]').each(function(idx, elem){
						if (localStorage.getItem('short_tpl') == 'tpl_list' || $(elem).hasClass('tpl_list')){
							$productGrid.append('<div class="product__item tpl_list">' + elem.innerHTML + '</div>');
							} else {
							$productGrid.append('<div class="product__item">' + elem.innerHTML + '</div>');
						}
					});					
				}
			});
			
		}					
	</script>
<?php } ?>
<!--/pages-->
