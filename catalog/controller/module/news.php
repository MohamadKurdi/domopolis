<?php
	class ControllerModuleNews extends Controller {
		protected function index($setting) {
			$this->language->load('module/news');
			
			$this->load->model('catalog/news');
			
			$this->load->model('catalog/ncomments');
			
			$this->load->model('catalog/ncategory');
			
			$this->load->model('tool/image'); 
			
		//	$this->document->addStyle('catalog/view/theme/default/stylesheet/blog-news.css');
			
			$this->data['text_headlines'] = $this->language->get('text_headlines');
			$this->data['display_style'] = $this->config->get('bnews_display_style');
			$this->data['text_posted_by'] = $this->language->get('text_posted_by');
			$this->data['text_posted_on'] = $this->language->get('text_posted_on');
			$this->data['text_posted_pon'] = $this->language->get('text_posted_pon');
			$this->data['text_posted_in'] = $this->language->get('text_posted_in');
			$this->data['text_updated_on'] = $this->language->get('text_updated_on');
			$this->data['text_comments'] = $this->language->get('text_comments');	
			$this->data['text_comments_v'] = $this->language->get('text_comments_v');
			$this->data['display_style'] = $this->config->get('bnews_display_style');	
			$this->data['button_more'] = $this->language->get('button_more');	
			$this->data['disqus_sname'] = $this->config->get('bnews_disqus_sname');
			$this->data['disqus_status'] = $this->config->get('bnews_disqus_status');
			$this->data['fbcom_status'] = $this->config->get('bnews_fbcom_status');
			$this->data['fbcom_appid'] = $this->config->get('bnews_fbcom_appid');
			$this->data['fbcom_theme'] = $this->config->get('bnews_fbcom_theme');
			$this->data['fbcom_posts'] = $this->config->get('bnews_fbcom_posts');
			
			if ($setting['ncategory_id'] == 'all') {
				$this->data['heading_title'] = $this->language->get('heading_title');
				$this->data['newslink'] = $this->url->link('news/ncategory');
				} else {
				$ncategory_info = $this->model_catalog_ncategory->getncategory($setting['ncategory_id']);
				$this->data['heading_title'] = $ncategory_info['name'];
				$this->data['newslink'] = $this->url->link('news/ncategory', 'ncat=' . $setting['ncategory_id']);
			}
			
			$this->data['article'] = array(); 
			$this->data['articles'] = array(); 
			
			$manufacturer = false;
			if (isset($this->request->get['manufacturer_id']) && (int)$this->request->get['manufacturer_id'] > 0){
				$this->load->model('catalog/manufacturer');
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
			}
			
			if ($setting['ncategory_id'] == 'all') {	
				$data = array(
				'start'           => 0,
				'limit'           => $setting['news_limit'],
				'filter_tag'      => $manufacturer['name'],
				);
				$results = $this->model_catalog_news->getNews($data);	
				} else {	
				$data = array(
				'filter_ncategory_id' => $setting['ncategory_id'],
				'start'           => 0,
				'limit'           => $setting['news_limit']
				);
				
				$results = $this->model_catalog_news->getNews($data);	
			}
			
			$bbwidth = ($this->config->get('bnews_image_width')) ? $this->config->get('bnews_image_width') : 80;
			$bbheight = ($this->config->get('bnews_image_height')) ? $this->config->get('bnews_image_height') : 80;
			
			if($this->config->get('bnews_display_elements')) {
				$elements = $this->config->get('bnews_display_elements');
				} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
			}
			
			foreach ($results as $result) {
				$name = (in_array("name", $elements) && $result['title']) ? $result['title'] : '';
				$da = (in_array("da", $elements)) ? date('d.m.Y', strtotime($result['date_added'])) : '';
				$du = (in_array("du", $elements) && $result['date_updated'] && $result['date_updated'] != $result['date_added']) ? date('d M Y', strtotime($result['date_updated'])) : '';
				$button = (in_array("button", $elements)) ? true : false;
				$custom1 = (in_array("custom1", $elements) && $result['cfield1']) ? html_entity_decode($result['cfield1'], ENT_QUOTES, 'UTF-8') : '';
				$custom2 = (in_array("custom2", $elements) && $result['cfield2']) ? html_entity_decode($result['cfield2'], ENT_QUOTES, 'UTF-8') : '';
				$custom3 = (in_array("custom3", $elements) && $result['cfield3']) ? html_entity_decode($result['cfield3'], ENT_QUOTES, 'UTF-8') : '';
				$custom4 = (in_array("custom4", $elements) && $result['cfield4']) ? html_entity_decode($result['cfield4'], ENT_QUOTES, 'UTF-8') : '';
				if (in_array("image", $elements) && ($result['image'] || $result['image2'])) {
					if ($result['image2']) {
						
						$image = $this->model_tool_image->resize($result['image2'], $bbwidth, $bbheight);
					
						
						} else {
						$image = $this->model_tool_image->resize($result['image'], $bbwidth, $bbheight);
					
					}
					} else {
					$image = false;
				
				}
				if (in_array("author", $elements) && $result['author']) {
					$author = $result['author'];
					$author_id = $result['nauthor_id'];
					$author_link = $this->url->link('news/ncategory', 'author=' . $result['nauthor_id']);
					} else {
					$author = '';
					$author_id = '';
					$author_link = '';
				}
				if (in_array("desc", $elements) && ($result['description'] || $result['description2'])) {
					if($result['description2']) {
						if ($setting['position'] == 'column_left' || $setting['position'] == 'column_right') {
							$desc = utf8_substr(strip_tags(html_entity_decode($result['description2'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..';
							} else {
							$desc = html_entity_decode($result['description2'], ENT_QUOTES, 'UTF-8');
						}
						} else {
						if ($setting['position'] == 'column_left' || $setting['position'] == 'column_right') {
							$desc = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..';
							} else {
							$desc = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 600) . '..';
						}
					}
					} else {
					$desc = '';
				}
				if (in_array("com", $elements) && $result['acom']) {
					$com = $this->model_catalog_ncomments->getTotalNcommentsByNewsId($result['news_id']);
					if (!$com) {
						$com = " 0 ";
					}
					} else {
					$com = '';
				}
				$path = '0';
				if (in_array("category", $elements)) {
					$category = "";
					$cats = $this->model_catalog_news->getNcategoriesbyNewsId($result['news_id']);
					if ($cats) {
						$comma = 0;
						foreach($cats as $catid) {
							$catinfo = $this->model_catalog_ncategory->getncategory($catid['ncategory_id']);
							$path = $this->model_catalog_ncategory->getncategorypath($catinfo['ncategory_id']);
							
							if ($catinfo) {
								if ($comma) {
									$category .= ', <a href="'.$this->url->link('news/ncategory', 'ncat=' . $path).'">'.$catinfo['name'].'</a>';						
									} else {
									$category .= '<a href="'.$this->url->link('news/ncategory', 'ncat=' . $path).'">'.$catinfo['name'].'</a>';																	
								}
								$comma++;
							}
						}
					}
					} else {
					$category = '';
					$path = '0';
				}				
				
				$href = $this->url->link('news/article', 'ncat='.$path.'&news_id=' . $result['news_id']);
				
				$this->data['article'][] = array(
				'article_id'  => $result['news_id'],
				'name'        => $name,
				'thumb'       => $image,			
				'date_added'  => $da,
				'du'          => $du,
				'author'      => $author,
				'author_id'   => $author_id,
				'author_link' => $author_link,
				'description' => $desc,
				'button'      => $button,
				'custom1'     => $custom1,
				'custom2'     => $custom2,
				'custom3'     => $custom3,
				'custom4'     => $custom4,
				'category'    => $category,
				'href'        => $href,
				'total_comments' => $com,
				'viewed' => $result['viewed'],
				);
			}
			$this->data['articles'] = $this->data['article'];
			
			if ($setting['position'] == 'column_left' || $setting['position'] == 'column_right') {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news_side.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/news_side.tpl';
					} else {
					$this->template = 'default/template/module/news_side.tpl';
				}
				} else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/news.tpl';
					} else {
					$this->template = 'default/template/module/news.tpl';
				}
			}
			
			$this->render(); 
			
		}
	}