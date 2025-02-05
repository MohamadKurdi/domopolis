<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<?php require_once($this->checkTemplate(dirname(__FILE__) , 'common/header_head.tpl')); ?>
		<body>
			<div id="container">
				<div id="header">
					<div class="div1">
						<div class="div2">
							<?php if ($logged) { ?>
							<img class="d_img" src="view/image/<? echo FILE_LOGO; ?>" style="float:left;margin-top:0px;" title="<?php echo $heading_title; ?>" height="38px" onclick="location = '<?php echo $home; ?>'" style="height:38px!important;" />	
							<div style="float:left; color:#000; font-weight:700; padding-left:20px;">
								<i class="fa fa-user-o icon_header"></i>
								<div style="display: inline-block;"><? echo $this->user->getUserFullName(); ?> (<? echo $this->user->getUserName(); ?>) <a style="color:#788084" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link"></i></b></a>
									<br /><? echo $this->user->getUserGroupName(); ?></div>
								</div>
								<? } ?>
								<?php if ($logged) { ?>
								<div id="menu_top">
									<ul class="left">
										<?php if ($this->config->get('config_enable_malert_in_admin')){	?>
										<li id="alertlog"><a class="top" href="<? echo $user_alerts; ?>" id="alert_history_preview_click"><span class="label label-danger" style="font-size:16px;"><i class='fa fa-bell' style="color:#FFF"></i></span></a></li>
									<?php } ?>
										<li id="notification" class="header-notifications delayed-load short-delayed-load" data-route='common/home/loadNotifications'></li>

										<li id="shortnames">
											<a class="top" href="<? echo $addasin; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-refresh" ></i>&nbsp;QUEUE&nbsp;<?php echo $total_product_in_asin_queue; ?></span></a>		
										</li>
									</ul>
									<style>
										#menu_top > ul li ul{
											display:none;
										}

										#menu_top > ul > li.hover > ul{
											display:block;
										}

										#menu_top > ul > li.sfhover > ul{
											display:block;
										}

										#menu_top > ul > li > ul > li > ul{
											display:none;
										}

										#menu_top > ul > li > ul > li:hover > ul{
											display:block;
										}
									</style>

									<div style="clear: both;"></div>
								</div>
								<? } ?>	
							</div>
							<?php if ($logged) { ?>
							<div class="div3" id="cacheButtons" style="margin-right:100px;">

							</div>
							<script>
								function loadCacheButtons(){
									$('#cacheButtons').load('index.php?route=setting/setting/getFPCINFO&token=<?php echo $token;?>');
								}

								$(document).ready(function() {
									loadCacheButtons();
									setInterval(function() { loadCacheButtons(); }, 5000);   				
								});
							</script>
							<? } ?>
							<div style="clear: both;"></div>
						</div>
						<?php if ($logged) { ?>

						<div id="menu">	
							<ul class="left">
								<li id="tasks"><a href="<?php echo $user_ticket; ?>" class="top"><i class="fa fa-calendar icon_menu" aria-hidden="true"></i>Задачи</a></li>
								<li id="add_task"><a id="trigger_add_task" class="top"><i class="fa fa-calendar-plus-o icon_menu" aria-hidden="true"></i>Задача</a></li> 
							</ul>

							<ul class="right">
								<li id="dashboard"><a href="<?php echo $home; ?>" class="top"><i class="fa fa-home icon_menu"></i><?php echo $text_dashboard; ?></a></li>
								<li id="user_content"><a class="top" href="<?php echo $user_content; ?>"><i class="fa fa-clock-o icon_menu"  aria-hidden="true"></i><span>Работа</span></a></li>
								<li id="catalog"><a class="top"><i class="fa fa-bars icon_menu"></i><?php echo $text_catalog; ?></a>
									<ul>
										<li><a class="home_icon_style" href="<?php echo $category; ?>"><i class="fa fa-minus"></i><span><?php echo $text_category; ?></span></a></li>								
										<li><a class="home_icon_style" href="<?php echo $product; ?>"><i class="fa fa-cubes"></i><span><?php echo $text_product; ?></span></a></li>								
										<li><a class="home_icon_style parent"><i class="fa fa-amazon"></i><span>Фреймворк Amazon</span></a>
										<ul>
											<li><a href="<?php echo $addasin_amazonv1; ?>"><i class="fa fa-amazon"></i> <span>Просмотр Amazon v1</span></a></li>
											<li><a href="<?php echo $addasin_amazonv2; ?>"><i class="fa fa-amazon"></i> <span>Просмотр Amazon v2</span></a></li>
											<li><a href="<?php echo $addasin; ?>"><i class="fa fa-amazon"></i> <span>Очередь добавления ASIN</span></a></li>
											<li><a href="<?php echo $product_deletedasin; ?>"><i class="fa fa-amazon"></i> <span>Исключенные ASIN</span></a></li>
											<li><a href="<?php echo $product_excludedasin; ?>"><i class="fa fa-amazon"></i> <span>Исключенные слова</span></a></li>
											<li><a href="<?php echo $addasin_report; ?>"><i class="fa fa-file-text-o"></i> <span>Отчет по добавлениям</span></a></li>
										</ul>
										</li>     
										<li><a class="home_icon_style parent"><i class="fa fa-file-text-o"></i><span><?php echo $text_attribute; ?></span></a>
											<ul>
												<li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
												<li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
											</ul>
										</li>
										<li><a class="home_icon_style" href="<?php echo $option; ?>"><i class="fa fa-sliders"></i><span><?php echo $text_option; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $manufacturer; ?>"><i class="fa fa-barcode"></i><span>Бренды</span></a></li>								
										<li><a class="home_icon_style" href="<?php echo $countrybrands_link; ?>"><i class="fa fa-flag"></i><span>Бренды по странам</span></a></li>								
										<li><a class="home_icon_style" href="<?php echo $collections_link; ?>"><i class="fa fa-linode"></i><span>Коллекции</span></a></li>								
										<li><a class="home_icon_style" href="<?php echo $keyworder_link; ?>"><i class="fa fa-exchange"></i><span>Связка производитель/категория</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $sets_link; ?>"><i class="fa fa-window-restore"></i><span>Комплекты товаров</span></a></li>								
										<li><a class="home_icon_style" href="<?php echo $facategory; ?>"><i class="fa fa-diamond"></i><span><?php echo $text_facategory; ?></span></a></li>	
										<li><a class="home_icon_style" href="<?php echo $ocfilter; ?>"><i class="fa fa-cubes"></i><span><?php echo $text_ocfilter; ?> <sup style="color:red">DEV</sup></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $ocfilter_page; ?>"><i class="fa fa-cubes"></i><span>Посадочные страницы <sup style="color:red">DEV</sup></span></a></li>							
										<li><a class="home_icon_style" href="<?php echo $filter; ?>"><i class="fa fa-filter"></i><span><?php echo $text_filter; ?> <sup style="color:red">DEV</sup></span></a></li>   							
									</ul>
								</li>
								<li id="editors"><a class="top"><i class="fa fa-edit icon_menu"></i>Редактор</a>
									<ul>
										<li><a class="home_icon_style" href="<?php echo $shortnames; ?>"><i class="fa fa-edit"></i><span>Экспортные названия</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $shortnames2; ?>"><i class="fa fa-edit"></i><span>Названия из заказов <sup style="color:red">(NEW)</sup></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $batch_editor_link; ?>"><i class="fa fa-pencil-square-o"></i><span>Batch Editor v.023</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $csvpricelink; ?>"><i class="fa fa-cubes"></i><span>CSV IMPORT/EXPORT</span></a></li>		

									</ul>
								</li>
								<li id="information"><a class="top"><i class="fa fa-info icon_menu"></i>Инфо</a>
									<ul>
										<li><a class="home_icon_style" href="<?php echo $review; ?>"><i class="fa fa-comments-o"></i><span><?php echo $text_review; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $shop_rating; ?>"><i class="fa fa-bar-chart"></i><span><?php echo $text_shop_rating; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $review_category; ?>"><i class="fa fa-comments-o"></i><span><?php echo $text_review_category; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $information; ?>"><i class="fa fa-newspaper-o"></i><span><?php echo $text_information; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $landingpage; ?>"><i class="fa fa-star"></i><span>Посадочные страницы</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $information_attribute; ?>"><i class="fa fa-newspaper-o"></i><span>Статьи для атрибутов</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $faq_url; ?>"><i class="fa fa-question-circle-o"></i><span>FAQ вопрос-ответ</span></a></li>
									</ul>
								</li>

								<li id="design"><a class="top"><i class="fa fa-file-image-o icon_menu"></i>Баннера</a>
									<ul>
										<li><a class="home_icon_style" href="<?php echo $banner; ?>">Баннера и слайды</a></li>					
										<li><a class="home_icon_style" href="<?php echo $banner_module; ?>">Конструктор баннеров (модуль)</a></li>					
										<li><a class="home_icon_style" href="<?php echo $slideshow_module; ?>">Простое слайдшоу (модуль)</a></li>
									</ul>
								</li>


								<li id="extension"><a class="top"><i class="fa fa-clone icon_menu"></i>Расширения</a>
									<ul>
										<li><a class="home_icon_style" href="<?php echo $module; ?>"><i class="fa fa-cogs"></i><span><?php echo $text_module; ?></span></a></li>		
										<li><a class="home_icon_style" href="<?php echo $shipping; ?>"><i class="fa fa-truck"></i><span><?php echo $text_shipping; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $payment; ?>"><i class="fa fa-credit-card"></i><span><?php echo $text_payment; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $total; ?>"><i class="fa fa-plus"></i><span><?php echo $text_total; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $feed; ?>"><i class="fa fa-line-chart"></i><span><?php echo $text_feed; ?></span></a></li>
										<li><a class="home_icon_style" href="<?php echo $notify_bar; ?>"><i class="fa fa-cog"></i><span>Верхняя полоса</span></a></li>
									</ul>
								</li>
								<li id="modules"><a class="top"><i class="fa fa-newspaper-o icon_menu"></i>Модули</a>
									<ul>
										<li><a class="home_icon_style" href="<?php echo $mod_latest; ?>"><i class="fa fa-check-square-o"></i><span>Последние добавленные (авто)</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $mod_bestseller; ?>"><i class="fa fa-diamond"></i><span>Хиты продаж (авто)</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $mod_featuredreview; ?>"><i class="fa fa-comment"></i><span>Последние с отзывами (авто)</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $mod_special; ?>"><i class="fa fa-percent"></i><span>Акционные товары (авто)</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $mod_featured; ?>"><i class="fa fa-thumbs-up"></i><span>Рекомендуемые (ручное)</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $mod_customproduct; ?>"><i class="fa fa-hand-pointer-o"></i><span>Мультирекомендуемые (ручное)</span></a></li>
									</ul>
								</li>
								<li id="seo"><a class="top"><i class="fa fa-puzzle-piece icon_menu"></i>SEO</a>
									<ul>
										<li><a class="home_icon_style" href="<?php echo $redirect_manager; ?>"><i class="fa fa-wrench"></i><span>Управление редиректами</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $seogen; ?>"><i class="fa fa-sitemap"></i><span>Генератор СЕО-фишечек</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $autolink_link; ?>"><i class="fa fa-font"></i><span>Подмена слов на ссылки</span></a></li>
										<li><a class="home_icon_style" href="<?php echo $keyworder_link; ?>"><i class="fa fa-retweet"></i><span>Связка произв./кат.</span></a></li>
										<li><a class="home_icon_style" href="<?php echo HTTP_CATALOG.'reviewgen/'; ?>"><i class="fa fa-sitemap"></i><span>Генератор отзывов</span></a></li>
									</ul>
								</li>							



								<li id="blog"><a class="top"><i class="fa fa-exclamation-circle icon_menu"></i>Новости</a>
									<ul>
										<li><a class="home_icon_style" style="width: 195px;" href="<?php echo $npages; ?>"><i class="fa fa-pencil-square-o"></i><span><?php echo $entry_npages; ?></span></a></li>
										<li><a class="home_icon_style" style="width: 195px;" href="<?php echo $ncategory; ?>"><i class="fa fa-pencil-square-o"></i><span><?php echo $entry_ncategory; ?></span></a></li>
										<li><a class="home_icon_style" style="width: 195px;" href="<?php echo $tocomments; ?>"><i class="fa fa-exchange"></i><span><?php echo $text_commod; ?></span></a></li>
										<li><a class="home_icon_style" style="width: 195px;" href="<?php echo $nauthor; ?>"><i class="fa fa-pencil-square-o"></i><span><?php echo $text_nauthor; ?></span></a></li>
										<li><a class="home_icon_style" style="width: 195px;" href="<?php echo $nmod; ?>"><i class="fa fa-newspaper-o"></i><span><?php echo $entry_nmod; ?></span></a></li>
										<li><a class="home_icon_style" style="width: 195px;" href="<?php echo $ncmod; ?>"><i class="fa fa-newspaper-o"></i><span><?php echo $entry_ncmod; ?></span></a></li>
									</ul>
								</li>

								<li id="store"><a class="top"><i class="fa fa-share icon_menu"></i>Магазин</a>
									<ul>
										<a href="<?php echo $store; ?>" target="_blank" class="top">Основной</a>
										<?php foreach ($stores as $stores) { ?>
										<li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
										<?php } ?>
									</ul>
								</li>
								<li><a class="top" href="<?php echo $logout; ?>"><i class="fa fa-power-off icon_menu"></i><?php echo $text_logout; ?></a></li>

							</ul>
							<div style="clear: both;"></div>
						</div>
						<?php } ?>
					</div>																																			