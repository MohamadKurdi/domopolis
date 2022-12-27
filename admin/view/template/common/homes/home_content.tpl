<style>
	.admin_home{width:100%;}
	.admin_home tr td{text-align:center;width: 20%;}
	.admin_home tr td a{color:#3a4247;}
	.link-list {
	display: block;
	width: 100%;;
	margin: 0;
	padding: 0;
	}
	.link-list li {
	display: block;
	float: left;
	text-align: left;;
	margin: 5px 3px 0 0 ;
	}
	
	.link-list li a {
	padding: 10px;
	border: 1px solid #cccccc;
	border-radius: 3px;
	box-shadow: 0 3px 3px #cccccc;
	}
	
	.link-list li a:hover {
	box-shadow: 0 3px 3px #999999;
	}
	
	.admin_button {    position: relative;width: 200px;height: 152px;margin-right:10px; margin-bottom:10px;float:left; text-align:center;text-align:center;}
	._green{ border: 0px solid #1f4962;}
	._orange{border: 0px solid #1f4962;}
	.admin_button i{font-size:56px;opacity: 0.8;padding: 20px;}
	.admin_button a{text-decoration:none;}
	.home_tabs_style{height: 35px;background-color: initial;}
	.content_opacity{background-color: #4ea24e;opacity: 1;width: 100%;height: 100%;}
	.content_opacity_color{background-color: #e4c25a;opacity: 1;width: 100%;height: 100%;}
	.admin_button:hover .content_opacity,.admin_button:hover .content_opacity_color, .admin_button:hover i, .admin_button:hover span{opacity: 1; color:white;}
	._green:hover {border: 0px solid #4ea24e;}
	._orange:hover {border: 0px solid #e4c25a;;}
	.content_position{position: absolute;top: 0;left: 0;right: 0;}
</style>
<table class="admin_home">
	<tr>
		<td>
			<div class="admin_button _green">
				<div  class="content_opacity ">
				</div>
				<div class="content_position">
					<a href="<?php echo $keyworder_link; ?>">
						<i class="fa fa-keyboard-o"></i><br />
						<span class="home_tabs_style">Связка производитель - категория (keyworder)</span>
					</a>
				</div>
			</div>
			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $category_link ?>">
						<i class="fa fa-list"></i><br />
						<span class="home_tabs_style">Категории</span>
					</a>
				</div>
			</div>

			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $product ?>">
						<i class="fa fa-bars"></i><br />
						<span class="home_tabs_style">Товары</span>
					</a>
				</div>
			</div>

			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $batch_editor_link ?>">
						<i class="fa fa-pencil"></i><br />
						<span class="home_tabs_style">Batch Editor v0.2.3 - пакетное редактирование товаров</span>
					</a>
				</div>
			</div>
			
			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $csv_pro_url ?>">
						<i class="fa fa-file-text-o"></i><br />
						<span class="home_tabs_style">CSV Price Pro import/export 3</span>
					</a>
				</div>
			</div>
			
			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $information_link; ?>">
						<i class="fa fa-info-circle"></i><br />
						<span class="home_tabs_style">Информационные статьи</span>
					</a>
				</div>
			</div>
			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $faq_url; ?>">
						<i class="fa fa-question-circle"></i><br />
						<span class="home_tabs_style">Frequently Asked Questions</span>
					</a>
				</div>
			</div>
			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $lp_link; ?>">
						<i class="fa fa-info"></i><br />
						<span class="home_tabs_style">Посадочные страницы</span>
					</a>       
				</div>
			</div>

			<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $addasin; ?>">
						<i class="fa fa-amazon"></i><br />
						<span class="home_tabs_style">Ручное добавление с Amazon</span>
					</a>       
				</div>
			</div>

			<div class="admin_button _green">
				<div  class="content_opacity">
				</div>
				<div class="content_position">
					<a href="<?php echo $product_deletedasin; ?>">
						<i class="fa fa-amazon"></i><br />
						<span class="home_tabs_style">Удаленные ASIN</span>
					</a>       
				</div>
			</div>
			<?php } ?>

			<div class="clr"></div>
		</td>
	</tr>
</table>
<table>
	<tr>
		<td>
			<h1>Модули товаров</h1>
			<div class="admin_button _orange">
				<div  class="content_opacity_color">
				</div>
				<div class="content_position">
					<a href="<?php echo $module_product_alsopurchased ?>">
						<i class="fa fa-exclamation"></i>
						<span class="home_tabs_style">Также покупали</span>
					</a>
				</div>
			</div>
			<div class="admin_button _orange">
				<div  class="content_opacity_color">
				</div>
				<div class="content_position">
					<a href="<?php echo $module_product_bestseller ?>">
						<i class="fa fa-diamond"></i>
						<span class="home_tabs_style">Хиты продаж</span>
					</a>
				</div>
			</div>
			<div class="admin_button _orange">
				<div  class="content_opacity_color">
				</div>
				<div class="content_position">
					<a href="<?php echo $module_product_featured ?>">
						<i class="fa fa-thumbs-up"></i>
						<span class="home_tabs_style">Рекомендуемые</span>
					</a>
				</div>
			</div>
			<div class="admin_button _orange">
				<div  class="content_opacity_color">
				</div>
				<div class="content_position">
					<a href="<?php echo $module_product_latest ?>">
						<i class="fa fa-check-square-o"></i>
						<span class="home_tabs_style">Последние</span>
					</a>
				</div>
			</div>
			<div class="admin_button _orange">
				<div  class="content_opacity_color">
				</div>
				<div class="content_position">
					<a href="<?php echo $module_product_faproduct ?>">
						<i class="fa fa-star"></i>
						<span class="home_tabs_style">Рекомендуемые из групп</span>
					</a>
				</div>
			</div>
			<div class="admin_button _orange">
				<div  class="content_opacity_color">
				</div>
				<div class="content_position">
					<a href="<?php echo $module_product_featuredreview ?>">
						<i class="fa fa-comment"></i>
						<span class="home_tabs_style">Популярные с отзывами</span>
					</a>
				</div>
			</div>
		</td>
	</tr>
	</table>					