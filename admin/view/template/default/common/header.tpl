<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
	<?php require_once($this->checkTemplate(dirname(__FILE__) , 'common/header_head.tpl')); ?>
	<body>
		<div id="container">
			<div id="header">
				<div class="div1">
					<div class="div2">
						<?php if ($logged) { ?>
							<style>
								@media (max-width: 600px) { .hidden-xs{display:none;} .user-name-1{padding-left:0px!important;}  }
							</style>
							<img class="d_img hidden-xs" src="view/image/<? echo FILE_LOGO; ?>" style="float:left;margin-top:0px; height:38px!important;" title="<?php echo $heading_title; ?>" height="38px" onclick="location = '<?php echo $home; ?>'" />
                            <div style="float:left; color:#000; font-weight:700; padding-left:20px;">
                                <div><small <?php if ($needs_update) { ?>style="color:#ff7815;"<?php } ?>>Local version: <?php echo $current_version; ?></small></div>
                                <div><small>Global version: <?php echo $global_version; ?></small></div>
                            </div>
							<div class="user-name-1" style="float:left; color:#000; font-weight:700; padding-left:20px;">
								<i class="fa fa-user-o icon_header hidden-xs"></i>
								<div style="display: inline-block;"><? echo $this->user->getUserFullName(); ?> (<? echo $this->user->getUserName(); ?>) <a style="color:#788084" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link"></i></b></a>
								<span class="hidden-xs"><br /><? echo $this->user->getUserGroupName(); ?></span>
								</div>
							</div>

							<div id="menu_top">
								<ul class="left">
									<?php if ($this->config->get('config_enable_malert_in_admin')){	?>
										<li id="alertlog">
											<a class="top" href="<? echo $user_alerts; ?>" id="alert_history_preview_click"><span class="label label-danger" style="font-size:16px;"><i class='fa fa-bell' style="color:#FFF"></i></span></a>
										</li>
									<?php } ?>
									<li id="notification" class="header-notifications delayed-load short-delayed-load" data-route='common/home/loadNotifications'></li>
									<li id="callbacks">
										<a class="top" href="<? echo $callback; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-phone-square" ></i> &nbsp;<?php echo $total_callbacks; ?></span></a>		
									</li>									
									<li id="waitlists">
										<a class="top" href="<? echo $waitlist_ready; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-thumbs-up" ></i>&nbsp;<?php echo $total_waitlist_ready; ?></span></a>
									</li>
									<li id="waitlists-pre">
										<a class="top" href="<? echo $waitlist_pre; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-hourglass-half" ></i>&nbsp;<?php echo $total_waitlist_prewaits; ?></span></a>
									</li>									
									<li id="courier_face2">
										<a class="top" href="<? echo $courier_face2; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-truck" ></i></span></a>
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
						<div class="div3" id="cacheButtons" style="margin-right:100px; float:right;"></div>
						<script>
							function loadCacheButtons(){
								$('#cacheButtons').load('index.php?route=setting/setting/getFPCINFO&token=<?php echo $token;?>');
							}
							
							$(document).ready(function() {
								loadCacheButtons();
								setInterval(function() { loadCacheButtons(); }, 10000);   				
							});
						</script>
					<? } ?>
					<div style="clear: both;"></div>
				</div>
				<?php if ($logged) { ?>  	
					<div id="menu">										
						<ul class="left">
							<? if ($user_sip_history) { ?>
								<li id="user_sip_history"><a href="<?php echo $user_sip_history; ?>" class="top"><i class="fa fa-phone-square icon_menu" aria-hidden="true"></i>Звонки</a></li>
							<? } ?>
							<li id="tasks"><a href="<?php echo $user_ticket; ?>" class="top"><i class="fa fa-calendar icon_menu" aria-hidden="true"></i>Задачи</a></li>
							<li id="add_task"><a id="trigger_add_task" class="top"><i class="fa fa-calendar-plus-o icon_menu" aria-hidden="true"></i>Задача</a></li> 
						</ul>
						
						
						<? if (isset($ONLYCURRENCY)) { ?>
							<div style="float:left; margin-left:40px; color:#FFF; font-size:26px; line-height:60px;">
								1€ = <? echo $ONLYCURRENCY; ?>
							</div>
							<? } else { ?>
							<div style="float:left; margin-left:40px; color:#FFF; font-size:14px; line-height:30px;">
								1€ = <? echo $RUBEUR; ?><br />
								1€ = <? echo $UAHEUR; ?><br />
							</div>
						<? } ?>
						
						
						<ul class="right">
							<li id="dashboard"><a href="<?php echo $home; ?>" class="top"><i class="fa fa-home icon_menu"></i><?php echo $text_dashboard; ?></a></li>							
							<? if ($this->user->getIsAV()) { ?>
								<li id="worktime"><a class="top"><i class="fa fa-clock-o icon_menu"></i>Работа</a>
									<ul>
										<li id="user_content"><a class="home_icon_style" href="<?php echo $user_content; ?>"><i class="fa fa-clock-o"  aria-hidden="true"></i> <span>Работа контентов</span></a></li>
										<li id="addasin_report"><a class="home_icon_style" href="<?php echo $addasin_report; ?>"><i class="fa fa-amazon"  aria-hidden="true"></i> <span>Отчет по добавлениям Amazon</span></a></li>
										<li id="user_worktime"><a class="home_icon_style" href="<?php echo $user_worktime; ?>"><i class="fa fa-clock-o"  aria-hidden="true"></i> <span>Работа менеджеров</span></a></li>
										<li id="manager_quality"><a class="home_icon_style" href="<?php echo $manager_quality; ?>"><i class="fa fa-bar-chart"  aria-hidden="true"></i> <span>KPI менеджеров</span></a></li>
										<li id="salary_manager"><a class="home_icon_style" href="<?php echo $salary_manager; ?>"><i class="fa fa-handshake-o"  aria-hidden="true"></i> <span>Отчет по закрытым заказам</span></a></li>
										<li id="salary_customerservice"><a class="home_icon_style" href="<?php echo $salary_customerservice; ?>"><i class="fa fa-users"  aria-hidden="true"></i> <span>Отчет по клиент-сервису</span></a></li>
									</ul>
								</li>
							<? } ?>
						</li>
						<li id="catalog"><a class="top"><i class="fa fa-bars icon_menu"></i><?php echo $text_catalog; ?></a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $product; ?>"><i class="fa fa-cubes"></i> <span><?php echo $text_product; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $category; ?>"><i class="fa fa-minus"></i> <span><?php echo $text_category; ?></span></a></li>

								<li><a class="home_icon_style parent"><i class="fa fa-amazon"></i> <span>Фреймворк Amazon</span></a>
									<ul>
										<li><a href="<?php echo $addasin_amazonv1; ?>"><i class="fa fa-amazon"></i> <span>Просмотр Amazon v1</span></a></li>
										<li><a href="<?php echo $addasin_amazonv2; ?>"><i class="fa fa-amazon"></i> <span>Просмотр Amazon v2</span></a></li>
										<li><a href="<?php echo $addasin; ?>"><i class="fa fa-amazon"></i> <span>Очередь добавления ASIN</span></a></li>
										<li><a href="<?php echo $product_deletedasin; ?>"><i class="fa fa-amazon"></i> <span>Исключенные ASIN</span></a></li>
										<li><a href="<?php echo $product_excludedasin; ?>"><i class="fa fa-amazon"></i> <span>Исключенные слова</span></a></li>
										<li><a href="<?php echo $addasin_report; ?>"><i class="fa fa-file-text-o"></i> <span>Отчет по добавлениям</span></a></li>
									</ul>
								</li>

								<li><a class="home_icon_style parent"><i class="fa fa-file-text-o"></i> <span>Атрибуты и фильтрация</span></a>
									<ul>
										<li><a href="<?php echo $attribute; ?>"><i class="fa fa-filter"></i> <?php echo $text_attribute; ?></a></li>
										<li><a href="<?php echo $attribute_group; ?>"><i class="fa fa-filter"></i> <?php echo $text_attribute_group; ?></a></li>
										<li class="divider"></li>
										<li><a href="<?php echo $megafilter_module; ?>" target="_blank" style="color:green"><i class="fa fa-server"></i> <span>Конструктор фильтра</span></a></li>
										<li class="divider"></li>
										<li><a href="<?php echo $filter; ?>"><i class="fa fa-filter"></i> <span><?php echo $text_filter; ?> <sup style="color:red">DEV</sup></span></a></li>
										<li><a href="<?php echo $ocfilter_module; ?>" target="_blank"><i class="fa fa-server"></i> <span>OcFilter <sup style="color:red">DEV</sup></span></a></li>
										<li><a href="<?php echo $ocfilter; ?>"><i class="fa fa-cubes"></i> <span>Ocfilter <sup style="color:red">DEV</sup></span></a></li>
										<li><a href="<?php echo $ocfilter_page; ?>"><i class="fa fa-cubes"></i> <span>Ocfilter Page <sup style="color:red">DEV</sup></span></a></li>
									</ul>
								</li>
								<li><a class="home_icon_style" href="<?php echo $option; ?>"><i class="fa fa-sliders"></i> <span><?php echo $text_option; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $collections_link; ?>"><i class="fa fa-linode"></i> <span>Коллекции</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $manufacturer; ?>"><i class="fa fa-barcode"></i> <span>Бренды</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $countrybrands_link; ?>"><i class="fa fa-flag"></i> <span>Бренды по странам</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $keyworder_link; ?>"><i class="fa fa-exchange"></i> <span>Связки бренд-категория</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $sets_link; ?>"><i class="fa fa-window-restore"></i> <span>Комплекты товаров</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $facategory; ?>"><i class="fa fa-diamond"></i> <span><?php echo $text_facategory; ?></span></a></li>
							</ul>
						</li>
						<li id="editors"><a class="top"><i class="fa fa-edit icon_menu"></i>Редактор</a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $shortnames; ?>"><i class="fa fa-edit"></i> <span>Экспортные названия</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $shortnames2; ?>"><i class="fa fa-edit"></i> <span>Названия из заказов <sup style="color:red">(NEW)</sup></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $batch_editor_link; ?>"><i class="fa fa-pencil-square-o"></i> <span>Batch Editor v.023</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $csvpricelink; ?>"><i class="fa fa-cubes"></i> <span>CSV IMPORT/EXPORT</span></a></li>		

							</ul>
						</li>
						<li id="information"><a class="top"><i class="fa fa-info icon_menu"></i>Инфо</a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $review; ?>"><i class="fa fa-comments-o"></i> <span><?php echo $text_review; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $shop_rating; ?>"><i class="fa fa-bar-chart"></i> <span><?php echo $text_shop_rating; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $review_category; ?>"><i class="fa fa-comments-o"></i> <span><?php echo $text_review_category; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $information; ?>"><i class="fa fa-newspaper-o"></i> <span><?php echo $text_information; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $landingpage; ?>"><i class="fa fa-star"></i> <span>Посадочные страницы</span></a></li>
								<li><a class="home_icon_style parent"><i class="fa fa-info-circle"></i> <span>Новости + Блог</span></a>
									<ul>
										<li><a style="width: 195px;" href="<?php echo $npages; ?>"><?php echo $entry_npages; ?></a></li>
										<li><a style="width: 195px;" href="<?php echo $ncategory; ?>"><?php echo $entry_ncategory; ?></a></li>
										<li><a style="width: 195px;" href="<?php echo $tocomments; ?>"><?php echo $text_commod; ?></a></li>
										<li><a style="width: 195px;" href="<?php echo $nauthor; ?>"><?php echo $text_nauthor; ?></a></li>
										<li><a style="width: 195px;" href="<?php echo $nmod; ?>"><?php echo $entry_nmod; ?></a></li>
										<li><a style="width: 195px;" href="<?php echo $ncmod; ?>"><?php echo $entry_ncmod; ?></a></li>
									</ul>
								</li>
								<li><a class="home_icon_style" href="<?php echo $information_attribute; ?>"><i class="fa fa-newspaper-o"></i> <span>Статьи для атрибутов</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $faq_url; ?>"><i class="fa fa-question-circle-o"></i> <span>FAQ вопрос-ответ</span></a></li>
								<li><a class="home_icon_style" href="<? echo $sms_link; ?>"><i class="fa fa-envelope-o"></i> <span>SMS</span></a></li>
							</ul>
						</li>				 
						
						<li id="sale"><a class="top"><i class="fa fa-handshake-o icon_menu"></i><?php echo $text_sale; ?></a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $order; ?>"><i class="fa fa-cart-arrow-down"></i> <span><?php echo $text_order; ?></span></a></li>						
								<? if ($fucked_order_total > 0) { ?>
									<li><a class="home_icon_style" href="<? echo $fucked_link; ?>"><i class="fa fa-cart-plus"></i> <span>Незавершенные заказы <span style="color:#cf4a61;">(<? echo $fucked_order_total; ?>)</span></span></a></li>
								<? } ?>
								<li><a class="home_icon_style" href="<? echo $callback; ?>"><i class="fa fa-phone"></i> <span>Обратные звонки <span style="color:#cf4a61;">(<? echo $total_callbacks; ?>)</span></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $return; ?>"><i class="fa fa-refresh"></i> <span><?php echo $text_return; ?></span></a></li>
								<li><a class="home_icon_style" href="<?php echo $user_sip; ?>"><i class="fa fa-list-ol"></i> <span>История телефонных звонков</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $coupon; ?>"><i class="fa fa-barcode"></i> <span>Промокоды</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $addspecials; ?>"><i class="fa fa-clone"></i> <span>Спецпредложения</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $actions; ?>"><i class="fa fa-percent"></i> <span>Акционные предложения</span></a></li>
								<li>
									<a class="home_icon_style parent"><i class="fa fa-cc"></i> <span><?php echo $text_voucher; ?></span></a>
									<ul>
										<li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
										<li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
									</ul>
								</li>															
							</ul>
						</li>
						<li id="customer"><a class="top"><i class="fa fa-users icon_menu"></i>Клиенты</a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $customer; ?>"><i class="fa fa-list-ol"></i> <?php echo $text_customer; ?></a></li>
								<li><a class="home_icon_style" href="<?php echo $customer_group; ?>"><i class="fa fa-newspaper-o"></i> <?php echo $text_customer_group; ?></a></li>
								<li><a class="home_icon_style" href="<?php echo $customer_manual; ?>"><i class="fa fa-phone"></i> Обзвон клиентов</a></li>
								<li><a class="home_icon_style" href="<?php echo $segments_link; ?>"><i class="fa fa-bar-chart"></i> Настройка сегментации</a></li>
								<li><a class="home_icon_style" href="<?php echo $actiontemplate; ?>"><i class="fa fa-envelope-o"></i> Шаблоны регулярных рассылок</a></li>
								<li><a class="home_icon_style" href="<?php echo $customer_ban_ip; ?>"><i class="fa fa-refresh"></i> <?php echo $text_customer_ban_ip; ?></a></li>
							</ul>
						</li>
						<li id="buyer"><a class="top"><i class="fa fa-eur icon_menu" aria-hidden="true"></i>Закупка</a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $waitlist; ?>"><i class="fa fa-clock-o"></i> <span>Лист ожидания</span></a></li> 
								<li><a class="home_icon_style" href="<?php echo $stocks; ?>"><i class="fa fa-cubes"></i> <span>Свободные остатки</span></a></li>	
								
								<?php if ($this->config->get('config_country_id') == 176) { ?>
									<li><a class="home_icon_style" href="<?php echo $yandex; ?>" style="color:#cf4a61"><i class="fa fa-yahoo"></i> <span>Yandex Market</span></a></li>
									<li><a class="home_icon_style" href="<?php echo $priceva; ?>" style="color:#7F00FF"><i class="fa fa-product-hunt"></i> <span>Мониторинг конкурентов</span></a></li>
								<?php } ?>

								<?php if ($this->config->get('config_product_quality_groups_enable')) { ?>
								<li><a href="<?php echo $product_groups; ?>"><i class="fa fa-product-hunt"></i> <span>Группы качества товаров</span></a></li>
								<?php } ?>

								<li><a class="home_icon_style" href="<?php echo $report_buyanalyze; ?>"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <span>Потребность в закупке</span></a></li>			
								<li><a class="home_icon_style" href="<?php echo $parties; ?>"><i class="fa fa-list-ol"></i> <span>Закупочные партии</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $suppliers; ?>"><i class="fa fa-list-alt"></i> <span>Управление поставщиками</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $optprices; ?>"><i class="fa fa-handshake-o"></i> <span>b2b ценообразование</span></a></li>
							</ul>
						</li>
						
						<li id="extension"><a class="top"><i class="fa fa-clone icon_menu"></i>Модули</a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $mod_latest; ?>"><i class="fa fa-check-square-o"></i> <span>Последние добавленные (авто)</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $mod_bestseller; ?>"><i class="fa fa-diamond"></i> <span>Хиты продаж (авто)</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $mod_special; ?>"><i class="fa fa-percent"></i> <span>Акционные товары (авто)</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $mod_customproduct; ?>"><i class="fa fa-hand-pointer-o"></i> <span>Мультирекомендуемые (ручное)</span></a></li>

								<li><a class="home_icon_style" href="<?php echo $mod_blokviewed; ?>"><i class="fa fa-eye"></i> <span>Блок просмотренных (полу-авто)</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $mod_featured; ?>"><i class="fa fa-thumbs-up"> </i> <span>Рекомендуемые табами (ручное)</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $mod_featuredreview; ?>"><i class="fa fa-comment"> </i> <span>Последние с отзывами (авто)</span></a></li>

								<li class="divider"></li>

								<li><a class="home_icon_style" href="<?php echo $notify_bar; ?>"><i class="fa fa-cog"></i> <span>Верхняя полоса</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $banner; ?>"><i class="fa fa-eye"></i> Баннера и слайды</a></li>
								<li><a class="home_icon_style" href="<?php echo $banner_module; ?>"><i class="fa fa-eye"></i> Конструктор баннеров</a></li>
								<li><a class="home_icon_style" href="<?php echo $slideshow_module; ?>"><i class="fa fa-eye"></i> Простое слайдшоу</a></li>
							</ul>
						</li>							
						<li id="seo"><a class="top"><i class="fa fa-puzzle-piece icon_menu"></i>SEO</a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $redirect_manager; ?>"><i class="fa fa-wrench"></i> <span>Управление редиректами</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $seo_feeds; ?>"><i class="fa fa-sitemap"></i> <span>Списки фидов</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $seogen; ?>"><i class="fa fa-sitemap"></i> <span>Генератор SEO</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $metaseo_anypage; ?>"><i class="fa fa-sitemap"></i> <span>Мета-теги</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $autolink_link; ?>"><i class="fa fa-font"></i> <span>Подмена слов на ссылки</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $keyworder_link; ?>"><i class="fa fa-retweet"></i> <span>Связка произв./кат.</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $microdata_link; ?>"><i class="fa fa-sitemap"></i> <span>Microdata v1</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $seo_snippet_link; ?>"><i class="fa fa-sitemap"></i> <span>Microdata v2</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $affiliate; ?>"><i class="fa fa-handshake-o"></i> <span>Трекинг</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $affiliate_mod_link; ?>"><i class="fa fa-cog"></i> <span>Настройки трекинга</span></a></li>
							</ul>
						</li>

						<li id="reports"><a class="top"><i class="fa fa-area-chart icon_menu"></i><?php echo $text_reports; ?></a>
							<ul>		
								<li><a class="home_icon_style" href="<?php echo $report_product_viewed; ?>"><i class="fa fa-eye"></i> <span>Отчет просмотров</span></a></li>
								<li><a href="<?php echo $report_reject; ?>"><i class="fa fa-bar-chart"></i> Причины отмен</a></li>
								<li><a href="<?php echo $report_marketplace; ?>"><i class="fa fa-bar-chart"></i> Маркетплейсы</a></li>
								<li><a class="home_icon_style" href="<?php echo $mreport_minusscan ?>"><i class="fa fa-exclamation"></i> <span>Проверка счетов</span></a></li>
								<li><a class="parent home_icon_style"><i class="fa fa-database"></i> <span><?php echo $text_sale; ?></span></a>
									<ul>																					 
										<li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
										<li><a href="<?php echo $report_product_purchased; ?>">Товары</a></li>
										<li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
										<li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
										<li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
										<li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
									</ul>
								</li>
								<li><a class="parent home_icon_style"><i class="fa fa-users"></i> <span><?php echo $text_customer; ?></span></a>
									<ul>
										<li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
										<li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
										<li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
										<li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
									</ul>
								</li>
								<li><a class="parent home_icon_style"><i class="fa fa-handshake-o"></i> <span><?php echo $text_affiliate; ?></span></a>
									<ul>
										<li><a href="<?php echo $report_affiliate_commission; ?>"><?php echo $text_report_affiliate_commission; ?></a></li>
										<li><a href="<?php echo $report_affiliate_statistics; ?>"><?php echo $text_report_affiliate_statistics; ?></a></li>
										<li><a href="<?php echo $report_affiliate_statistics_all; ?>"><?php echo $text_report_affiliate_statistics_all; ?></a></li>
									</ul>
								</li>
							</ul>
						</li>
						
						<li id="system"><a class="top"><i class="fa fa-cogs icon_menu"></i><?php echo $text_system; ?></a>
							<ul>
								<li><a class="home_icon_style" href="<?php echo $setting; ?>"><i class="fa fa-cog"></i> <span>Настройки движка</span></a></li>
								<li><a class="home_icon_style"  href="<?php echo $rnf; ?>"><i class="fa fa-amazon"></i> <span>Настройки Rainforest API</span></a></li>
								<li><a class="home_icon_style"  href="<?php echo $simple_module; ?>" target="_blank"><i class="fa fa-server"></i> <span>Настройки чекаута</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $etemplate; ?>"><i class="fa fa-cog"></i> <span>Настройки шаблонов EMail</span></a></li>

								<li><a class="parent home_icon_style"><i class="fa fa-cog"></i> <span>Модули</span></a>
									<ul>
										<li><a href="<?php echo $module; ?>"><i class="fa fa-cogs"></i> <span><?php echo $text_module; ?></span></a></li>
										<li><a href="<?php echo $shipping; ?>"><i class="fa fa-truck"></i> <span><?php echo $text_shipping; ?></span></a></li>
										<li><a href="<?php echo $payment; ?>"><i class="fa fa-credit-card"></i> <span><?php echo $text_payment; ?></span></a></li>
										<li><a href="<?php echo $total; ?>"><i class="fa fa-plus"></i> <span><?php echo $text_total; ?></span></a></li>
									</ul>
								</li>

								<li class="divider"></li>

								<li>
									<a class="parent home_icon_style"><i class="fa fa-globe"></i> <span><?php echo $text_localisation; ?></span></a>
									<ul>
										<li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
										<li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
										<li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
										<li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
										<li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
										<li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
										<li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
										<li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
										<li><a href="<?php echo $legalperson; ?>">Кассы и счета</a></li>
										<li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
										<li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
										<li><a class="parent"><?php echo $text_tax; ?></a>
											<ul>
												<li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
												<li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
											</ul>
										</li>
										<li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
										<li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
										<li><a href="<?php echo $order_bottom_forms; ?>">Шаблоны подтверждения</a></li>
									</ul>									
								</li>
								<?php if ($this->config->get('config_country_id') == 220 && !defined('RESTRICTED_FUNCTIONS')) { ?>
								<li>
									<a class="home_icon_style parent"><i class="fa fa-check"></i> <span>CheckBOX ПРРО</span></a>
									<ul>
										<li><a href="<?php echo $sale_checkbox; ?>"><i class="fa fa-check"></i> Продажи/Чеки</a></li>
										<li><a href="<?php echo $module_checkbox; ?>"><i class="fa fa-check"></i> Настройки</a></li>
										<li><a href="<?php echo $log_checkbox; ?>"><i class="fa fa-check"></i> Журнал</a></li>
									</ul>
								</li>
								<?php } ?>
								<?php if ($pp_express_status) { ?>
								<li><a class="parent home_icon_style" href="<?php echo $paypal_express; ?>"><i class="fa fa-cc-paypal"></i> <span><?php echo $text_paypal_express; ?></span></a>
									<ul>
										<li><a href="<?php echo $paypal_express_search; ?>"><?php echo $text_paypal_express_search; ?></a></li>
									</ul>
								</li>
								<?php } ?>
								<li>
									<a class="parent home_icon_style"><i class="fa fa-users"></i> <span> Мониторинг</span></a>
									<ul>
										<li><a href="<?php echo $panel; ?>"><i class="fa fa-server"></i> <span>Мониторинг API</span></a></li>
										<li><a href="<?php echo $cronmon; ?>"><i class="fa fa-server"></i> <span>Мониторинг Сron</span></a></li>
										<li><a href="<? echo $adminlog_url; ?>"><i class="fa fa-user"></i> <span>Журнал доступа</span></a></li>
										<li><a href="<?php echo $error_log; ?>"><i class="fa fa-bars"></i> <span>Журналы системы</span></a></li>
									</ul>
								</li>
								<li>
									<a class="parent home_icon_style"><i class="fa fa-users"></i> <span><?php echo $text_users; ?></span></a>
									<ul>
										<li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
										<li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
									</ul>
								</li>
								<li class="divider"></li>
								<li><a class="home_icon_style"  href="<?php echo $courier_face2; ?>" target="_blank"><i class="fa fa-bus" ></i> <span>Интерфейс курьера</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $layout; ?>"><i class="fa fa-file-image-o"></i> <span>Схемы / макеты</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $custom_template_link; ?>"><i class="fa fa-refresh"></i> Переназначение шаблонов</a></li>
								<li><a class="home_icon_style"  href="<?php echo $simple_module_abandoned; ?>"><i class="fa fa-server"></i> <span>Брошенные корзины</span></a></li>
								<li><a class="home_icon_style" href="<?php echo $translator; ?>"><i class="fa fa-language"></i> <span>Перевод языковых файлов</span></a></li>															
							</ul>
						</li>							   

						<?php if ($this->config->get('config_enable_amazon_specific_modes')) { ?>
							<li id="rnf">
								<a class="top" href="<?php echo $rnf; ?>"><i class="fa fa-amazon icon_menu"></i>Rainforest</a>
							</li>
						<?php } ?>
						<li id="store"><a href="<?php echo $store; ?>" target="_blank" class="top"><i class="fa fa-share icon_menu"></i><?php echo $text_front; ?></a>
							<ul>
								<?php foreach ($stores as $stores) { ?>
									<li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
						<li></li>
						
					</ul>
					<style>
						#menu > ul li ul{
						display:none;
						}
						
						#menu > ul > li.hover > ul{
						display:block;
						}
						
						#menu > ul > li.sfhover > ul{
						display:block;
						}
						
						#menu > ul > li > ul > li > ul{
						display:none;
						}
						
						#menu > ul > li > ul > li:hover > ul{
						display:block;
						}
					</style>
					<div style="clear: both;"></div>
				</div>
			<?php } ?>
		</div>																																																											