<style type="text/css">
	.category_wall_allcat_container{
		display: grid;
		grid-template-columns: repeat(5, 1fr);
		grid-gap: 15px;
		text-align: center;
		margin-bottom: 30px;
	}
	.category_wall_allcat_container .category_wall_allcat_item{
		padding: 15px;
		border: 1px solid transparent;
		transition: .3s ease-in-out;
	}
	.category_wall_allcat_container .category_wall_allcat_item:hover{
		background: #fff;
		border: 1px solid #eae9e8;
		box-shadow: 0 0 30px rgba(0,0,0,.1);		
	}
	.category_wall_allcat_container .category_wall_allcat_item .img svg{
		width: 120px;
		height: 120px;
		
	}
	.category_wall_allcat_container .category_wall_allcat_item .img svg path{
		stroke-width: 38px;
	}
	.category_wall_allcat_container .category_wall_allcat_item .title{
		font-size: 21px;
		/*margin: 15px 0 15px 0;*/
		margin: 15px 0 0 0;
		display: block;
		/*border-bottom: 1px solid #51a881;*/
		padding-bottom: 10px;
		/*font-weight: 500;*/
		transition: .3s ease-in-out;
	}
	.category_wall_allcat_container .category_wall_allcat_item:hover .title{
		color: #51a881;
	}
	.category_wall_allcat_container .category_wall_allcat_item .children_block a{
		display: block;
		margin-bottom: 3px;
		font-size: 16px;
	}
	@media screen and (max-width: 1400px) {
		.category_wall_allcat_container .category_wall_allcat_item .title{
			font-size: 18px;
		}
		.category_wall_allcat_container .category_wall_allcat_item .img svg{
			width: 90px;
			height: 90px;
			
		}
		
	}
	@media screen and (max-width: 992px) {
		.category_wall_allcat_container{
			grid-template-columns: repeat(3, 1fr);
		}
		
	}
	@media screen and (max-width: 567px) {
		.category_wall_allcat_container{
			grid-template-columns: repeat(2, 1fr);
		}
		.category_wall_allcat_container .category_wall_allcat_item .title{
			font-size: 17px;
		}
		.category_wall_allcat_container .category_wall_allcat_item .img svg{
			width: 100px;
			height: 100px;
		}

	}
	@media screen and (max-width: 480px) {
		.category_wall_allcat_container .category_wall_allcat_item .img svg{
			width: 45px;
			height: 45px;
		}
		.category_wall_allcat_container{
			/*grid-template-columns: repeat(2, 1fr);*/
			display: flex;
			overflow-x: auto;
			width: 100%;
			grid-gap: inherit;
			scrollbar-color: transparent !important;
			scrollbar-width: transparent !important;
		}
		.category_wall_allcat_container::-webkit-scrollbar-thumb {
		    background-color: transparent !important;
		    scrollbar-color: transparent !important;
		}
		.category_wall_allcat_container .category_wall_allcat_item {
		    width: 90px;
			display: inline-block;
			min-width: 86px;
			height: 120px;
			margin-right: 2px;
			padding: 0;

		}
		.category_wall_allcat_container .category_wall_allcat_item:last-child{
			margin-right: 0;
		}
		.category_wall_allcat_container .category_wall_allcat_item .title{
			font-size: 12px !important;
			line-height: 14px !important;
			text-overflow: ellipsis;
			margin: 0;
			background: transparent;
			text-align: center;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			padding-bottom: 2px;
			word-wrap: break-word;
		}
		.category_wall_allcat_container .category_wall_allcat_item a {
		    display: flex;
		    flex-direction: column;
		    height: 100%;
		}
		.category_wall_allcat_container .category_wall_allcat_item .img {
		    width: 70px;
			height: 70px;
			margin: auto;
			    margin-top: auto;
			    margin-bottom: auto;
			margin-top: 3px;
			margin-bottom: 5px;
			border-radius: 50px;
			border: 2px solid #53a87f;
			background: transparent;
			mask-composite: exclude;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.category_wall_allcat_container .category_wall_allcat_item:hover {
		    background: #fff;
		    border: 1px solid transparent;
		    box-shadow: none;
		}
	}
</style>

<?php if ($categories) { ?>
	<div class="wrap categories_home">
		<h2 class="title center" style="text-transform: uppercase;font-weight: 400;font-size: 28px;">Каталог</h2>
		<div class="category_wall_allcat_container">
		    <?php foreach ($categories as $category) { ?>
				<a href="<?php echo $category['href']; ?>" class="category_wall_allcat_item">
					<div class="img">
						<?php echo $category['menu_icon'];  ?>
					</div>
					<span class="title"><?php echo $category['name']; ?></span>
					<!-- <div class="children_block">
						<?php if ($category['children']) { ?>
							<?php foreach ($category['children'] as $children ): ?>				
								<a href="<?=$children['href']; ?>" class="btn-children">
									<?=$children['name'] ?>
								</a>		
							<?php endforeach; ?>
						<?php } ?>
					</div>
					<a href="<?php echo $category['href']; ?>" class="all_category btn btn-acaunt">Все категории</a> -->
				</a>
			<?php } ?>
		</div>	
	</div>		
<?php } ?>	
