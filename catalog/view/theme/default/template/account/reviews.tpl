<?php echo $header; ?><?php echo $column_right; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<style>
.ajax-edit{display:none;}
.ajax-edit textarea{width:100%;cursor:text;}
.ajax-edit +div{cursor:pointer;}

.box.box_purchased{
	display: flex;
	flex-direction: column;

}
.reviews_block .reviews_title,
.box.box_purchased .box-heading{
	font-size: 22px;
	display: block;
	margin-bottom: 20px;
	font-weight: 500;
}

.box-content .box-product {
	display: flex;
	flex-direction: column;
	margin-bottom: 30px;
}
.reviews_block .reviews-list_wrap .reviews-list,
.box-content .box-product .box-product-item{
	display: flex;
	flex-direction: row;
    align-items: center;
    padding: 16px;
    border: 1px solid #ccc;
    border-bottom: 0;
}
.reviews_block .reviews-list_wrap .reviews-list .product-image,

.box-content .box-product .box-product-item .image{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    height: 40px;
    width: 40px;
    margin-right: 16px;
}
.reviews_block .reviews-list_wrap .reviews-list .product-image a,
.box-content .box-product .box-product-item .image a{
	display: flex;
}
.reviews_block .reviews-list_wrap .reviews-list .product-image img,
.box-content .box-product .box-product-item .image img{
	max-height: 100%;
    max-width: 100%;
}
.reviews_block .reviews-list_wrap .reviews-list:first-child,
.box-content .box-product .box-product-item:first-child{
	border-radius: 4px 4px 0 0;
}
.reviews_block .reviews-list_wrap .reviews-list:last-child,
.box-content .box-product .box-product-item:last-child{
	border-radius: 0 0 4px 4px;
	border-bottom: 1px solid #ccc;
}

.reviews_block .reviews-list_wrap .reviews-list .product-name,

.box-content .box-product .box-product-item .name{
	margin-right: 25px;
}
.reviews_block .reviews-list_wrap .reviews-list .product-name a,
.box-content .box-product .box-product-item .name a{
	font-size: 16px;
	color: #51a881;
	font-weight: 500;
}
.box-content .box-product .box-product-item .add_reviews{
	background: #51a881;
	color: #fff;
	font-size: 16px;
    height: 30px;
    line-height: 30px;
    padding: 0 15px;
    white-space: nowrap;
    margin-left: auto;
}
.box-content .box-product .box-product-item .add_reviews:hover{
	opacity: .8;
}

.reviews_block .text_empty{
	font-size: 14px;
}
.reviews_block .reviews-list_wrap .reviews-list,
.reviews_block .reviews-list_wrap{
	display: flex;
	flex-direction: column;
}
.reviews_block .reviews-list_wrap .reviews-list{
	margin-bottom: 0;
	box-shadow: none;
}
.reviews_block .reviews-list_wrap .reviews-list .detail_reviews{
	display: none;
}
.reviews_block .reviews-list_wrap .reviews-list.open .detail_reviews{
	display: flex;
	position: relative;
	flex-direction: column;
}
.reviews_block .reviews-list_wrap .reviews-list .btn_detail{
	width: 24px;
	background: transparent;
	border: 0;
	padding: 0;
	transition: .15s ease-in-out;
	margin-left: auto;
}
.reviews_block .reviews-list_wrap .reviews-list.open .btn_detail{
	transform: rotate(180deg);
}

.reviews_block .reviews-list_wrap .reviews-list .head .pre_review{
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
	max-width: 45%;
	margin-right: 25px;
}
.reviews_block .reviews-list_wrap .reviews-list.open .pre_review{
	display: none;
}
.reviews_block .reviews-list_wrap .reviews-list .head {
	display: flex;
	align-items: center;
	width: 100%;
}
.reviews_block .reviews-list_wrap .reviews-list .detail_reviews .reviews-main{
	border: 1px solid #ccc;
	padding: 15px;
	margin-top: 15px;

}
.reviews_block .reviews-list_wrap .reviews-list .detail_reviews .reviews-main .text-review{
	font-size: 15px;
}
.reviews_block .reviews-list_wrap .reviews-list .detail_reviews .reviews-main .review-status{
	font-size: 12px;
	font-weight: 500;

}
.reviews_block .reviews-list_wrap .reviews-list .detail_reviews .reviews-main .review-status.status-1{
	color: #51a881;
}
.reviews_block .reviews-list_wrap .reviews-list .detail_reviews .reviews-main .review-status.status-0{
	color: #e16a5d;
}
.review-content .reviews-list .review-content .btn-edit{
	font-size: 12px;
	font-weight: 500;
	top: initial;
	bottom: 15px;
	left: 20px;
	right: initial;
	margin-left: 18px;
}

.review-content .reviews-list .review-content .btn-edit i{
	position: relative;
	left: -18px;
}
.review-content .reviews-list .ajax-edit.edit{
	position: relative;
}
.review-content .reviews-list .ajax-edit.edit .btn-edit{
	bottom: initial;
	top: -55px;
	left: 15px;
	margin: 0;
}
@media screen and (max-width: 560px) {
	 .box-content .box-product .box-product-item{
	 	flex-wrap: wrap;
	 	border-left: 0;
	 	border-right: 0;
	 	border-top: 0;
	 	border-bottom: 1px solid #ccc;
	 }
	 .box-content .box-product .box-product-item .image{
	 	flex-basis: 40px;
	 }
	 .box-content .box-product .box-product-item .name{
	 	flex-basis: calc(100% - 56px);
	margin-right: 0;
	 }
	 .box-content .box-product .box-product-item .add_reviews{
		flex-basis: 100%;
		margin-top: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		height: 35px;
		line-height: 35px;
	 }
	 .reviews_block .reviews-list_wrap .reviews-list .head .pre_review{
	 	display: none
	 }
}
</style>

<section id="content" class="review-content account_wrap">
	<div class="wrap two_column">
		<div class="side_bar">
			<?php echo $column_left; ?>
		</div>
		<div class="account_content">
			<?php echo $content_top; ?>



			<?php if ($purchased_products) { ?>
				<div class="box box_purchased">
					<div class="box-heading"><?php echo $text_purchased_products; ?></div>
					<div class="box-content">
						<div class="box-product">
							<?php foreach ($purchased_products as $product) { ?>
								<div class="box-product-item">
									<?php if ($product['image']) { ?>
										<div class="image">
											<a href="<?php echo $product['href']; ?>">
												<img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
											</a>
										</div>
									<?php } ?>
									<div class="name">
										<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
									</div>
									<a href="<?php echo $product['href']; ?>#reviews_btn" class="add_reviews"><?php echo $set_review ?></a>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="reviews_block">				
				<?php if ($reviews) { ?>
					<div class="reviews-list_wrap">
						<?php foreach ($reviews as $review) { ?>  
							<div class="reviews-list">
								<div class="head">
									<div class="product-image">
										<a href="<?php echo $review['href']; ?>">
											<img src="<?php echo $review['product_image'] ?>" />
										</a>
									</div>
									<div class="product-name">
										<a href="<?php echo $review['href']; ?>"><?php echo $review['product_name']; ?></a>
									</div>
									<div class="pre_review text-<?php echo $review['review_id']; ?>">
										<?php echo $review['text'] ?>
									</div>
									<button class="btn_detail">
										<svg width="14" height="7" viewBox="0 0 14 7" fill="none" xmlns="https://www.w3.org/2000/svg">
											<path d="M1 1L7 6L13 1" stroke="#FFC34F" stroke-width="2" stroke-linejoin="round"></path>
										</svg>
									</button>

								</div>
								<div class="detail_reviews">
									<div class="reviews-main">
										<div class="text-review text-<?php echo $review['review_id']; ?>">
											<?php echo $review['text'] ?>
										</div>
										<div class="review-status <?php echo $review['class-status']; ?>">
											<?php echo $review['status']; ?>
										</div>
									</div>
									<div class="review-content">
										<?php if($reviews_edit==1) { ?>
											<div class="edit-form">
												<div class="ajax-edit" id="text-<?php echo $review['review_id']; ?>" value="<?php echo $review['review_id']; ?>">
													<textarea name="text" id="texteditor" rows="5"><?php echo $review['text']; ?></textarea><br>
													<span class="btn-success btn btn-acaunt do-popup-element" data-target="notification_reviews"  onclick="save_review(<?php echo $review['review_id']; ?>)"><?php echo $button_save; ?></span>&nbsp
													<span class="btn-edit" onclick="close_review(<?php echo $review['review_id']; ?>)"; return false;><?php echo $button_back; ?></span>

												</div>
												<span class="btn-edit"><?php echo $button_edit; ?><i class="fa fa-pencil"></i></span>
												<span class="btn-edit" title="Редактировать"><i class="fas fa-pen"></i></span>
											</div>
										<?php } ?>
									</div>
								</div>
								
							</div>
						<?php } ?>
						
					</div>		
					<div class="pagination"><?php echo $pagination; ?></div>
				<?php } else { ?>
					<div class="content text_empty"><?php echo $text_empty; ?></div>
				<?php } ?>
			</div>
			

			<?php echo $content_bottom; ?>

		</div>
	</div>
</section>

<?php if($reviews_edit==1) { ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.ajax-edit').each(function(index, wrapper) {
				$(this).siblings().click(function() {
					$(wrapper).show(300);
					$(wrapper).addClass('edit');
					$(wrapper).siblings().hide();
				})
			});
		})

		function save_review(id) {
											
			var textarea_text = $("#text-"+id+" textarea[name=\'text\']");
			var text = textarea_text.val();								
			$.ajax({
				url: '/index.php?route=account/reviews/updateReview',
				type: 'post',
				dataType: 'json',
				data: 'text=' + encodeURIComponent(text) + '&review_id=' + encodeURIComponent(id),
				success: function(data) {
					$('.success, .warning').remove();
					if (data['error']) {
						$('#notification_reviews').click();
						$('#notification').html('<div class="warning" style="color:red; font-weight:500;">' + data['error'] + '</div>');
					}			
					if (data['success']) {
						$('#notification_reviews').click();
						$('#notification').html('<div class="success" style="color:green; font-weight:500;">' + data['success'] + '</div>');
										
						$('.text-'+id).html(text);
						close_review(id);				
					}
				}
			});      
		}

		function close_review(id) {
			$('.ajax-edit textarea').blur();
			$('#text-'+id).siblings().show();
			$('.ajax-edit').removeClass('edit');
			$('#text-'+id).hide(100);
		}



	</script>


<?php } ?>

<script>
	var reviews_item = document.querySelectorAll('.reviews-list_wrap .reviews-list');

	reviews_item.forEach(function(e){
		e.querySelector('.head').addEventListener('click', function () {
		    e.classList.toggle('open');
		});
	});
</script>
<div class="popup-form" id="notification_reviews">
	<div class="object">
	    <div class="overlay-popup-close"><i class="fas fa-times"></i></div>
		<div class="info-order-container">
			<div class="content" style="background: #fff;padding: 20px 30px 20px;">	
				<div id="notification"></div>
			</div>	
		</div>
	</div>
</div>
<?php echo $footer; ?>


