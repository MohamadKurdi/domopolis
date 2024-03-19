<?php if ($categories) { ?>
	<style type="text/css">
		.subcategory_list_new .categories-photo__row__collection{
			display: grid;
			grid-template-columns: repeat(4,minmax(0,1fr));
			gap: 30px;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item{
			background-position: center;
			background-repeat: no-repeat;
		    background-size: cover;
		    height: 190px;
		    position: relative;
		    padding: 16px 20px;
		    display: flex;
		    align-items: end;
			border-radius: 12px;
		}
		.subcategory_list_new .categories-photo__row__collection .main_link{
		    position: absolute;
		    left: 0;
		    top: 0;
		    width: 100%;
		    height: 100%;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item::before{
			content: '';
			border-radius: 12px;
			background: linear-gradient(360deg, #181A24 0%, rgba(24, 26, 36, 0) 100%);
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item .sub_category {
			position: absolute;
			left: 0;
			bottom: 0;
			width: 100%;
			padding: 20px;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item .sub_category .name{
			font-weight: 600;
			font-size: 18px;
			line-height: 22px;
			color: #EFF1F2;
			display: flex;
			align-items: flex-start;
			gap: 18px;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item .sub_category .name svg{
			margin-top: 6px;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover{
			z-index: 200;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover .sub_category .name{
			color: #404345;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover .sub_category{
			background: #FFFFFF;
			box-shadow: 0px 19px 41px rgba(0, 0, 0, 0.42);
			border-radius: 0 0 12px 12px;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover .sub_category::before{
			content: '';
			background-image: url(data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMiAxMiI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNmZmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5BcnRib2FyZCAxPC90aXRsZT48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0wLDEySDBaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNLjI1LDEySDEyVi4yNUExMS43NSwxMS43NSwwLDAsMSwuMjUsMTJaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTIsMGMwLC4wOCwwLC4xNywwLC4yNVYwWiIvPjwvc3ZnPg==);
			width: 12px;
			height: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			background-position: center;
			background-size: cover;
			position: absolute;
			left: 0;
			top: -12px;
			transform: scaleX(-1);
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover .sub_category::after{
			content: '';
			background-image: url(data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMiAxMiI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNmZmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5BcnRib2FyZCAxPC90aXRsZT48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0wLDEySDBaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNLjI1LDEySDEyVi4yNUExMS43NSwxMS43NSwwLDAsMSwuMjUsMTJaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTIsMGMwLC4wOCwwLC4xNywwLC4yNVYwWiIvPjwvc3ZnPg==);
			width: 12px;
			height: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			background-position: center;
			background-size: cover;
			position: absolute;
			right: 0;
			top: -12px;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item .sub_category .accordeon_subcat{
			display: none;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover .sub_category .accordeon_subcat{
			display: flex;
			flex-direction: column;
			position: absolute;
			background: #FFFFFF;
			box-shadow: 0 33px 40px rgba(0, 0, 0, 0.2);
			border-radius: 0 0 12px 12px;
			width: 100%;
			left: 0;
			top: calc(100% - 12px);
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover .sub_category .accordeon_subcat li a{
			display: flex;
			align-items: center;
			padding: 14px 20px;
			border-top: 1px solid #DDE1E4;
			font-weight: 500;
			font-size: 16px;
			line-height: 19px;
			color: #404345;
		}
		.subcategory_list_new .categories-photo__row__collection .category_item:hover .sub_category .accordeon_subcat li.show_more a{
			color: #3050C2;
		} 
		@media screen  and (max-width: 560px){
			.subcategory_list_new .categories-photo__row__collection .category_item{
				padding: 0;
				height: 158px;
			}
			.subcategory_list_new .categories-photo__row__collection{
				grid-template-columns: repeat(2,minmax(0,1fr));
				gap: 8px;
			}
			.subcategory_list_new .categories-photo__row__collection .category_item{
				border: 1px solid #DDE1E4;
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.07);
			}
			.subcategory_list_new .categories-photo__row__collection .category_item .sub_category{
				padding: 12px;
				position: relative;
				background: #FFFFFF;
				box-shadow: none;
				border-radius: 0 0 10px 11px;
				z-index: 100;
				height: 58px;
			}
			.subcategory_list_new .categories-photo__row__collection .category_item .sub_category .accordeon_subcat{
				display: none !important;
			}

			.subcategory_list_new .categories-photo__row__collection .category_item .sub_category::before {

			    content: '';
			    background-image: url(data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMiAxMiI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNmZmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5BcnRib2FyZCAxPC90aXRsZT48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0wLDEySDBaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNLjI1LDEySDEyVi4yNUExMS43NSwxMS43NSwwLDAsMSwuMjUsMTJaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTIsMGMwLC4wOCwwLC4xNywwLC4yNVYwWiIvPjwvc3ZnPg==);
			    width: 12px;
			    height: 12px;
			    display: flex;
			    align-items: center;
			    justify-content: center;
			    background-position: center;
			    background-size: cover;
			    position: absolute;
			    left: 0;
			    top: -12px;
			    transform: scaleX(-1);

			}
			.subcategory_list_new .categories-photo__row__collection .category_item .sub_category::after {
			  content: '';
			  background-image: url(data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMiAxMiI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNmZmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5BcnRib2FyZCAxPC90aXRsZT48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0wLDEySDBaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNLjI1LDEySDEyVi4yNUExMS43NSwxMS43NSwwLDAsMSwuMjUsMTJaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTIsMGMwLC4wOCwwLC4xNywwLC4yNVYwWiIvPjwvc3ZnPg==);
			  width: 12px;
			  height: 12px;
			  display: flex;
			  align-items: center;
			  justify-content: center;
			  background-position: center;
			  background-size: cover;
			  position: absolute;
			  right: 0;
			  top: -12px;
			}
			.subcategory_list_new .categories-photo__row__collection .category_item .sub_category .name {
			  	color: #404345;
			  	font-size: 14px;
				line-height: 17px;
				flex-direction: row-reverse;
				justify-content: space-between;
				align-items: center;
				display: -webkit-box;
			    -webkit-line-clamp: 2;
			    -webkit-box-orient: vertical;
			    overflow: hidden;
			    text-overflow: ellipsis;
			    position: relative;
			    padding-right: 20px;
			}
			.subcategory_list_new .categories-photo__row__collection .category_item .sub_category .name svg{
				transform: rotate(-90deg);
			    min-width: 8px;
			    height: 6px;
			    position: absolute;
			    right: 0;
			    top: 0;
			    bottom: 0;
			    margin: auto 0;
			}
		}
	</style>

	<div class="categories-list subcategory_list_new" style="margin-bottom: 15px">
	  	<div class="wrap">
	  		<div id="categories" class="categories-photo__row__collection">
	  			<?php foreach ($categories as $category) { ?>
					<div class="category_item" style="background-image: url(<?php echo $category['thumb']; ?>);cursor: pointer;" onclick="javascript:location.href='<?php echo $category['href']; ?>'">
						<a href="<?php echo $category['href']; ?>" class="main_link"></a>
						<div class="sub_category">
							<a href="<?php echo $category['href']; ?>" class="name">
								<svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2 2L8 8L14 2" stroke="#BABEC2" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								<?php echo $category['name']; ?>
							</a>
							<?php if (isset($category['children']) && $category['children']) { ?>
								<ul class="accordeon_subcat">
									<?php foreach (array_slice($category['children'], 0, 3)  as $child) { ?>
										<li>							
											<a href="<?php echo $child['href']; ?>">
												<?php echo $child['name']; ?>
											</a>											
										</li>
									<?php } ?>
									<li class="show_more">
										<a href="<?php echo $category['href']; ?>" >Показати ще</a>
									</li>
								</ul>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
	  		</div>
	  	</div>
	</div>
<? } ?>