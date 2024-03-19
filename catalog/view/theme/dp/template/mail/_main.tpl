<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Robots" content="all" />
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="author" content="<?php echo $store_name; ?>" />

	<title><?php echo $title; ?></title>
	<!-- new -->
	<!-- new -->
</head>

<body text="<?php echo $config['body_font_color']; ?>" link="<?php echo $config['body_link_color']; ?>" alink="<?php echo $config['body_link_color']; ?>" vlink="<?php echo $config['body_link_color']; ?>" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
	<span style="font-size:1px;">Інтернет-магазин Domopolis</span>
	<?php if(!empty($emailtemplate['preview'])){ ?>
	<div style="display:none !important; font-size:0 !important; line-height:0 !important;"><?php echo $emailtemplate['preview']; ?></div>
	<?php } ?>

	<?php
		$preauth_url = false;
		if (!empty($customer_id)){
			$customer = $this->db->non_cached_query("SELECT email FROM customer WHERE customer_id = '" . (int)$customer_id . "' LIMIT 1");

			if ($customer->num_rows){
				$preauth_url = 'https://domopolis.com.ua' .'?utm_term='.$customer->row['email'].'&utoken='.md5(md5($customer->row['email'].$customer->row['email']));
			}
		}
	?>


	<div id="emailTemplate">
		<table class="emailStyle<?php echo ucwords($config['style']); ?>" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="<?php echo $config['body_bg_color']; ?>">
			<?php if($config['head_text'] || isset($config['shadow_top']['length'])): ?>
			<tr>
				<td class="emailWrapper" bgcolor="<?php echo $config['body_bg_color']; ?>">
					<table class="emailHead" border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td width="100%" align="<?php echo $config['page_align']; ?>" valign="top" bgcolor="<?php echo ($config['head_section_bg_color']) ? $config['head_section_bg_color'] : $config['body_bg_color']; ?>">
								<?php if($config['page_align'] == 'center'){ ?><center>
										<!--[if mso]>
											<table align="center" border="0" cellspacing="0" cellpadding="0" width="<?php echo $config['email_full_width']; ?>"><tr><td>
											<![endif]--><?php } ?>
												<div class="mainContainer" align="<?php echo $config['page_align']; ?>">
													<table class="mainContainer" width="100%" cellpadding="0" cellspacing="0" border="0" align="<?php echo $config['page_align']; ?>">
														<?php if($config['head_text']): ?>
														<tr>
															<td>
																<table class="emailHeadText" border="0" cellspacing="0" cellpadding="0" width="100%">
																	<tr>
																		<td><?php echo $config['head_text']; ?></td>
																	</tr>
																</table>
															</td>
														</tr>
														<?php endif; ?>
														<tr>
															<td width="100%">
																<?php if(isset($config['shadow_top']['bg'])): ?>
																<table  class="emailShadow" border="0" cellspacing="0" cellpadding="0" width="100%">
																	<tr>
																		<td width="<?php echo $config['shadow_top']['left_img_width']; ?>" height="<?php echo $config['shadow_top']['left_img_height']; ?>" valign="top">
																			<img src="<?php echo $config['shadow_top']['left_img']; ?>" width="<?php echo $config['shadow_top']['left_img_width']; ?>" height="<?php echo $config['shadow_top']['left_img_height']; ?>" alt="" />
																		</td>
																		<td valign="top">
																			<table class="emailShadowTop" border="0" cellspacing="0" cellpadding="0" width="100%">
																				<?php echo $config['shadow_top']['bg']; ?>
																				<tr>
																					<td height="<?php echo $config['shadow_top']['overlap']; ?>" bgcolor="<?php echo $config['header_bg_color']; ?>">&nbsp;</td>
																				</tr>
																			</table>
																		</td>
																		<td width="<?php echo $config['shadow_top']['right_img_width']; ?>" height="<?php echo $config['shadow_top']['right_img_height']; ?>" valign="top">
																			<img src="<?php echo $config['shadow_top']['right_img']; ?>" width="<?php echo $config['shadow_top']['right_img_width']; ?>" height="<?php echo $config['shadow_top']['right_img_height']; ?>" alt="" />
																		</td>
																	</tr>
																</table>
																<?php endif; ?>
															</td>
														</tr>
													</table>
												</div>
										<?php if($config['page_align'] == 'center'){ ?><!--[if mso]>
											</td></tr></table>
										<![endif]-->
										</center><?php } ?>
									</td>
								</tr>
							</table>

						</td>
					</tr>
					<?php endif; ?>
					<tr >
						<td class="emailWrapper" bgcolor="<?php echo $config['body_bg_color']; ?>" width="100%" >
							<table class="emailHeader" border="0" cellspacing="0" cellpadding="0" width="100%">
								<tr>
									<td width="100%" align="<?php echo $config['page_align']; ?>" valign="top" bgcolor="<?php echo ($config['header_section_bg_color']) ? $config['header_section_bg_color'] : $config['body_bg_color']; ?>">
										<?php if($config['page_align'] == 'center'){ ?><center>
										<!--[if mso]>
											<table align="center" border="0" cellspacing="0" cellpadding="0" width="<?php echo $config['email_full_width']; ?>"><tr><td>
											<![endif]--><?php } ?>
												<div class="mainContainer" align="<?php echo $config['page_align']; ?>">
													<table class="mainContainer emailTableShadow emailHeaderHeight" width="100%" cellpadding="0" cellspacing="0" border="0" align="<?php echo $config['page_align']; ?>" height="<?php echo $config['header_height']; ?>">
														<tr>
															<?php if(isset($config['shadow_left']['bg'])) echo $config['shadow_left']['bg']; ?>
															<td>
																<table class="emailHeader" border="0" cellspacing="0" cellpadding="0" width="100%" <?php if($config['header_bg_image']){ ?> background="<?php echo $config['header_bg_image']; ?>"<?php } ?> bgcolor="<?php echo $config['header_bg_color']; ?>" style="padding: 0px 30px 0px 30px !important;">
																	<tr>
																		<td class="emailHeaderHeight" width="100%" height="<?php echo $config['header_height']; ?>" valign="<?php echo $config['logo_valign']; ?>" align="<?php echo $config['logo_align']; ?>" style="padding: 0px 30px 0px 30px !important;">
																			<?php if($config['header_bg_image']){ ?>
																		<!--[if gte mso 9]>
																			<v:image xmlns:v="urn:schemas-microsoft-com:vml" id="HeaderImage" style='behavior:url(#default#VML);display:inline-block;position:absolute; height:<?php echo $config['header_height']; ?>px; width:<?php echo $config['email_width']; ?>px;top:0;left:0;border:0;z-index:1;' src="<?php echo $config['header_bg_image']; ?>"/>
																			<v:shape xmlns:v="urn:schemas-microsoft-com:vml" id="HeaderText" style='behavior:url(#default#VML);display:inline-block;position:absolute;visibility:visible;height:<?php echo $config['header_height']-5; ?>px;width:<?php echo $config['email_width']; ?>px;background-color:transparent;top:-5px;left:-10px;border:0;z-index:2;' stroked='f'>
																			<div>
																			<![endif]-->
																				<?php } ?>
																				<table class="emailHeaderHeight" cellspacing="0" cellpadding="0" border="0" width="100%" height="<?php echo $config['header_height']; ?>"  style="padding: 0px 30px 0px 30px !important;">
																					<tr style="padding: 0px 30 0px 30px;">
																						<td  class="emailLogo emailHeaderHeight" width="122" height="<?php echo $config['header_height']; ?>" valign="<?php echo $config['logo_valign']; ?>" align="<?php echo $config['logo_align']; ?>">
																							<a href="<?php echo $store_url_tracking; ?>" target="_blank" title="<?php echo $store_name; ?>">
																								<span id="emailLogoHolder" style="display: inline-block;padding-top: 6px">
																									<?php if($logo){ ?>
																									<img class="emailLogo emailStretch" src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" border="0" height="<?php echo $config['logo_height']; ?>" width="<?php echo $config['logo_width']; ?>" />
																									<?php } else { ?>
																									<?php echo $store_name; ?>
																									<?php } ?>
																								</span>
																								<?php if(!empty($emailtemplate['tracking_img'])){ ?>
																								<img src="<?php echo $emailtemplate['tracking_img']; ?>" width="1" height="1" style="border:0;" />
																								<?php } ?>
																							</a>
																						</td>
																						<td style="padding-top: 0 !important;">
																							<table style="padding-top: 0 !important; vertical-align: top !important;">
																								<tr>
																									<td valign="top" align="right" style="text-align: right; vertical-align: top; margin-top: 20px;" idth="100%">
																										<span style="height: 24px;display: inline-block;vertical-align: top;line-height: 24px;">Будемо раді відповісти на ваші питання:</span>
																										<a target="_blank" href="https://m.me/105857422278656" style="display:inline-block;margin: 0 5px;width: 24px;height: 24px;">
																											<img title="messenger" src="https://domopolis.com.ua/catalog/view/theme/dp/template/mail/img/messenger.png" alt="messenger" width="25" height="25">
																										</a>
																										<a target="_blank" href="viber://pa?chatURI=domopolisua" style="display:inline-block;margin: 0 10px;width: 24px;height: 24px;">
																											<img title="viber" src="https://domopolis.com.ua/catalog/view/theme/dp/template/mail/img/viber.png" alt="viber" width="25" height="25">
																										</a>
																										<a target="_blank" href="https://t.me/domopolis_bot" style="display:inline-block;margin: 0 5px;width: 24px;height: 24px;">
																											<img title="teleg" src="https://domopolis.com.ua/catalog/view/theme/dp/template/mail/img/telegram.png" alt="teleg" width="25" height="25">
																										</a>
																									</td>
																								</tr>
																							</table>

																						</td>
																					</tr>
																					<tr width="450"  style="color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px;">

																					</tr>
																				</table>
																				<?php if($config['header_bg_image']){ ?>
																		<!--[if gte mso 9]>
																			</div>
																			</v:shape>
																		<![endif]-->
																			<?php } ?>
																		</td>
																	</tr>
																</table>
																<?php if($config['header_border_color']): ?>
																<table class="emailHeaderBorder" border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="<?php echo $config['header_border_color']; ?>">
																	<tr><td width="100%" height="1" valign="top">&nbsp;</td></tr>
																</table>
																<?php endif; ?>
															</td>
															<?php if(isset($config['shadow_right']['bg'])) echo $config['shadow_right']['bg']; ?>
														</tr>
													</table>
												</div>
										<?php if($config['page_align'] == 'center'){ ?><!--[if mso]>
											</td></tr></table>
										<![endif]-->
										</center><?php } ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="emailWrapper" bgcolor="<?php echo $config['body_bg_color']; ?>" width="100%">
							<table id="emailPage" border="0" cellspacing="0" cellpadding="0" width="100%">
								<tr>
									<td width="100%" align="<?php echo $config['page_align']; ?>" valign="top" bgcolor="<?php echo ($config['body_section_bg_color']) ? $config['body_section_bg_color'] : $config['body_bg_color']; ?>">
										<?php if($config['page_align'] == 'center'){ ?><center>
										<!--[if mso]>
											<table align="center" border="0" cellspacing="0" cellpadding="0" width="<?php echo $config['email_full_width']; ?>"><tr><td>
											<![endif]--><?php } ?>
												<div class="mainContainer" align="<?php echo $config['page_align']; ?>">
													<table class="mainContainer emailTableShadow" width="100%" cellpadding="0" cellspacing="0" border="0" align="<?php echo $config['page_align']; ?>">
														<tr>
															<?php if(isset($config['shadow_left']['bg'])) echo $config['shadow_left']['bg']; ?>
															<td>
																<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="<?php echo $config['page_bg_color']; ?>">
																	<tr>
																		<td align="<?php echo $config['text_align']; ?>" class="emailMainText">
																			{CONTENT}
																		</td>
																	</tr>																	
																		<?php if($emailtemplate['showcase'] && !empty($showcase_selection)): ?>
																		<tr>
																			<td>
																				<table class="emailSpacer" cellpadding="0" cellspacing="0" width="100%"><tr><td height="20" style="font-size:0;line-height:0;">&nbsp;</td></tr></table>
																			</td>
																		</tr>
																		<?php if($config['showcase_title']){ ?>
																		<tr>
																			<td align="<?php echo $config['text_align']; ?>" class="emailShowcase emailMainText">
																				<table cellpadding="0" cellspacing="0" border="0" width="100%">
																					<tr>
																						<td width="2"></td>
																						<td align="<?php echo $config['text_align']; ?>"><div class="heading3"><strong><?php echo $config['showcase_title']; ?></strong></div></td>
																					</tr>
																					<tr><td width="2" height="3" style="font-size:1px; line-height:0;">&nbsp;</td>
																						<td height="3" style="font-size:1px; line-height:0;">&nbsp;</td></tr>
																						<tr><td width="2" height="1" bgcolor="#cccccc" style="font-size:1px; line-height:0;">&nbsp;</td>
																							<td height="1" bgcolor="#cccccc" style="font-size:1px; line-height:0;">&nbsp;</td></tr>
																							<tr><td width="2" height="10" style="font-size:1px; line-height:0;">&nbsp;</td>
																								<td height="10" style="font-size:1px; line-height:0;">&nbsp;</td></tr>
																							</table>
																						</td>
																					</tr>
																					<?php } ?>
																					<tr>
																						<td align="<?php echo $config['text_align']; ?>" class="emailShowcase emailMainText">
																							<table border="0" cellspacing="0" cellpadding="0" width="100%">
																								<tr>
																									<td align="left">
																										<?php foreach($showcase_selection as $row){ ?>
																										<div class="emailShowcaseItem emailtemplateSpacing">
																											<table align="left" border="0" cellspacing="0" cellpadding="0" width="120" style="width:120px">
																												<tr>
																													<td valign="bottom" align="center" class="productTitle">
																														<a href="<?php echo $row['url_tracking']; ?>" target="_blank" title="<?php echo $row['preview']; ?>">
																															<?php echo $row['name_short']; ?>
																														</a>
																													</td>
																												</tr>
																												<?php if ($row['rating']) { ?>
																												<tr><td class="rating">
																													<img src="<?php echo $store_url; ?>catalog/view/theme/default/image/stars-<?php echo $row['rating']; ?>.png" alt="<?php echo $row['rating'] . ' ' . $text_rating ; ?>" />
																													<?php if($row['reviews'] > 1){ ?>(<?php echo $row['reviews']; ?>)<?php } ?>
																												</td></tr>
																												<?php } ?>
																												<?php if (isset($row['price'])) { ?>
																												<tr>
																													<td class="price">
																														<span class="price-inner">
																															<?php if (!$row['special']) { ?>
																															<?php echo $row['price']; ?>
																															<?php } else { ?>
																															<span class="price-old"><?php echo $row['price']; ?></span> <strong class="price-new"><?php echo $row['special']; ?></strong>
																															<?php } ?>
																														</span>
																													</td>
																												</tr>
																												<?php } ?>
																												<tr><td height="5"></td></tr>
																												<tr>
																													<td height="120" valign="top" align="center" class="img">
																														<?php if($row['image']): ?><a href="<?php echo $row['url_tracking']; ?>" target="_blank" title="<?php echo $row['preview']; ?>" class="emailtemplateNoDisplay">
																															<img src="<?php echo $row['image']; ?>" width="<?php echo $row['image_width']; ?>" height="<?php echo $row['image_height']; ?>" alt="" /></a>
																															<?php endif; ?>
																														</td>
																													</tr>
																												</table>
																											</div>
																											<?php } ?>
																										</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																						<?php endif; ?>
																						<?php if(false && $config['page_footer_text']){ ?>
																						<tr><td class="emailMainText"><?php echo $config['page_footer_text']; ?></td></tr>
																						<?php } ?>

																						<?php if ($preauth_url) { ?>
																						<tr>
																							<td class="emailWrapper" bgcolor="<?php echo $config['body_bg_color']; ?>" width="100%" style="padding:10px 0 20px 0;background-color:#fff !important;">
																								<table class="emailFooter" border="0" cellspacing="0" cellpadding="0" width="100%">
																									<tr>
																										<td style="background-color:#fff !important;" class="emailFooterCell" width="100%" align="<?php echo $config['page_align']; ?>" valign="top" bgcolor="<?php echo ($config['footer_section_bg_color']) ? $config['footer_section_bg_color'] : $config['body_bg_color']; ?>">
																											<a style="text-decoration:underline;   font-size: 16px; padding: 10px; width: 80%;color: #51a881;font-weight: 500;" href="<?php echo($preauth_url); ?>">Перейти до магазину</a>
																										</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																						<?php } ?>

																					</table>
																					<!-- new -->
																					<table style="Margin:10px;">
																						<tr>
																							<td class="emailWrapper1"  width="100%" style="padding:10px;">
																								<table class="es-table-not-adapt es-social" cellspacing="0" cellpadding="0" role="presentation" >
																									<tbody>
																										<tr style="border-collapse:collapse">
																											<td align="center" style="padding:0;Margin:0;padding-bottom:5px;padding-left:20px;padding-right:20px">
																												<table border="0" width="50%" height="100%" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
																													<tbody>
																														<tr style="border-collapse:collapse">
																															<td style="max-width:300px;padding:0;Margin:0;border-bottom:1px solid #DEDEDE;background:none;height:1px;width:100%;margin:0px"></td>
																														</tr>
																													</tbody>
																												</table>
																											</td>
																										</tr>

																									</tbody>
																								</table>
																							</td>
																						</tr>
																					</table>
																					<!-- new -->
																				</td>
																				<?php if(isset($config['shadow_right']['bg'])) echo $config['shadow_right']['bg']; ?>
																			</tr>
																		</table>
																	</div>
										<?php if($config['page_align'] == 'center'){ ?><!--[if mso]>
											</td></tr></table>
										<![endif]-->
										</center><?php } ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="emailWrapper" bgcolor="<?php echo $config['body_bg_color']; ?>" width="100%">
							<table class="emailFooter" border="0" cellspacing="0" cellpadding="0" width="100%">
								<tr>
									<td class="emailFooterCell" width="100%" align="<?php echo $config['page_align']; ?>" valign="top" bgcolor="<?php echo ($config['footer_section_bg_color']) ? $config['footer_section_bg_color'] : $config['body_bg_color']; ?>">
										<?php if($config['page_align'] == 'center'){ ?><center>
										<!--[if mso]>
											<table align="center" border="0" cellspacing="0" cellpadding="0" width="<?php echo $config['email_full_width']; ?>"><tr><td>
											<![endif]--><?php } ?>
												<div class="mainContainer" align="<?php echo $config['page_align']; ?>">
													<table class="mainContainer" width="100%" cellpadding="0" cellspacing="0" border="0" align="<?php echo $config['page_align']; ?>">
														<tr>
															<td width="100%">
																<?php if(isset($config['shadow_bottom']['bg'])): ?>
																<table class="emailShadow emailTableShadow" border="0" cellspacing="0" cellpadding="0" width="100%">
																	<tr>
																		<?php if($config['shadow_bottom']['left_img']){ ?>
																		<td width="<?php echo $config['shadow_bottom']['left_img_width']; ?>" height="<?php echo $config['shadow_bottom']['left_img_height']; ?>" valign="top">
																			<img src="<?php echo $config['shadow_bottom']['left_img']; ?>" width="<?php echo $config['shadow_bottom']['left_img_width']; ?>" height="<?php echo $config['shadow_bottom']['left_img_height']; ?>" alt="" border="0" />
																		</td>
																		<?php } ?>
																		<td valign="top">
																			<table class="emailShadowBottom emailTableShadow" border="0" cellspacing="0" cellpadding="0" width="100%">
																				<?php if($config['shadow_bottom']['overlap']): ?>
																				<tr><td height="<?php echo $config['shadow_bottom']['overlap']; ?>" bgcolor="<?php echo $config['page_bg_color']; ?>">&nbsp;</td></tr>
																				<?php endif; ?>
																				<?php echo $config['shadow_bottom']['bg']; ?>
																			</table>
																		</td>
																		<?php if($config['shadow_bottom']['right_img']){ ?>
																		<td width="<?php echo $config['shadow_bottom']['right_img_width']; ?>" height="<?php echo $config['shadow_bottom']['right_img_height']; ?>" valign="top">
																			<img src="<?php echo $config['shadow_bottom']['right_img']; ?>" width="<?php echo $config['shadow_bottom']['right_img_width']; ?>" height="<?php echo $config['shadow_bottom']['right_img_height']; ?>" alt="" border="0" />
																		</td>
																		<?php } ?>
																	</tr>
																</table>
																<?php endif; ?>

																<table class="emailFooterText" border="0" cellspacing="0" cellpadding="0" width="100%" height="<?php echo $config['footer_height']; ?>">
																	<tr>
																		<td style="text-align: justify;padding: 0 10px;" valign="<?php echo $config['footer_valign']; ?>" height="<?php echo $config['footer_height']; ?>" width="100%">
																			<?php if(isset($unsubscribe)){ ?>
																			<div class="emailUnsubscribe">
																				<?php echo $unsubscribe; ?>
																				<br />
																			</div>
																			<?php } ?>

																			<?php if(isset($view_browser)){ ?>
																			<div>
																				<?php echo $view_browser; ?>
																				<br />
																			</div>
																			<?php } ?>
																			<?php echo $config['footer_text']; ?>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</div>
										<?php if($config['page_align'] == 'center'){ ?><!--[if mso]>
											</td></tr></table>
										<![endif]-->
										</center><?php } ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="30" style="font-size:1px; line-height:0; mso-margin-top-alt:1px">&nbsp;</td>
					</tr>
				</table>
			</div>

			<style type="text/css">

				v\:* { behavior: url(#default#VML); display:inline-block} /* background image hack for outlook */

				<?php if($config['email_responsive']): ?>
				@media all and (max-width: <?php echo $config['email_full_width']; ?>px) {
					<?php /* #emailTemplate img[class=emailStretch] { width:100% !important; height:auto !important; max-width:<?php echo $config['email_width']; ?>px !important; display:block !important } */ ?>
					#emailTemplate .mainContainer { max-width:none !important; width:100% !important; margin-left: 0 !important; margin-right: 0 !important }
					#emailTemplate .emailMainText { padding: 15px 10px 5px 10px !important }
					#emailTemplate .emailShadow { display: none !important;}
					#emailTemplate .emailHeaderHeight { height: auto !important;}
					#emailTemplate td.emailLogo { padding: 20px 0 !important;}
					#emailTemplate .heading1, #emailTemplate .heading2, #emailTemplate .title { font-size: 16px !important; line-height:20px !important; }
					#emailTemplate .heading3 { font-size: 13px !important; }
					#emailTemplate .link { font-size: 11px !important; line-height:16px !important; }
					#emailTemplate .table1 td.price { font-size: 11px !important }
					#emailTemplate .emailFooterText p { font-size: 11px !important }
					<?php if($config['body_bg_color']){ ?>
						#emailTemplate .emailFooterCell { background-color: <?php echo $config['page_bg_color']; ?> !important }
						<?php } ?>
					}

					@media only screen and (max-width: 480px) {
						#emailTemplate .tableStack, #emailTemplate .tableStack tbody, #emailTemplate .tableStack tr {
							display: block !important;
							width: 100% !important
						}
						#emailTemplate .tableStack td {
							display: block !important;
							padding-bottom: 8px !important;
							width: auto !important;
						}
						#emailTemplate .tableStack td.last-child {
							border-top: 1px solid #E0E0E0 !important;
							border-left: none !important;
						}
						#emailTemplate .product-image {
							float: none !important;
							display: block !important;
						}
						#emailTemplate .clearMobile {
							clear: both !important;
						}
					}

			<?php /* Replace logo with background image for mobile
				@media all and (max-device-width: 489px) {
				#emailTemplate td.emailLogo span {
				background-image: url(http://www.sample.com/mobile.jpg) !important;
				background-repeat: no-repeat !important;
				background-position: center !important;
				display: block !important;
				width: 100px !important;
				height: 100px !important;
				}
				#emailTemplate img.emailLogo { visibility: hidden !important }
				}
			*/?>
					<?php endif; ?>
				</style>
			</body>
			</html>