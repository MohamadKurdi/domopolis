<?php if ($home_cart_products) { ?>
		<style type="text/css">
			#home-cart .container-cart{
				display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -webkit-box-orient: horizontal;
			    -webkit-box-direction: normal;
			    -ms-flex-direction: row;
			    flex-direction: row;
			    -webkit-box-align: center;
			    -ms-flex-align: center;
			    align-items: center;
			    margin: 20px 0;
			    padding: 8px 24px;
			    border: 1px solid #eaeaea;
			    border-radius: 3px;
			}
			#home-cart-products-in-cart-products{
				display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -webkit-box-orient: horizontal;
			    -webkit-box-direction: normal;
			    -ms-flex-direction: row;
			    flex-direction: row;
			    -webkit-box-align: center;
			    -ms-flex-align: center;
			    align-items: center;
			    -webkit-box-pack: center;
			    -ms-flex-pack: center;
			    justify-content: center;
			    -ms-flex-wrap: wrap;
			    flex-wrap: wrap;
			    height: 56px;
			    overflow: hidden;
			    margin: auto;
			    max-width: 47%;
			}
			#home-cart-products-in-cart-products .product-name{
				font-size: 13px;
				overflow: hidden;
				width: calc(100% - 180px);
				line-height: 18px;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
				text-overflow: ellipsis;
			}
			#home-cart-products-in-cart-products div:first-child{
				margin-left: 0;
			}
			#home-cart-products-in-cart-products div {
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -webkit-box-orient: horizontal;
			    -webkit-box-direction: normal;
			    -ms-flex-direction: row;
			    flex-direction: row;
			    -webkit-box-align: center;
			    -ms-flex-align: center;
			    align-items: center;
			    justify-content: center;
			    max-width: 75%;
			    margin-left: 8px;
			}
			#home-cart-products-in-cart-products div img {
			    display: -webkit-inline-box;
			    display: -ms-inline-flexbox;
			    display: inline-flex;
			    -webkit-box-align: center;
			    -ms-flex-align: center;
			    align-items: center;
			    -webkit-box-pack: center;
			    -ms-flex-pack: center;
			    justify-content: center;
			    -ms-flex-negative: 0;
			    flex-shrink: 0;
			    width: 56px;
			    height: 56px;
			    min-width: 56px;
			    min-height: 56px;
			    padding: 4px;
			    -webkit-box-sizing: border-box;
			    box-sizing: border-box;
			    max-width: 100%;
			    max-height: 100%;
			    vertical-align: middle;
			    object-fit: cover;
			}


			#home-cart-text-products-in-cart-line-2{
				color: #888;
				font-size: 15px;
				margin-top: 4px;
			}
			#home-cart-text-products-in-cart-line-2 b{
				color: #333333;
				font-weight: 500;
				font-size: 17px;
			}
			#home-cart-text-products-in-cart-line-1{
				 font-size: 20px;
			}
			#home-cart-products-in-cart-buttons-go-to-cart{
				-ms-flex-negative: 0;
			    flex-shrink: 0;
			    margin-left: auto;
			    font-size: 13px;
		        margin: 0;
			    padding: 0;
			    border: none;
			    background: none;
			    text-decoration: none;
			    color: #51a881;
			    cursor: pointer;
			    font-weight: 400;
			}
			#home-cart-products-in-cart-buttons-go-to-cart:hover{
				text-decoration: underline;
			}
			#home-cart-products-in-cart-buttons a{
			    -ms-flex-negative: 0;
			    flex-shrink: 0;
			    margin: 0 0 0 16px;
			   	background: #51a881;
			    color: #fff;
			    height: 32px;
			    line-height: 32px;
			    display: inline-block;
			    font-size: 14px;
			    text-decoration: none;
			    position: relative;
			    display: inline-block;
			    margin: 0;
			    padding-left: 16px;
			    padding-right: 16px;
			    text-align: center;
			    border: none;
			    outline: none;
			    box-sizing: border-box;
			    transition: all .2s ease-in-out;
			    margin-left: 15px;
				
			}
			#home-cart-products-in-cart-buttons a:hover{
				background: #7acaa6;
			}
			#close_home-cart{
				cursor: pointer;
				margin-left: 20px;
			}
			@media screen  and (max-width: 1100px){
				#home-cart .container-cart{
					position: relative;
					padding: 15px 24px;
				}
				#home-cart-products-in-cart-products .product-name,
				#home-cart-products-in-cart-buttons-go-to-cart{
					display: none;
				}
				#home-cart-products-in-cart-buttons a{
					margin-left: 0;
					margin-right: 20px;
				}
				#close_home-cart{
					position: absolute;
				    right: 15px;
				    top: 0;
				    bottom: 0;
				    margin: auto;
				}
				#home-cart-text-products-in-cart-line-1 {
				    font-size: 17px;
				}
				#home-cart-text-products-in-cart-line-2 {
				    font-size: 13px;
				    margin-top: 0;
				}
			}
			@media screen  and (max-width: 768px){
				#home-cart-products-in-cart-products,
				#home-cart-products-in-cart-buttons-go-to-cart{
					display: none;
				}
				#home-cart .container-cart{
					flex-direction: column;
					align-items: flex-start;
				}
				
				#home-cart-products-in-cart-buttons{
					width: 100%;
				}
				#home-cart-products-in-cart-buttons a{
					margin: 15px 0 0;
					display: block;
				}
				#close_home-cart{
					right: 15px;
					top: 15px;
					bottom: unset;
				}

			}
			



		</style>
		<div id="home-cart">
			<div class="wrap">
				<div class="container-cart">
					<div>
						<div id="home-cart-text-products-in-cart-line-1">
							<?php echo $home_cart_line_1; ?>
						</div>
						<div id="home-cart-text-products-in-cart-line-2">
							<?php echo $home_cart_line_2; ?>
						</div>
					</div>
					<div id="home-cart-products-in-cart-products">
						
							<?php foreach ($home_cart_products as $home_cart_product) { ?>
								<div>
									<a href="<?php echo $home_cart_product['href'] ?>"><img src="<?php echo $home_cart_product['thumb'] ?>"  loading="lazy"/></a>
								</div>

							<?php } ?>
							
							<?php if ($cart_has_one_product){ ?>
								<a href="<?php echo $home_cart_product['href'] ?>"><span class="product-name"><?php echo $cart_one_product_name; ?></span></a>
							<?php } ?>
					</div>
					<div id="home-cart-products-in-cart-buttons">
						<span id="home-cart-products-in-cart-buttons-go-to-cart" onclick="openCart();"><?php echo $home_cart_text_go_to_cart;?></span>
						<a href="<?php echo $home_cart_href_go_to_checkout;?>" ><?php echo $home_cart_text_go_to_checkout;?></a>
						<svg id="close_home-cart" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.97333 4.99992L9.66666 8.69325V9.66659H8.69333L5 5.97325L1.30666 9.66659H0.333328V8.69325L4.02666 4.99992L0.333328 1.30659V0.333252H1.30666L5 4.02659L8.69333 0.333252H9.66666V1.30659L5.97333 4.99992Z" fill="#BDBDBD"/>
						</svg>
					</div>
				</div>
			</div>	
		</div>
		<script>
			$('#close_home-cart').on('click', function(){
		    	$(this).closest('#home-cart').hide();
		    });
		</script>	
<?php } ?>