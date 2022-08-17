<div class="rows">
	
	
	<?php if (!empty($histories) || (!empty($populars))) { ?> 
		<div class="left_block left_block__history_block" style="flex-basis:100%">
			
			<?php if (!empty($histories)) { ?>
				<span class="title"><?php echo $text_search_history; ?><button class="clear_history_btn" onclick="removeHistory('all');">Очистить историю</button></span>
				
				<div class="evinent-search-group">
					<?php foreach ($histories as $history) { ?>
						<div class="history_item">
							<a href="<?php echo $history['href']; ?>"><i class="fas fa-history"></i> <?php echo $history['text']; ?><?php if ($history['date_added']) { ?> <span class="search_results_total">(<?php echo $history['date_added']; ?>)</span><?php } ?></a>
							<button class="clear_history_btn" onclick="removeHistory('<?php echo $history['id']?>');">Удалить</button>
						</div>						
					<?php } ?>
				</div>
			<?php } ?>
			
			<?php if (!empty($populars)) { ?>
				<span class="title"><?php echo $text_search_popular; ?></span>
				<div class="evinent-search-group">
					<?php foreach($populars as $popular) { ?>
						<a href="<?php echo $popular['href']; ?>"><i class="fas fa-search"></i> <?php echo $popular['text']; ?><?php if ($popular['results']) { ?> <span class="search_results_total">(<?php echo $popular['results']; ?>)</span><?php 
						} ?></a>
					<?php } ?>
				</div>
			<? } ?>
		</div>	
	<?php } ?>
	
	<?php if ($results_count) { ?>
		<?php if ($results['m'] || $results['c'] || $results['cc'] || $results['s']) { ?>
			<div class="left_block <?php if (!$results['p']) { ?>two_column<?php } ?>">
				<?php if ($results['s']) { ?>
					<div class="autocomplete_search">
						<?php foreach ($results['s'] as $suggestion) { ?>
							<div>
								<a data-id="<? echo $suggestion['id']; ?>" href="<?php echo $suggestion['href']; ?>">
									<i class="fas fa-search"></i> <?php echo $suggestion['name']; ?>
								</a>
							</div>
							
						<?php } ?>
					</div>
				<?php } ?>
				<!-- это блок с подсказками к поиску -->
				<?php if ($results['c'] || $results['mc']) { ?>
					<div class="item_history">					
						<span class="title"><?php echo $text_retranslate_search_r; ?></span>
						<div class="evinent-search-group">
							
							
							<?php foreach ($results['c'] as $category) { ?>
								
								<a data-id="<? echo $category['id']; ?>" href="<?php echo $category['href']; ?>">
								<i class="fas fa-bars"></i> <?php echo $category['name']; ?>
							</a>
							
						<?php } ?>
						
						<?php foreach ($results['mc'] as $mcategory) { ?>
							<a data-id="<? echo $mcategory['id']; ?>" href="<?php echo $mcategory['href']; ?>">
								<i class="fas fa-bars"></i><?php echo $mcategory['name']; ?>
							</a>
						<?php } ?>							
					</div>
				</div>
			<? } ?>	
			<?php if ($results['m']) { ?>
				<div class="item_history">
					<!-- это блок поиска по Бренду -->
					<span class="title"><?php echo $text_retranslate_search_brands; ?></span>
					<div class="evinent-search-group">
						<ul>
							<?php foreach ($results['m'] as $manufacturer) { ?>
								<li data-id="<? echo $manufacturer['id']; ?>">
									<a href="<?php echo $manufacturer['href']; ?>">
										<i class="fas fa-bars"></i><span class="name"><?php echo $manufacturer['name']; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				<? } ?>	
				
				<?php if ($results['cc']) { ?>
					<!-- это блок поиска по Коллекции -->
					<span class="title"><?php echo $text_retranslate_search_collections; ?></span>
					<div class="evinent-search-group">
						<ul>
							<?php foreach ($results['cc'] as $collection) { ?>
								<li data-id="<? echo $collection['id']; ?>">
									<a href="<?php echo $collection['href']; ?>">
										<i class="fas fa-bars"></i><span class="name"><?php echo $collection['name']; ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</div>
					<? } ?>	
				</div>
			<?php } ?>	
			<?php if ($results['p']) { ?>
				<div class="right_block">
					<!-- товары по поиску -->
					<span class="title"><?php echo $text_retranslate_search_products; ?></span>
					<div class="product_list">	
						<?php foreach ($results['p'] as $product) { ?>
							<div class="product_item">
								<a href="<?php echo $product['href']; ?>">
									<div class="img">
										<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>">
									</div>
									<span class="about_product">
										<span class="name"><?php echo $product['name']; ?></span>
										<span class="product_price">
											<?php if ($product['special']) { ?>
												<div class="special_wrap">
													<span class="new"><?php echo $product['special']; ?></span><span class="old"><?php echo $product['price']; ?></span><span class="saved">-<?php echo $product['saving']; ?>%</span>
												</div>
												
												<?php } else { ?>
												<span class="new"><?php echo $product['price']; ?></span>
											<?php } ?>									
										</span>
									</span>
								</a>
							</div>
						<? } ?>
					</div>
				</div>
			<?php } ?>
			<?php } else { ?>
			<?php if (empty($histories) && empty($populars)) { ?>
				<div><i class="fas fa-sad-tear"></i> <?php echo $text_retranslate_search_nothing_found; ?></div>
			<?php } ?>
		<? } ?>
	</div>																		