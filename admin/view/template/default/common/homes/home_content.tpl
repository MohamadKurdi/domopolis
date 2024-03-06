<?php echo $header; ?>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">   
		<div class="content">
			<div id="template_bygroup">
				<table class="admin_home">
					<tr>
						<td>			
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

							<div class="clr"></div>
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

							<div class="clr"></div>
							<div class="admin_button _green">
								<div  class="content_opacity">
								</div>
								<div class="content_position">
									<a href="<?php echo $shortnames; ?>">
										<i class="fa fa-eur"></i><br />
										<span class="home_tabs_style">Экспортные названия</span>
									</a>       
								</div>
							</div>
						</td>
					</tr>
				</table>
				<div class="clr"></div>
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
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>