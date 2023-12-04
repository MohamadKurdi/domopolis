<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
	<?php require_once(dirname(__FILE__).'/../header_head.tpl'); ?>
	<body>
		<div id="container">
			<div id="header">
				<div class="div1">
					<div class="div2">
						<?php if ($logged) { ?>
							<div style="float:left; color:#000; font-weight:700; padding-left:20px;"><i class="fa fa-user-o icon_header"></i><div style="display: inline-block;"><? echo $this->user->getUserFullName(); ?> (<? echo $this->user->getUserName(); ?>)<br /><? echo $this->user->getUserGroupName(); ?></div></div>
						<? } ?>
						<?php if ($logged) { ?> 
							<div id="menu_top">
								<ul class="left">
									<li id="alertlog"><a class="top" href="<? echo $user_alerts; ?>" id="alert_history_preview_click"><i class='fa fa-bell'></i></a></li>
									<!-- Admin Panel Notication -->
									<li id="notification"><a class="top"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-warning" ></i>&nbsp;&nbsp;<?php echo $total_notification; ?></span></a>
										<ul style="width: 196px;">
											<li><a href="<?php echo $order; ?>&filter_date_added=<?php echo date('Y-m-d'); ?>"><?php echo $text_new_order; ?><span class="label pull-right<?php echo ($total_new_order == '0' ? ' label-warning' : ' label-danger'); ?>"><?php echo $total_new_order; ?></span></a></li>
											<li><a href="<?php echo $order; ?>&filter_order_status_id=1"><?php echo $text_pending_order; ?><span class="label pull-right<?php echo ($total_pending_order == '0' ? ' label-warning' : ' label-danger'); ?>"><?php echo $total_pending_order; ?></span></a></li>
											<li><a href="<?php echo $customer; ?>&filter_date_added=<?php echo date('Y-m-d'); ?>"><?php echo $text_new_customer; ?><span class="label pull-right<?php echo ($total_new_customer == '0' ? ' label-success' : ' label-danger'); ?>"><?php echo $total_new_customer; ?></span></a></li>
											<li><a href="<?php echo $customer; ?>&filter_approved=0"><?php echo $text_pending_customer; ?><span class="label pull-right<?php echo ($total_customer_approval == '0' ? ' label-success' : ' label-danger'); ?>"><?php echo $total_customer_approval; ?></span></a></li>
											<li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?><span class="label pull-right<?php echo ($total_customer_online == '0' ? ' label-success' : ' label-danger'); ?>"><?php echo $total_customer_online; ?></span></a></li>
											<li><a href="<?php echo $product; ?>&filter_quantity=0"><?php echo $text_stockout; ?><span class="label pull-right<?php echo ($total_stockout == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_stockout; ?></span></a></li>
											<li><a href="<?php echo $return; ?>&filter_date_added=<?php echo date('Y-m-d'); ?>"><?php echo $text_return; ?><span class="label pull-right<?php echo ($total_new_return == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_new_return; ?></span></a></li>
											<li><a href="<?php echo $review; ?>&sort=r.status&order=ASC"><?php echo $text_pending_review; ?><span class="label pull-right<?php echo ($total_review_approval == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_review_approval; ?></span></a></li>
											<li><a href="<?php echo $affiliate; ?>&filter_approved=0"><?php echo $text_pending_affiliate; ?><span class="label pull-right<?php echo ($total_affiliate_approval == '0' ? ' label-info' : ' label-danger'); ?>"><?php echo $total_affiliate_approval; ?></span></a></li>
										</ul>
									</li>
									<!-- Admin Header Notices 1.0 -->
									<li id="callbacks">
										<a class="top" href="<? echo $callback; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-phone-square" ></i> &nbsp;&nbsp;<?php echo $total_callbacks; ?></span></a>		
									</li>
									
									<li id="waitlists">
										<a class="top" href="<? echo $waitlist_ready; ?>"><span class="label label-danger" style="font-size:16px;"><i class="fa fa-hourglass-2" ></i> &nbsp;&nbsp;<?php echo $total_waitlist_ready; ?></span></a>		
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
						<div class="div3" style="color: #788084;"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
					<?php } ?>
					<?php if ($logged) { ?>
						<div class="div3" style="margin-right:100px;"><a class="link_headr " onclick="$('#clearCacheR').load('<? echo $clear_memcache ?>');" >Очистить временный кэш <span id="clearCacheR"></span></a>&nbsp;&nbsp;<a class="link_headr <? if ($noCacheMode) { ?>link_enter<? } ?>" onclick="$('#noCacheR').load('<? echo $noCacheModeLink ?>');">Режим без блок-кеша:<span id='noCacheR'><? echo ($noCacheMode?'вкл':'выкл'); ?></span></a>		
						</div>
					<? } ?>
					<div style="clear: both;"></div>
				</div>
				<?php if ($logged) { ?>  	
					<div id="menu">
						<img class="d_img" src="view/image/<? echo FILE_LOGO; ?>" style="float:left;margin-top:0px;" title="<?php echo $heading_title; ?>" onclick="location = '<?php echo $home; ?>'" />
						<ul class="right">
							<li id="dashboard"><a href="<?php echo $home; ?>" class="top"><i class="fa fa-home icon_menu"></i><?php echo $text_dashboard; ?></a></li>
							<li id="catalog"><a class="top"><i class="fa fa-bars icon_menu"></i><?php echo $text_catalog; ?></a>
								<ul>
									<li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
									<li><a href="<?php echo $categoryocshop; ?>">Категории простые деревом</a></li>
									<li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
									<li><a href="<?php echo $product_parser; ?>">Результаты парсера</a></li>
									<li><a href="<?php echo $batch_editor_link; ?>">Групповое ред. товаров</a></li>
									
									<li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>        
									<li><a class="parent"><?php echo $text_attribute; ?></a>
										<ul>
											<li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
											<li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
										</ul>
									</li>
									<li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
									<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
									<!--<li><a href="<?php echo $product_statuses; ?>"><?php echo $text_product_statuses; ?></a></li>-->
									<li><a href="<?php echo $keyworder_link; ?>">Связка производитель/категория</a></li>
									<li><a href="<?php echo $sets_link; ?>">Комплекты товаров</a></li>
									<li><a href="<?php echo $addspecials; ?>"><?php echo $text_addspecials; ?></a></li>
									<li><a href="<?php echo $collections_link; ?>">Коллекции</a></li>
									<!--<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>-->
									<!-- FAproduct -->
									<li><a href="<?php echo $facategory; ?>"><?php echo $text_facategory; ?></a></li>
									<!-- FAproduct -->
								</ul>
							</li>
							<li id="information"><a class="top"><i class="fa fa-info icon_menu"></i>Инфо</a>
								<ul>
									<li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
									<li><a href="<?php echo $shop_rating; ?>"><?php echo $text_shop_rating; ?></a></li>
									<li><a href="<?php echo $review_category; ?>"><?php echo $text_review_category; ?></a></li>
									<li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
									<li><a class="parent">Новости + Блог</a>
										<ul>
											<li><a style="width: 195px;" href="<?php echo $npages; ?>"><?php echo $entry_npages; ?></a></li>
											<li><a style="width: 195px;" href="<?php echo $ncategory; ?>"><?php echo $entry_ncategory; ?></a></li>
											<li><a style="width: 195px;" href="<?php echo $tocomments; ?>"><?php echo $text_commod; ?></a></li>
											<li><a style="width: 195px;" href="<?php echo $nauthor; ?>"><?php echo $text_nauthor; ?></a></li>
											<li><a style="width: 195px;" href="<?php echo $nmod; ?>"><?php echo $entry_nmod; ?></a></li>
											<li><a style="width: 195px;" href="<?php echo $ncmod; ?>"><?php echo $entry_ncmod; ?></a></li>
										</ul>
									</li>
									<li><a href="<?php echo $information_attribute; ?>">Статьи для атрибутов</a></li>
									<li><a href="<?php echo $faq_url; ?>">FAQ вопрос-ответ</a></li>
									<li><a href="<? echo $sms_link; ?>">SMS</a></li>
								</ul>
							</li>				 
							
							<li id="sale"><a class="top"><i class="fa fa-handshake-o icon_menu"></i><?php echo $text_sale; ?></a>
								<ul>
									<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
									<li><a href="<?php echo $waitlist; ?>">Лист ожидания</a></li> 
									<li><a href="<?php echo $parties; ?>">Партии</a></li> 		 		  
									<? if ($fucked_order_total > 0) { ?>
										<li><a href="<? echo $fucked_link; ?>">Незавершенные заказы <span style="color:#cf4a61;">(<? echo $fucked_order_total; ?>)</span></a></li>
									<? } ?>
									<li>
										<a href="<? echo $callback; ?>">
											Обратные звонки <span style="color:#cf4a61;">(<? echo $total_callbacks; ?>)</span>
										</a>
									</li>
									<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
									<li><a class="parent"><?php echo $text_customer; ?></a>
										<ul>
											<li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
											<li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
											<li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
										</ul>
									</li>
									<li><a href="<?php echo $user_sip; ?>">История телефонных звонков</a></li>
									<li><a href="<?php echo $rewards_gen; ?>">Генератор бонусных баллов</a></li>
									<li><a href="<?php echo $rewards_mod; ?>">Настройки бонусной программы</a></li>
									<li><a href="<?php echo $invite_after_order; ?>">Отзыв после покупки</a></li>
									<li><a href="<?php echo $affiliate; ?>">Партнеры / партн. программа</a></li>
									<li><a href="<?php echo $affiliate_mod_link; ?>">Настройки партн. программы</a></li>
									<li><a href="<?php echo $subscribe; ?>"><?php echo $text_subscribe; ?></a></li>
									<li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
									<li><a href="<?php echo $actions; ?>"><?php echo $text_actions; ?></a></li>
									<!--<li><a href="<?php echo $advanced_coupon; ?>"><?php echo $text_advanced_coupon; ?></a></li>-->
									<li><a class="parent"><?php echo $text_voucher; ?></a>
										<ul>
											<li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
											<li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
										</ul>
									</li>
									<li id="opt"><a class="parent">Опт</a>
										<ul>
											<li><a href="<?php echo $optprices; ?>">Управление ценами</a></li>       
											<li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
											<li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>	   
										</ul>
									</li>
									<!--li><a href="<?php echo $recurring_profile; ?>"><?php echo $text_recurring_profile; ?></a></li-->
									<!-- PAYPAL MANAGE NAVIGATION LINK -->
									<?php if ($pp_express_status) { ?>
										<li><a class="parent" href="<?php echo $paypal_express; ?>"><?php echo $text_paypal_express; ?></a>
											<ul>
												<li><a href="<?php echo $paypal_express_search; ?>"><?php echo $text_paypal_express_search; ?></a></li>
											</ul>
										</li>
									<?php } ?>
									<!-- PAYPAL MANAGE NAVIGATION LINK END -->		
									<li>
										<a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
									</li>
									<!--<li><a href="<?php echo $interplusplus; ?>"><?php echo $text_interplusplus; ?></a></li>-->
								</ul>
							</li>
							
							
							<li id="extension"><a class="top"><i class="fa fa-clone icon_menu"></i>Модули</a>
								<ul>
									<li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>	
									<li><a href="<?php echo $mattimeotheme; ?>">Настройки шаблона</a></li>
									<li><a href="<?php echo $etemplate; ?>">Настройка шаблонов EMail</a></li>
									<li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
									<li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
									<li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
									<li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
									<li><a href="<?php echo $vk_export; ?>" class="parent"><?php echo $text_vk_export; ?></a>
										<ul>
											<li><a href="<?php echo $vk_export; ?>"><?php echo $text_vk_export; ?></a></li>
											<li><a href="<?php echo $vk_export_albums; ?>"><?php echo $text_vk_export_albums; ?></a></li>
											<li><a href="<?php echo $vk_export_setting; ?>"><?php echo $text_vk_export_setting; ?></a></li>
											<li><a href="<?php echo $vk_export_report; ?>"><?php echo $text_vk_export_cron_report; ?></a></li>
										</ul>
									</li>
									<?php if ($openbay_show_menu == 1) { ?>
										<li><a class="parent"><?php echo $text_openbay_extension; ?></a>
											<ul>
												<li><a href="<?php echo $openbay_link_extension; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
												<li><a href="<?php echo $openbay_link_orders; ?>"><?php echo $text_openbay_orders; ?></a></li>
												<li><a href="<?php echo $openbay_link_items; ?>"><?php echo $text_openbay_items; ?></a></li>
												
												<?php if($openbay_markets['ebay'] == 1){ ?>
													<li><a class="parent" href="<?php echo $openbay_link_ebay; ?>"><?php echo $text_openbay_ebay; ?></a>
														<ul>
															<li><a href="<?php echo $openbay_link_ebay_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
															<li><a href="<?php echo $openbay_link_ebay_links; ?>"><?php echo $text_openbay_links; ?></a></li>
															<li><a href="<?php echo $openbay_link_ebay_orderimport; ?>"><?php echo $text_openbay_order_import; ?></a></li>
														</ul>
													</li>
												<?php } ?>
												
												<?php if($openbay_markets['amazon'] == 1){ ?>
													<li><a class="parent" href="<?php echo $openbay_link_amazon; ?>"><?php echo $text_openbay_amazon; ?></a>
														<ul>
															<li><a href="<?php echo $openbay_link_amazon_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
															<li><a href="<?php echo $openbay_link_amazon_links; ?>"><?php echo $text_openbay_links; ?></a></li>
														</ul>
													</li>
												<?php } ?>
												
												<?php if($openbay_markets['amazonus'] == 1){ ?>
													<li><a class="parent" href="<?php echo $openbay_link_amazonus; ?>"><?php echo $text_openbay_amazonus; ?></a>
														<ul>
															<li><a href="<?php echo $openbay_link_amazonus_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
															<li><a href="<?php echo $openbay_link_amazonus_links; ?>"><?php echo $text_openbay_links; ?></a></li>
														</ul>
													</li>
												<?php } ?>
											</ul>
										</li>
									<?php } ?>
								</ul>
							</li>
							<li id="modules"><a class="top"><i class="fa fa-newspaper-o icon_menu"></i>Модули тов.</a>
								<ul>
									<li><a href="<?php echo $mod_latest; ?>">Последние добавленные (авто)</a></li>
									<li><a href="<?php echo $mod_bestseller; ?>">Хиты продаж (авто)</a></li>
									<li><a href="<?php echo $mod_special; ?>">Акционные товары (авто)</a></li>
									<li><a href="<?php echo $mod_featured; ?>">Рекомендуемые (ручное)</a></li>
									<li><a href="<?php echo $mod_featuredreview; ?>">Рекомендуемые + отзывы (ручное)</a></li>
									<li><a href="<?php echo $mod_customproduct; ?>">Мультирекомендуемые (спешл фор Валерчик) (ручное)</a></li>
								</ul>
							</li>
							<li id="seo"><a class="top"><i class="fa fa-puzzle-piece icon_menu"></i>SEO</a>
								<ul>
									<li><a href="<?php echo $redirect_manager; ?>">Управление редиректами</a></li>
									<li><a href="<?php echo $seogen; ?>">Генератор СЕО-фишечек</a></li>
									<li><a href="<?php echo $autolink_link; ?>">Подмена слов на ссылки</a></li>
									<li><a href="<?php echo $keyworder_link; ?>">Связка произв./кат.</a></li>
									<li><a href="<?php echo HTTP_CATALOG.'reviewgen/'; ?>">Генератор отзывов</a></li>
								</ul>
							</li>
							<li id="system"><a class="top"><i class="fa fa-cogs icon_menu"></i><?php echo $text_system; ?></a>
								<ul>
									<li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
									<li><a class="parent"><?php echo $text_design; ?></a>
										<ul>
											<li><a href="<?php echo $layout; ?>">Схемы / макеты</a></li>
											<li><a href="<?php echo $custom_template_link; ?>">Индивидуальное переназначение шаблонов!</a></li>
											<li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
											<li><a href="<?php echo $advanced_banner_link; ?>">Умные баннера</a></li>
										</ul>
									</li>
									<li><a class="parent"><?php echo $text_users; ?></a>
										<ul>
											<li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
											<li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
										</ul>
									</li>
									<li><a href="<?php echo $translator; ?>">Перевод языковых файлов</a></li>
									<li><a class="parent"><?php echo $text_localisation; ?></a>
										<ul>
											<li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
											<li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
											<li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
											<li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
											<li><a class="parent"><?php echo $text_return; ?></a>
												<ul>
													<li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
													<li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
													<li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
												</ul>
											</li>
											<li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
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
										</ul>
									</li>		   
									<li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
									<li><a href="<?php echo $vqmod_manager; ?>"><?php echo $text_vqmod_manager; ?></a></li>
									<!--<li><a href="<?php echo $export; ?>"><?php echo $text_export; ?></a></li>-->
									<li><a href="<?php echo $backup; ?>">Стоковый бекап</a></li>
									<li><a href="<?php echo $csvpricelink; ?>">CSV IMPORT/EXPORT</a></li>
									<li><a href="<?php echo $anyport_link; ?>">Бекап в облако</a></li>
									<li><a href="<?php echo $excelport_link; ?>">ExcelPort (бекап в excel)</a></li>
									<li><a class="parent">Неиспользуемые функции</a>
										<ul>
											<li><a href="<?php echo $geoip_link; ?>">Настройка GeoIP</a></li>
											<li><a href="<?php echo $profile; ?>"><?php echo $text_profile; ?></a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li id="adminlog"><a class="top" href="<? echo $adminlog_url; ?>"><i class="fa fa-user icon_menu"></i>Журнал</a></li>	   
							<li id="reports"><a class="top"><i class="fa fa-area-chart icon_menu"></i><?php echo $text_reports; ?></a>
								<ul>				  		
									<li><a class="parent"><?php echo $text_sale; ?></a>
										<ul>
											<li><a href="<?php echo $report_adv_sale_order ?>">Универсальный отчет</a></li>	
											<li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
											<li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
											<li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
											<li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
											<li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
										</ul>
									</li>
									<li><a class="parent"><?php echo $text_product; ?></a>
										<ul>
											<li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
											<li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
										</ul>
									</li>
									<li><a class="parent"><?php echo $text_customer; ?></a>
										<ul>
											<li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
											<li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
											<li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
											<li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
										</ul>
									</li>
									<li><a class="parent"><?php echo $text_affiliate; ?></a>
										<ul>
											<li><a href="<?php echo $report_affiliate_commission; ?>"><?php echo $text_report_affiliate_commission; ?></a></li>
											<li><a href="<?php echo $report_affiliate_statistics; ?>"><?php echo $text_report_affiliate_statistics; ?></a></li>
											<li><a href="<?php echo $report_affiliate_statistics_all; ?>"><?php echo $text_report_affiliate_statistics_all; ?></a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li id="store"><a href="<?php echo $store; ?>" target="_blank" class="top"><i class="fa fa-share icon_menu"></i><?php echo $text_front; ?></a>
								<ul>
									<?php foreach ($stores as $stores) { ?>
										<li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
									<?php } ?>
								</ul>
							</li>
							<li><a class="top" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link icon_menu"></i></b></a></li>
							
						</ul>
						<style>
							#menu > ul li ul{
							display:none;
							}
							
							#menu > ul > li.hover > ul{
							display:block;
							}
							
							#menu > ul > li.sfhover > ul {
							display:block;
							}
							
							#menu > ul > li > ul > li > ul {
							display:none;
							}
							
							#menu > ul > li > ul > li:hover > ul{
							display:block;
							}
						</style>
						<!--<ul class="right">
							<li id="store"><a href="<?php echo $store; ?>" target="_blank" class="top"><?php echo $text_front; ?></a>
							<ul>
							<?php foreach ($stores as $stores) { ?>
								<li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
							<?php } ?>
							</ul>
							</li>
							<li><a class="top" href="<?php echo $logout; ?>"><b><i class="fa fa-external-link icon_down"></i></b></a></li>
						</ul>-->
						<div style="clear: both;"></div>
					</div>
				<?php } ?>
			</div>
			<div id="alert_history_preview"></div>
			<script>
				$('a#alert_history_preview_click').click(function(){
					$.ajax({
						url: 'index.php?route=user/user_alerts&token=<?php echo $token; ?>&ajax=1',
						dataType: 'html',				
						success : function(html){
							$('#alert_history_preview').html(html).dialog({width:800, height:800, modal:true,resizable:true,position:{my: 'center', at:'center center', of: window}, closeOnEscape: true, title: "Мои уведомления"})				
						}
					});
					return false;
				});	
			</script>																																																																							