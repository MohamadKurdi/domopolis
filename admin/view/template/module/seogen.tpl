<?php echo $header; ?>
<style type="text/css">
	.seogen-list {
	border-collapse: collapse;
	}
	
	.seogen-list tbody td {
	padding: 5px;
	vertical-align: top;
	}
	.seogen-list input[type="text"] {
	width: 430px;
	}
	.seogen-list a.button {
	float: right;
	margin-top: 5px;
	margin-right: 10px
	}
	.seogen-list td {
	border-bottom: inherit;
	border-right: inherit;
	}
	.seogen-table td {
	padding-bottom:20px;		
	}
	.seogen-list span.bold {
	font-weight: bold;
	}
	#form input[type="text"] {
	width: 669px;
	}
	.test_click{
		float:left;margin-right:10px;
		margin-top:8px;
	}
	.test_click i{
		cursor:pointer;
	}
	.generate_test{
		border:1px solid #ccc; padding:5px;
		float:left;
		margin-top:8px;
		max-width:500px;
		display:none;
	}
</style>
<div id="content">
	<div class="breadcrumb">
		<?php foreach($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading order_head">
			<h1><img src="view/image/module.png" alt=""/><?php echo $heading_title; ?></h1>
			
			<div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';"
			class="button"><?php echo $button_cancel; ?></a></div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="seogen-list" style="width: 100%; margin-bottom:20px;">
					<tbody>
						<tr>
							<td class="left">
								<input id="seogen_status" class="checkbox" type="checkbox" name="seogen_status" <?php if($seogen_status){echo 'checked="checked"';}?>>
								<label for="seogen_status">Генерировать при редактировании?</label>
							</td>
							<td class="right">
								<input id="seogen_overwrite" class="checkbox" type="checkbox">
								<label for="seogen_overwrite"><?php echo $entry_overwrite; ?></label>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="tabs_languages" class="htabs">		
					<?php foreach ($languages as $language) { ?>
						<a href="#tab-lang<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $language['name']; ?></a>		
					<? } ?>
					<a href="#tab-onlyurls"><?php foreach ($languages as $language) { ?><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<? } ?>Только SEO_URL!</a>
					<div class="clr"></div>
				</div>
				<div class="th_style"></div>
				<div id="tab-onlyurls">
					<table class="seogen-table">
						<tbody id="seourls" style="border: 1px solid #003A88;">
							<tr>
								<td style="width: 120px;"><?php echo $text_categories; ?></td>
								<td>
									<input type="text" name="seogen[categories_template]" value="<?php echo $seogen['categories_template']; ?>"><br/>
									<?php echo $text_available_tags . ' <span class="bold">' . $text_categories_tags . '</span>'; ?>
								</td>
								<td>
									<input id="seogen[categories_overwrite]" class="checkbox" type="checkbox" name="seogen[categories_overwrite]" <?php if (isset($seogen['categories_overwrite'])) echo "checked='checked'"; ?>>
									<label for="seogen[categories_overwrite]"></label>
								</td>
							</tr>
							
							<tr>
								<td><?php echo $text_products ?></td>
								<td>
									<input type="text" name="seogen[products_template]" value="<?php echo $seogen['products_template']; ?>"><br/>
									<?php echo $text_available_tags . ' <span class="bold">' . $text_products_tags . '</span>'; ?>
								</td>
								<td>
									<input id="seogen[products_overwrite]" class="checkbox" type="checkbox" name="seogen[products_overwrite]" <?php if (isset($seogen['products_overwrite'])) echo "checked='checked'"; ?>>
									<label for="seogen[products_overwrite]"></label>
								</td>
							</tr>
							<tr>
								<td><?php echo $text_manufacturers ?></td>
								<td>
									<input type="text" name="seogen[manufacturers_template]"
									value="<?php echo $seogen['manufacturers_template']; ?>"><br/>
									<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_tags . '</span>'; ?>
								</td>
								<td>
									<input id="seogen[manufacturers_overwrite]" class="checkbox" type="checkbox" name="seogen[manufacturers_overwrite]" <?php if (isset($seogen['manufacturers_overwrite'])) echo "checked='checked'"; ?>>
									<label for="seogen[manufacturers_overwrite]"></label>
								</td>
							</tr>
							
							<tr>
								<td><?php echo $text_collections ?></td>
								<td>
									<input type="text" name="seogen[collections_template]"
									value="<?php echo $seogen['collections_template']; ?>"><br/>
									<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_tags . '</span>'; ?>
								</td>
								<td>
									<input id="seogen[collections_overwrite]" class="checkbox" type="checkbox" name="seogen[collections_overwrite]" <?php if (isset($seogen['collections_overwrite'])) echo "checked='checked'"; ?>>
									<label for="seogen[collections_overwrite]"></label>
								</td>
							</tr>
							
							<tr>
								<td><?php echo $text_informations ?></td>
								<td>
									<input type="text" name="seogen[informations_template]" value="<?php echo $seogen['informations_template']; ?>"><br/>
									<?php echo $text_available_tags . ' <span class="bold">' . $text_informations_tags . '</span>'; ?>
								</td>
								<td>
									<input id="seogen[informations_overwrite]" class="checkbox" type="checkbox" name="seogen[informations_overwrite]" <?php if (isset($seogen['informations_overwrite'])) echo "checked='checked'"; ?>>
									<label for="seogen[informations_overwrite]"></label>
								</td>
							</tr>
							
							<tr>
								<td><?php echo $entry_category; ?></td>
								<td><div class="scrollbox">
									<?php $class = 'odd'; ?>
									<?php foreach ($categories as $category) { ?>
										<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
										<div class="<?php echo $class; ?>">
											<?php if (in_array($category['category_id'], $seogen['only_categories'])) { ?>
												<input id="<?php echo $category['category_id']; ?>" class="checkbox" type="checkbox" name="seogen[only_categories][]" value="<?php echo $category['category_id']; ?>" checked="checked" />
												<label for="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
												<?php } else { ?>
												<input id="<?php echo $category['category_id']; ?>" class="checkbox" type="checkbox" name="seogen[only_categories][]" value="<?php echo $category['category_id']; ?>" />
												<label for="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></label>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
								<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
							</tr>
							
							<?/*	
								<tr>
								<td colspan="3">
								<a onclick="generate('categories', 0);" class="button"><?php echo $text_generate; ?>&nbsp;<?php echo $tab_categories; ?></a>
								<a onclick="generate('products', 0);" class="button"><?php echo $text_generate; ?>&nbsp;<?php echo $tab_products; ?></a>
								<a onclick="generate('manufacturers', 0);" class="button"><?php echo $text_generate; ?>&nbsp;<?php echo $tab_manufacturers; ?></a>
								<a onclick="generate('collections', 0);" class="button"><?php echo $text_generate; ?>&nbsp;<?php echo $tab_collections; ?></a>
								<a onclick="generate('informations', 0);" class="button"><?php echo $text_generate; ?>&nbsp;<?php echo $tab_news; ?></a>
								</td>
								</tr>
							*/ ?>
						</tbody>
					</table>
				</div>
				<? // var_dump($seogen[20]['categories_title_template']); ?>
				<?php foreach ($languages as $language) { ?>
					<div id="tab-lang<?php echo $language['language_id']; ?>">
						
						<div id="tabs<?php echo $language['language_id']; ?>" class="htabs">
							<a href="#tab-categories<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $tab_categories; ?></a>
							<a href="#tab-products<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $tab_products; ?></a>
							<a href="#tab-manufacturers<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $tab_manufacturers; ?></a>
							
							<a href="#tab-manufacturers-tags<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;Бренды спец-тэги</a>
							
							<a href="#tab-collections<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;Коллекции</a>
							<a href="#tab-news<?php echo $language['language_id']; ?>"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $tab_news; ?></a>
						</div>
						<div id="tab-categories<?php echo $language['language_id']; ?>">
							<table class="seogen-table" width="100%">
								<tbody id="categories<?php echo $language['language_id']; ?>" style="border: 1px solid #003A88;">					
									<?php if(isset($seogen[$language['language_id']]['categories_h1_template'])) { ?>					
										<tr>
											<td style="width: 120px;"><?php echo $text_categories_h1; ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][categories_h1_template]"  value="<?php echo $seogen[$language['language_id']]['categories_h1_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_categories_h1_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][categories_h1_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][categories_h1_overwrite]" <?php if (isset($seogen[$language['language_id']]['categories_h1_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][categories_h1_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['categories_title_template'])) { ?>
										<tr>
											<td style="width: 120px;"><?php echo $text_categories_title; ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][categories_title_template]"
												value="<?php echo $seogen[$language['language_id']]['categories_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_categories_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][categories_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][categories_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['categories_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][categories_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['categories_meta_keyword_template'])) { ?>
										<tr>
											<td style="width: 120px;"><?php echo $text_categories_meta_keyword; ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][categories_meta_keyword_template]"
												value="<?php echo $seogen[$language['language_id']]['categories_meta_keyword_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_categories_meta_keyword_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][categories_meta_keyword_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][categories_meta_keyword_overwrite]" <?php if (isset($seogen[$language['language_id']]['categories_meta_keyword_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][categories_meta_keyword_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['categories_meta_description_template'])) { ?>
										<tr>
											<td style="width: 120px;"><?php echo $text_categories_meta_description; ?></td>
											<td>
												<textarea cols="125" rows="2" name="seogen[<?php echo $language['language_id']; ?>][categories_meta_description_template]"><?php echo $seogen[$language['language_id']]['categories_meta_description_template']; ?></textarea><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_categories_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][categories_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][categories_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['categories_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][categories_meta_description_overwrite]"></label>
											</td>
										</tr>
										<tr>
											<td><?php echo $text_meta_description_limit?></td>
											<td><input type="text" name="seogen[<?php echo $language['language_id']; ?>][categories_meta_description_limit]" style="width: 40px" value="<?php echo $seogen[$language['language_id']]['categories_meta_description_limit']?>" size="3"></td>
										</tr>
										<tr>
											<td><?php echo $text_use_expressions?></td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][categories_use_expressions]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][categories_use_expressions]" <?php if (isset($seogen[$language['language_id']]['categories_use_expressions'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][categories_use_expressions]"></label>
											</td>
										</tr>
									<?php } ?>
									<tr>
										<td><?php echo $text_categories_description ?></td>
										<td>
											<textarea id="categories_description_template<?php echo $language['language_id']; ?>" cols="125" rows="2" name="seogen[<?php echo $language['language_id']; ?>][categories_description_template]"><?php echo $seogen[$language['language_id']]['categories_description_template']; ?></textarea><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_categories_description_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][categories_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][categories_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['categories_description_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][categories_description_overwrite]"></label>
										</td>
									</tr>
									
									<tr>
										<td colspan="3"><a onclick="generate('categories', <?php echo $language['language_id']; ?>);" class="button"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $text_generate; ?>&nbsp;<?php echo $tab_categories; ?></a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id ="tab-products<?php echo $language['language_id']; ?>">
							<table class="seogen-table" width="100%">
								<tbody id="products<?php echo $language['language_id']; ?>" style="border: 1px solid #003A88;">
									
									<?php if(isset($seogen[$language['language_id']]['products_h1_template'])) { ?>
										<tr>
											<td><?php echo $text_products_h1 ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][products_h1_template]" value="<?php echo $seogen[$language['language_id']]['products_h1_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_products_h1_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][products_h1_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_h1_overwrite]" <?php if (isset($seogen[$language['language_id']]['products_h1_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][products_h1_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['products_title_template'])) { ?>
										<tr>
											<td><?php echo $text_products_title ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][products_title_template]"
												value="<?php echo $seogen[$language['language_id']]['products_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_products_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][products_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['products_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][products_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['products_meta_keyword_template'])) { ?>
										<tr>
											<td><?php echo $text_products_meta_keyword ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][products_meta_keyword_template]"
												value="<?php echo $seogen[$language['language_id']]['products_meta_keyword_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_products_meta_keyword_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][products_meta_keyword_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_meta_keyword_overwrite]" <?php if (isset($seogen[$language['language_id']]['products_meta_keyword_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][products_meta_keyword_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['products_meta_description_template'])) { ?>
										<tr>
											<td><?php echo $text_products_meta_description ?></td>
											<td>
												<textarea cols="125" rows="2" name="seogen[<?php echo $language['language_id']; ?>][products_meta_description_template]"><?php echo $seogen[$language['language_id']]['products_meta_description_template']; ?></textarea><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_products_meta_description_tags . '</span>'; ?>
												<br />
												<div>
												<div class="test_click">
													<i class="fa fa-refresh" aria-hidden="true" onclick="generate_test('products', 'meta_description', <? echo $language['language_id']; ?>)"></i>
												</div>
													<div class="generate_test" id="products_meta_description_<? echo $language['language_id']; ?>">
													</div>
												</div>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][products_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['products_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][products_meta_description_overwrite]"></label>
											</td>
										</tr>									
										<tr>
											<td><?php echo $text_meta_description_limit ?></td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][products_meta_description_limit]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_meta_description_limit]" value="<?php echo $seogen[$language['language_id']]['products_meta_description_limit'] ?>" <?php if (isset($seogen[$language['language_id']]['categories_use_expressions'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][products_meta_description_limit]"></label>
											</td>
										</tr>
									<?php } ?>
									<tr>
										<td><?php echo $text_img_alt?></td>
										<td><input type="text" name="seogen[<?php echo $language['language_id']; ?>][products_img_alt_template]" value="<?php echo isset($seogen[$language['language_id']]['products_img_alt_template'])? $seogen[$language['language_id']]['products_img_alt_template'] : ""?>"></td>
									</tr>
									<tr>
										<td><?php echo $text_img_title?></td>
										<td><input type="text" name="seogen[<?php echo $language['language_id']; ?>][products_img_title_template]" value="<?php echo isset($seogen[$language['language_id']]['products_img_title_template'])? $seogen[$language['language_id']]['products_img_title_template'] : ""?>"></td>
									</tr>
									<tr>
										<td><?php echo $text_products_description ?></td>
										<td>
											<textarea id="products_description_template<?php echo $language['language_id']; ?>" cols="125" rows="2" name="seogen[<?php echo $language['language_id']; ?>][products_description_template]"><?php echo $seogen[$language['language_id']]['products_description_template']; ?></textarea><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_products_description_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][products_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['products_description_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][products_description_overwrite]"></label>
										</td>
									</tr>
									<tr>
										<td><?php echo $text_products_model ?></td>
										<td>
											<input type="text" name="seogen[<?php echo $language['language_id']; ?>][products_model_template]" value="<?php echo $seogen[$language['language_id']]['products_model_template']; ?>"><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_products_model_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][products_model_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_model_overwrite]" <?php if (isset($seogen[$language['language_id']]['products_model_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][products_model_overwrite]"></label>
										</td>
									</tr>
									<tr>
										<td><?php echo $text_use_expressions?></td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][products_use_expressions]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][products_use_expressions]" <?php if (isset($seogen[$language['language_id']]['products_use_expressions'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][products_use_expressions]"></label>
										</td>
									</tr>
									<tr>
										<td><?php echo $entry_category; ?></td>
										<td><div class="scrollbox">
											<?php $class = 'odd'; ?>
											<?php foreach ($categories as $category) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($category['category_id'], $seogen[$language['language_id']]['only_categories'])) { ?>
														<input id="seogen[<?php echo $language['language_id']; ?>][only_categories][]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][only_categories][]" value="<?php echo $category['category_id']; ?>" checked="checked" />
														<label for="seogen[<?php echo $language['language_id']; ?>][only_categories][]"><?php echo $category['name']; ?></label>
														<?php } else { ?>
														<input id="seogen[<?php echo $language['language_id']; ?>][only_categories][]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][only_categories][]" value="<?php echo $category['category_id']; ?>" />
														<label for="seogen[<?php echo $language['language_id']; ?>][only_categories][]"><?php echo $category['name']; ?></label>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
										<a class="select_all" onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a><a class="remove_selection" onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
									</tr>
									<tr>
										<td colspan="3"><a onclick="generate('products', <?php echo $language['language_id']; ?>);" class="button"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $text_generate; ?>&nbsp;<?php echo $tab_products; ?></a></td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<div id ="tab-manufacturers-tags<?php echo $language['language_id']; ?>">
							<table class="seogen-table" width="100%">
								<tbody id="manufacturers-tags<?php echo $language['language_id']; ?>" style="border: 1px solid #003A88;">								
									<?php if(isset($seogen[$language['language_id']]['manufacturers_products_title_template'])) { ?>
										<tr>
											<td><i class="fa fa-cubes"></i> Товары Тайтл</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_title_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_products_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_products_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>

									<?php if(isset($seogen[$language['language_id']]['manufacturers_products_meta_description_template'])) { ?>
										<tr>
											<td><i class="fa fa-cubes"></i> Товары Мета Дескрипшн</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_products_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_products_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_products_meta_description_overwrite]"></label>
											</td>
											
										</tr>
									<?php } ?>
									
									<?php if(isset($seogen[$language['language_id']]['manufacturers_collections_title_template'])) { ?>
										<tr>
											<td><i class="fa fa-bars"></i> Коллекции Тайтл</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_title_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_collections_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_collections_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>

									<?php if(isset($seogen[$language['language_id']]['manufacturers_collections_meta_description_template'])) { ?>
										<tr>
											<td><i class="fa fa-bars"></i> Коллекции Мета Дескрипшн</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_collections_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_collections_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_collections_meta_description_overwrite]"></label>
											</td>
											
										</tr>
									<?php } ?>
									
									<?php if(isset($seogen[$language['language_id']]['manufacturers_categories_title_template'])) { ?>
										<tr>
											<td><i class="fa fa-database"></i> Категории Тайтл</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_title_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_categories_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_categories_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>

									<?php if(isset($seogen[$language['language_id']]['manufacturers_categories_meta_description_template'])) { ?>
										<tr>
											<td><i class="fa fa-database"></i> Категории Мета Дескрипшн</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_categories_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_categories_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_categories_meta_description_overwrite]"></label>
											</td>
											
										</tr>
									<?php } ?>
									
									<?php if(isset($seogen[$language['language_id']]['manufacturers_articles_title_template'])) { ?>
										<tr>
											<td><i class="fa fa-map-o"></i> Статьи Тайтл</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_title_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_articles_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_articles_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>

									<?php if(isset($seogen[$language['language_id']]['manufacturers_articles_meta_description_template'])) { ?>
										<tr>
											<td><i class="fa fa-map-o"></i> Статьи Мета Дескрипшн</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_articles_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_articles_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_articles_meta_description_overwrite]"></label>
											</td>
											
										</tr>
									<?php } ?>
									
									<?php if(isset($seogen[$language['language_id']]['manufacturers_newproducts_title_template'])) { ?>
										<tr>
											<td><i class="fa fa-tags"></i> Новинки Тайтл</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_title_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_newproducts_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_newproducts_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>

									<?php if(isset($seogen[$language['language_id']]['manufacturers_newproducts_meta_description_template'])) { ?>
										<tr>
											<td><i class="fa fa-tags"></i> Новинки Мета Дескрипшн</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_newproducts_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_newproducts_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_newproducts_meta_description_overwrite]"></label>
											</td>
											
										</tr>
									<?php } ?>
									
									<?php if(isset($seogen[$language['language_id']]['manufacturers_special_title_template'])) { ?>
										<tr>
											<td><i class="fa fa-thumbs-up"></i> Скидки Тайтл</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_title_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_special_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_special_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>

									<?php if(isset($seogen[$language['language_id']]['manufacturers_special_meta_description_template'])) { ?>
										<tr>
											<td><i class="fa fa-thumbs-up"></i> Скидки Мета Дескрипшн</td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_special_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_special_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_special_meta_description_overwrite]"></label>
											</td>
											
										</tr>
									<?php } ?>
									
									<tr>
										<td colspan="3"><a onclick="generate('manufacturers-tags', <?php echo $language['language_id']; ?>);" class="button"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">Генерировать спец-тэги</a></td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<div id ="tab-manufacturers<?php echo $language['language_id']; ?>">
							<table class="seogen-table" width="100%">
								<tbody id="manufacturers<?php echo $language['language_id']; ?>" style="border: 1px solid #003A88;">
									
									<?php if(isset($seogen[$language['language_id']]['manufacturers_h1_template'])) { ?>
										<tr>
											<td><?php echo $text_manufacturers_h1 ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_h1_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_h1_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_h1_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_h1_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_h1_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_h1_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_h1_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['manufacturers_title_template'])) { ?>
										<tr>
											<td><?php echo $text_manufacturers_title ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_title_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['manufacturers_meta_keyword_template'])) { ?>
										<tr>
											<td><?php echo $text_manufacturers_meta_keyword ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_keyword_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_meta_keyword_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_keyword_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_keyword_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_keyword_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_meta_keyword_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_keyword_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['manufacturers_meta_description_template'])) { ?>
										<tr>
											<td><?php echo $text_manufacturers_meta_description ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['manufacturers_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_meta_description_overwrite]"></label>
											</td>
											
										</tr>
										<tr>
											<td><?php echo $text_use_expressions?></td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_use_expressions]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_use_expressions]" <?php if (isset($seogen[$language['language_id']]['manufacturers_use_expressions'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_use_expressions]"></label>
											</td>
										</tr>
									<?php } ?>
									<tr>
										<td><?php echo $text_manufacturers_description ?></td>
										<td>
											<textarea id="manufacturers_description_template<?php echo $language['language_id']; ?>" cols="125" rows="2" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_description_template]"><?php echo $seogen[$language['language_id']]['manufacturers_description_template']; ?></textarea><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_manufacturers_description_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][manufacturers_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][manufacturers_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['manufacturers_description_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][manufacturers_description_overwrite]"></label>
										</td>
									</tr>
									
									<tr>
										<td colspan="3"><a onclick="generate('manufacturers', <?php echo $language['language_id']; ?>);" class="button"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $text_generate; ?>&nbsp;<?php echo $tab_manufacturers; ?></a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id ="tab-collections<?php echo $language['language_id']; ?>">
							<table class="seogen-table" width="100%">
								<tbody id="collections<?php echo $language['language_id']; ?>" style="border: 1px solid #003A88;">
									
									<?php if(isset($seogen[$language['language_id']]['collections_h1_template'])) { ?>
										<tr>
											<td><?php echo $text_collections_h1 ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][collections_h1_template]"
												value="<?php echo $seogen[$language['language_id']]['collections_h1_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_collections_h1_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][collections_h1_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][collections_h1_overwrite]" <?php if (isset($seogen[$language['language_id']]['collections_h1_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][collections_h1_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['collections_title_template'])) { ?>
										<tr>
											<td><?php echo $text_collections_title ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][collections_title_template]"
												value="<?php echo $seogen[$language['language_id']]['collections_title_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_collections_title_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][collections_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][collections_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['collections_title_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][collections_title_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['collections_meta_keyword_template'])) { ?>
										<tr>
											<td><?php echo $text_collections_meta_keyword ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][collections_meta_keyword_template]"
												value="<?php echo $seogen[$language['language_id']]['collections_meta_keyword_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_collections_meta_keyword_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][collections_meta_keyword_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][collections_meta_keyword_overwrite]" <?php if (isset($seogen[$language['language_id']]['collections_meta_keyword_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][collections_meta_keyword_overwrite]"></label>
											</td>
										</tr>
									<?php } ?>
									<?php if(isset($seogen[$language['language_id']]['collections_meta_description_template'])) { ?>
										<tr>
											<td><?php echo $text_collections_meta_description ?></td>
											<td>
												<input type="text" name="seogen[<?php echo $language['language_id']; ?>][collections_meta_description_template]"
												value="<?php echo $seogen[$language['language_id']]['collections_meta_description_template']; ?>"><br/>
												<?php echo $text_available_tags . ' <span class="bold">' . $text_collections_meta_description_tags . '</span>'; ?>
											</td>
											<td>
												<input id="seogen[<?php echo $language['language_id']; ?>][collections_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][collections_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['collections_meta_description_overwrite'])) echo "checked='checked'"; ?>>
												<label for="seogen[<?php echo $language['language_id']; ?>][collections_meta_description_overwrite]"></label>
											</td>
										</tr>
									</tr>
									<tr>
										<td><?php echo $text_use_expressions?></td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][collections_use_expressions]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][collections_use_expressions]" <?php if (isset($seogen[$language['language_id']]['collections_use_expressions'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][collections_use_expressions]"></label>
										</td>
									</tr>
								<?php } ?>
								<tr>
									<td><?php echo $text_collections_description ?></td>
									<td>
										<textarea id="collections_description_template<?php echo $language['language_id']; ?>" cols="125" rows="2" name="seogen[<?php echo $language['language_id']; ?>][collections_description_template]"><?php echo $seogen[$language['language_id']]['collections_description_template']; ?></textarea><br/>
										<?php echo $text_available_tags . ' <span class="bold">' . $text_collections_description_tags . '</span>'; ?>
									</td>
									<td>
										<input id="seogen[<?php echo $language['language_id']; ?>][collections_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][collections_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['collections_description_overwrite'])) echo "checked='checked'"; ?>>
										<label for="seogen[<?php echo $language['language_id']; ?>][collections_description_overwrite]"></label>
									</td>
								</tr>
								
								<tr>
									<td colspan="3"><a onclick="generate('collections', <?php echo $language['language_id']; ?>);" class="button"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $text_generate; ?>&nbsp;<?php echo $tab_collections; ?></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="tab-news<?php echo $language['language_id']; ?>">
						<table class="seogen-table" width="100%">
							<tbody id="informations<?php echo $language['language_id']; ?>" style="border: 1px solid #003A88;">
								
								<?php if(isset($seogen[$language['language_id']]['informations_h1_template'])) { ?>
									
									<tr>
										<td><?php echo $text_informations_h1 ?></td>
										<td>
											<input type="text" name="seogen[<?php echo $language['language_id']; ?>][informations_h1_template]" value="<?php echo $seogen[$language['language_id']]['informations_h1_template']; ?>"><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_informations_h1_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][informations_h1_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][informations_h1_overwrite]" <?php if (isset($seogen[$language['language_id']]['informations_h1_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][informations_h1_overwrite]"></label>
										</td>
									</tr>
								<?php } ?>
								<?php if(isset($seogen[$language['language_id']]['informations_title_template'])) { ?>
									<tr>
										<td><?php echo $text_informations_title ?></td>
										<td>
											<input type="text" name="seogen[<?php echo $language['language_id']; ?>][informations_title_template]"
											value="<?php echo $seogen[$language['language_id']]['informations_title_template']; ?>"><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_informations_title_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][informations_title_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][informations_title_overwrite]" <?php if (isset($seogen[$language['language_id']]['informations_title_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][informations_title_overwrite]"></label>
										</td>
									</tr>
								<?php } ?>
								<?php if(isset($seogen[$language['language_id']]['informations_meta_keyword_template'])) { ?>
									<tr>
										<td><?php echo $text_informations_meta_keyword ?></td>
										<td>
											<input type="text" name="seogen[<?php echo $language['language_id']; ?>][informations_meta_keyword_template]"
											value="<?php echo $seogen[$language['language_id']]['informations_meta_keyword_template']; ?>"><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_informations_meta_keyword_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][informations_meta_keyword_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][informations_meta_keyword_overwrite]" <?php if (isset($seogen[$language['language_id']]['informations_meta_keyword_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][informations_meta_keyword_overwrite]"></label>
										</td>
									</tr>
								<?php } ?>
								<?php if(isset($seogen[$language['language_id']]['informations_meta_description_template'])) { ?>
									<tr>
										<td><?php echo $text_informations_meta_description ?></td>
										<td>
											<input type="text" name="seogen[<?php echo $language['language_id']; ?>][informations_meta_description_template]"
											value="<?php echo $seogen[$language['language_id']]['informations_meta_description_template']; ?>"><br/>
											<?php echo $text_available_tags . ' <span class="bold">' . $text_informations_meta_description_tags . '</span>'; ?>
										</td>
										<td>
											<input id="seogen[<?php echo $language['language_id']; ?>][informations_meta_description_overwrite]" class="checkbox" type="checkbox" name="seogen[<?php echo $language['language_id']; ?>][informations_meta_description_overwrite]" <?php if (isset($seogen[$language['language_id']]['informations_meta_description_overwrite'])) echo "checked='checked'"; ?>>
											<label for="seogen[<?php echo $language['language_id']; ?>][informations_meta_description_overwrite]"></label>
										</td>
									</tr>
								<?php } ?>
								<tr>
									<td colspan="3"><a onclick="generate('informations', <?php echo $language['language_id']; ?>);" class="button"><img src="<?php echo DIR_FLAGS_NAME; ?><?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>">&nbsp;<?php echo $text_generate; ?>&nbsp;<?php echo $tab_news; ?></a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			<? } ?>
		</form>
		<a id="generate_url" style="display: none" href="<?php echo $generate?>"></a>
		<a id="generate_test_url" style="display: none" href="<?php echo $generate_test?>"></a>
		<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
		<script type="text/javascript"><!--
			<?php foreach ($languages as $language) { ?>
				<?php foreach (array('products_description_template', 'categories_description_template', 'manufacturers_description_template', 'collections_description_template') as $item) { ?>
					CKEDITOR.replace('<?php echo $item . $language['language_id'] ?>', {
						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
					});
				<? } ?>
			<? } ?>
		//--></script>
		<script type="text/javascript">
			function generate(selector, language_id) {
				$(".success").remove();
				//update CKEDITOR
				for(var instanceName in CKEDITOR.instances)
				CKEDITOR.instances[instanceName].updateElement();
				
				
				
				var data = $('#seourls :input').serialize() + '&' +
				$('#' + selector + language_id + ' :input').serialize()
				+ '&name=' + selector + '&language_id='+language_id;
				$.post($('#generate_url').attr('href'), data, function(html) {
					$(".breadcrumb").after('<div class="success">' + html + '</div>');
				});
			}
			
			function generate_test(selector, field, language_id){
				var data = $('#' + selector + language_id + ' :input').serialize()
				+ '&name=' + selector + '&language_id='+language_id + '&field=' + field;
				
				$.post($('#generate_test_url').attr('href'), data, function(html) {
					$("#" + selector + '_' + field + '_' + language_id).html(html).show();
				});
			}
			
			$('#seogen_overwrite').change(function(){
				$('.overwrite').prop('checked', $(this).prop('checked'))
			});
			
			$(document).ready(function() {
				$(".seogen-list tr:even").css("background-color", "#F4F4F8");
			});
			
			$('#tabs_languages a').tabs();
			<?php foreach ($languages as $language) { ?>
				$('#tabs<?php echo $language['language_id']; ?> a').tabs();
			<? } ?>
			
			
		</script>
	</div>
</div>
<?php echo $footer; ?>						