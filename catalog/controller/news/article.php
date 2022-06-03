<?php
class ControllerNewsArticle extends Controller {
	public function index() {
		$this->language->load('news/article');

		$this->load->model('catalog/news');

		$this->load->model('catalog/ncategory');	

		$this->document->addStyle('catalog/view/theme/default/stylesheet/blog-news.css');
		$this->document->addScript('catalog/view/theme/default/blog-mp/jquery.magnific-popup.min.js');
		$this->document->addStyle('catalog/view/theme/default/blog-mp/magnific-popup.css');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		if (isset($this->request->get['ncat'])) {
			$ncat = '';

			foreach (explode('_', $this->request->get['ncat']) as $ncat_id) {
				if (!$ncat) {
					$ncat = $ncat_id;
				} else {
					$ncat .= '_' . $ncat_id;
				}

				$ncategory_info = $this->model_catalog_ncategory->getncategory($ncat_id);

				if ($ncategory_info) {
					$this->data['breadcrumbs'][] = array(
						'text'      => $ncategory_info['name'],
						'href'      => $this->url->link('news/ncategory', 'ncat=' . $ncat),
						'separator' => $this->language->get('text_separator')
					);
				}
			}
		} else {
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('button_news'),
				'href'      => $this->url->link('news/ncategory'),
				'separator' => $this->language->get('text_separator')
			);

		}

		if (isset($this->request->get['news_id'])) {
			$news_id = (int)$this->request->get['news_id'];
		} else {
			$news_id = 0;
		}
		$this->document->addLink($this->url->link('news/article', 'news_id=' . $news_id), 'canonical');

		$news_info = $this->model_catalog_news->getNewsStory($news_id);

		if ($news_info) {

			$this->model_catalog_news->updateViewed($news_id);

			if ($news_info['ctitle']) {
				$this->document->setTitle($news_info['ctitle']); 
			} else {
				$this->document->setTitle($news_info['title']); 
			}
			$this->document->setDescription($news_info['meta_desc']);
			$this->document->setKeywords($news_info['meta_key']);				

			/*	$this->data['breadcrumbs'][] = array(
				'text'      => $news_info['title'],
				'href'      => $this->url->link('news/article', 'news_id=' . $news_id),      		
				'separator' => $this->language->get('text_separator')
				);
			*/
				
				$this->data['heading_title'] = $news_info['title'];
				$this->data['button_continue'] = $this->language->get('button_news');
				$this->data['text_sostav'] = $this->language->get('sostav');
				$this->data['text_count_of_portion'] = $this->language->get('count_of_portion');
				$this->data['text_cook_time'] = $this->language->get('cook_time');
				$this->data['text_table_mer_and_weight'] = $this->language->get('table_mer_and_weight');
				$this->data['text_save'] = $this->language->get('save');
				$this->data['text_print'] = $this->language->get('print');
				$this->data['text_send'] = $this->language->get('send');
				
				
				$this->data['continue'] = $this->url->link('news/ncategory');
				
				$this->data['description'] = $this->getChild('news/article/getPageContent', $news_info);
				
				if (!$this->config->get('bnews_tplpick')) {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/layout.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/news/layout.tpl';
					} else {
						$this->template = 'default/template/news/layout.tpl';
					}
				} else {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/information.tpl')) {
						$this->template = $this->config->get('config_template') . '/template/information/information.tpl';
					} else {
						$this->template = 'default/template/information/information.tpl';
					}
				}
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
				
				$this->response->setOutput($this->render());
			} else {
				$this->document->setTitle = $this->language->get('text_error');
				
				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_error'),
					'href'      => $this->url->link('news/article', 'news_id=' .  $news_id),      		
					'separator' => $this->language->get('text_separator')
				);	
				
				$this->data['heading_title'] = $this->language->get('text_error');
				
				$this->data['text_error'] = $this->language->get('text_error');
				
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['continue'] = $this->url->link('common/home');
				
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
				
				$this->response->setOutput($this->render());
			}
		}
		protected function getPageContent($news_info) {
			if(isset($this->request->get['route'])) {
				if(strpos(strtolower($this->request->get['route']), 'getpagecontent')) {
					$this->redirect($this->url->link('news/ncategory'));
				}
			} 
			$this->language->load('news/article');
			
			$this->load->model('catalog/news');
			
			$this->load->model('catalog/ncomments');
			
			$this->load->model('tool/image');
			
			$this->load->model('catalog/ncategory');	
			
			if ($this->request->get['news_id']) {
				$this->data['news_id'] = (int)$this->request->get['news_id'];
			} else {
				$this->data['news_id'] = 0;
			}
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_review'] = $this->language->get('entry_comment');
			$this->data['entry_captcha'] = $this->language->get('entry_captcha');
			$this->data['text_note'] = $this->language->get('text_note');
			$this->data['nocomment'] = $this->language->get('nocomment');
			$this->data['writec'] = $this->language->get('writec');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_send'] = $this->language->get('bsend');
			$this->data['title_comments'] = sprintf($this->model_catalog_ncomments->getTotalNcommentsByNewsId($this->data['news_id']));
			$this->data['text_coms'] = $this->language->get('title_comments');
			$this->data['text_posted_pon'] = $this->language->get('text_posted_pon');
			$this->data['text_posted_in'] = $this->language->get('text_posted_in');
			$this->data['text_updated_on'] = $this->language->get('text_updated_on');
			$this->data['text_tags'] = $this->language->get('text_tags');
			$this->data['text_posted_by'] = $this->language->get('text_posted_by');
			$this->data['text_posted_on'] = $this->language->get('text_posted_on');
			$this->data['text_comments'] = $this->language->get('text_comments');	
			$this->data['text_comments_v'] = $this->language->get('text_comments_v');
			$this->data['text_comments_to'] = $this->language->get('text_comments_to');
			$this->data['text_reply_to'] = $this->language->get('text_reply_to');
			$this->data['text_reply'] = $this->language->get('text_reply');
			$this->data['author_text'] = $this->language->get('author_text');			
			$this->data['button_more'] = $this->language->get('button_more');	
			$this->data['category'] = '';
			$cats = $this->model_catalog_news->getNcategoriesbyNewsId($this->data['news_id']);
			if ($cats) {
				$comma = 0;
				foreach($cats as $catid) {
					$catinfo = $this->model_catalog_ncategory->getncategory($catid['ncategory_id']);
					if ($catinfo) {
						if ($comma) {
							$this->data['category'] .= ', <a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
						} else {
							$this->data['category'] .= '<a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
						}
						$comma++;
					}
				}
			}
			/*if($this->config->get('bnews_display_elements')) {
				$pageelem = $this->config->get('bnews_display_elements');
				} else {
				$pageelem = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
				}
				if (!in_array("category", $pageelem)) {
				$this->data['category'] = '';
			}*/
			$this->data['gallery_type'] = isset($news_info['gal_slider_t']) ? $news_info['gal_slider_t'] : 1;
			if ($this->data['gallery_type'] != 1) {
				$this->document->addScript('catalog/view/theme/default/blog-mp/jssor.slider.mini.js');
			}
			$this->data['gallery_height'] = $news_info['gal_slider_h'];
			$this->data['gallery_width'] = $news_info['gal_slider_w'];
			$this->data['acom'] = $news_info['acom'];
			$this->data['heading_title'] = $news_info['title'];
			
			$this->data['recipe'] = unserialize($news_info['recipe']);
			
			$this->data['description'] = html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8');
			$this->data['description'] = str_replace("<video", "<iframe", $this->data['description']);
			$this->data['description'] = str_replace("</video>", "</iframe>", $this->data['description']);
			$this->data['custom1'] = html_entity_decode($news_info['cfield1'], ENT_QUOTES, 'UTF-8');
			$this->data['custom2'] = html_entity_decode($news_info['cfield2'], ENT_QUOTES, 'UTF-8');
			$this->data['custom3'] = html_entity_decode($news_info['cfield3'], ENT_QUOTES, 'UTF-8');
			$this->data['custom4'] = html_entity_decode($news_info['cfield4'], ENT_QUOTES, 'UTF-8');
			$this->data['date_added'] = date('d.m.Y', strtotime($news_info['date_added']));
			$this->data['date_updated'] = date('d M Y', strtotime($news_info['date_updated']));
			if ($this->data['date_added'] == $this->data['date_updated']) { $this->data['date_updated'] = ''; }
			if ($news_info['nauthor_id']) {
				$this->data['author_link'] = $this->url->link('news/ncategory', 'author=' . $news_info['nauthor_id']);
				$this->data['author'] = $news_info['author'];
				if ($this->data['author']) {
					if (method_exists($this->document , 'addExtraTag')) {
						$this->document->addExtraTag('noprop', $this->data['author'], 'author');
					}
				}
				$this->data['author_image'] = ($news_info['nimage']) ? $this->model_tool_image->resize($news_info['nimage'], 70, 70) : false;
				$authordesc = $this->model_catalog_news->getNauthorDescriptions($news_info['nauthor_id']);
				if (isset($authordesc[$this->config->get('config_language_id')])) {
					$this->data['author_desc'] = html_entity_decode($authordesc[$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
				} else { 
					$this->data['author_desc'] = ''; 
				}
			} else {
				$this->data['author'] = '';
			}
			$this->data['ntags'] = array();
			if ($news_info['ntags']) {		
				$ntags = explode(',', $news_info['ntags']);
				foreach ($ntags as $ntag) {
					$this->data['ntags'][] = array(
						'ntag' => trim($ntag),
						'href' => $this->url->link('news/search', 'article_tag=' . trim($ntag))
					);
				}
			}
			$this->data['button_news'] = $this->language->get('button_news');
			
			$this->data['button_cart'] = $this->language->get('button_cart');
			
			$this->data['news_prelated'] = sprintf($this->language->get('news_prelated'), $news_info['title']);
			
			$this->data['news_related'] = $this->language->get('news_related');
			
			$bwidth = ($this->config->get('bnews_thumb_width')) ? $this->config->get('bnews_thumb_width') : 230;
			$bheight = ($this->config->get('bnews_thumb_height')) ? $this->config->get('bnews_thumb_height') : 230;
			if ($news_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($news_info['image'], $bwidth, $bheight);
				$this->data['popup'] = $this->model_tool_image->resize($news_info['image'], 600, 600);
			} else {
				$this->data['thumb'] = '';
				$this->data['popup'] = '';
			}
			
			$this->data['article'] = array();
			
			$bbwidth = ($this->config->get('bnews_image_width')) ? $this->config->get('bnews_image_width') : 80;
			$bbheight = ($this->config->get('bnews_image_height')) ? $this->config->get('bnews_image_height') : 80;
			
			if($this->config->get('bnews_display_elements')) {
				$elements = $this->config->get('bnews_display_elements');
			} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
			}
			
			$this->data['page_url'] = $this->url->link('news/article', '&news_id=' . $this->data['news_id']);
			$this->data['disqus_sname'] = $this->config->get('bnews_disqus_sname');
			$this->data['disqus_id'] = 'article_'.$this->data['news_id'];
			$this->data['disqus_status'] = $this->config->get('bnews_disqus_status');
			$this->data['fbcom_status'] = $this->config->get('bnews_fbcom_status');
			$this->data['fbcom_appid'] = $this->config->get('bnews_fbcom_appid');
			$this->data['fbcom_theme'] = $this->config->get('bnews_fbcom_theme');
			$this->data['fbcom_posts'] = $this->config->get('bnews_fbcom_posts');
			
			if (method_exists($this->document , 'addExtraTag')) {
				if (!$this->config->get('bnews_facebook_tags')) {
					$this->document->addExtraTag('og:title', $this->data['heading_title']);
					if ($this->data['thumb']) {
						$this->document->addExtraTag('og:image', $this->data['thumb']);
					}
					$this->document->addExtraTag('og:url', $this->data['page_url']);
					$this->document->addExtraTag('og:type', 'product');
					$this->document->addExtraTag('og:description', trim(utf8_substr(strip_tags(html_entity_decode($this->data['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '...'));
				}
				if (!$this->config->get('bnews_twitter_tags')) {
					$this->document->addExtraTag('twitter:card', 'summary');
					$this->document->addExtraTag('twitter:url', $this->data['page_url']);
					$this->document->addExtraTag('twitter:title', $this->data['heading_title']);
					$this->document->addExtraTag('twitter:description', trim(utf8_substr(strip_tags(html_entity_decode($this->data['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '...'));
					if ($this->data['thumb']) {
						$this->document->addExtraTag('twitter:image', $this->data['thumb']);
					}
				}
			}
			
			$this->data['article_videos'] = array();	
			
			$vid_results = $this->model_catalog_news->getArticleVideos($this->data['news_id']);
			
			foreach ($vid_results as $result) {
				$result['text'] = unserialize($result['text']); 
				$result['text'] = isset($result['text'][$this->config->get('config_language_id')]) ? $result['text'][$this->config->get('config_language_id')] : '' ;
				$code = '<iframe frameborder="0" allowfullscreen src="' . str_replace("watch?v=","embed/",$result['video']) . '" height="'.$result['height'].'"width="100%" style="max-width:'.$result['width'].'px"></iframe>';
				
				$this->data['article_videos'][] = array(
					'text'  => $result['text'],
					'code' => $code
				);
			}
			
			$this->data['gallery_images'] = array();
			
			$gal_results = $this->model_catalog_news->getArticleGallery($this->data['news_id']);
			
			foreach ($gal_results as $result) {
				$result['text'] = unserialize($result['text']); 
				$result['text'] = isset($result['text'][$this->config->get('config_language_id')]) ? $result['text'][$this->config->get('config_language_id')] : '' ;
				$this->data['gallery_images'][] = array(
					'text'  => $result['text'],
					'popup' => $this->model_tool_image->resize($result['image'], $news_info['gal_popup_w'], $news_info['gal_popup_h']),
					'thumb' => $this->model_tool_image->resize($result['image'], $news_info['gal_thumb_w'], $news_info['gal_thumb_h'])
				);
			}
			$this->data['products'] = array();
			
			$results = $this->model_catalog_news->getProductRelated($this->data['news_id']);
			
			foreach ($results as $result) {		
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));

				} else {
					$image = $this->model_tool_image->resize($this->config->get('config_noimage'), $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));

				}
				
				$price = (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))) : false;	
				
				$special = ((float)$result['special']) ? $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))) : false;
				
				$rating = ($this->config->get('config_review_status')) ? (int)$result['rating'] : false;
				
				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,			
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}	

			if ($results = $this->model_catalog_news->getNewsRelated($this->data['news_id'])) {
				foreach ($results as $result) {
					if (!empty($result['title'])) {
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
								$desc = html_entity_decode($result['description2'], ENT_QUOTES, 'UTF-8');
							} else {
								$desc = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 600) . '..';
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
						if (in_array("category", $elements)) {
							$category = "";
							$cats = $this->model_catalog_news->getNcategoriesbyNewsId($result['news_id']);
							if ($cats) {
								$comma = 0;
								foreach($cats as $catid) {
									$catinfo = $this->model_catalog_ncategory->getncategory($catid['ncategory_id']);
									if ($catinfo) {
										if ($comma) {
											$category .= ', <a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
										} else {
											$category .= '<a href="'.$this->url->link('news/ncategory', 'ncat=' . $catinfo['ncategory_id']).'">'.$catinfo['name'].'</a>';
										}
										$comma++;
									}
								}
							}
						} else {
							$category = '';
						}

						$this->data['article'][] = array(
							'article_id'  => $result['news_id'],
							'name'        => $name,
							'thumb'       => $image,
						//	'thumb_mime'  => $image_mime,
						//	'thumb_webp'  => $image_webp,
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
							'href'        => $this->url->link('news/article', '&news_id=' . $result['news_id']),
							'total_comments' => $com,
							'viewed' => $result['viewed'],
						);
					}
				}		
			}
			
			$this->data['news'] = $this->url->link('news/headlines');
			if (isset($this->request->get['page'])) {
				$page = (int)$this->request->get['page'];
			} else {
				$page = 1;
			}
			
			$this->data['comment'] = array();
			
			$comment_total = $this->model_catalog_ncomments->getTotalJNcommentsByNewsId($this->data['news_id']);
			
			$results = $this->model_catalog_ncomments->getCommentsByNewsId($this->data['news_id'], ($page - 1) * 10, 10);

			foreach ($results as $result) {
				$replies = array();
				$allreplies = $this->model_catalog_ncomments->getCommentsByNewsId($this->data['news_id'], 0, 1000, $result['ncomment_id']);
				foreach ($allreplies as $reply) {
					$replies[] = array (
						'ncomment_id' => $reply['author'],
						'author'      => $reply['author'],
						'text'        => strip_tags($reply['text']),
						'date_added'  => date('d.m.Y', strtotime($reply['date_added']))
					);
				}
				$this->data['comment'][] = array(
					'ncomment_id' => $result['ncomment_id'],
					'author'      => $result['author'],
					'replies'     => $replies,
					'text'        => strip_tags($result['text']),
					'date_added'  => date('d.m.Y', strtotime($result['date_added']))
				);
			}
			$pagination = new Pagination();
			$pagination->total = $comment_total;
			$pagination->page = $page;
			$pagination->limit = 10; 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('news/article', 'news_id=' . $this->data['news_id'] . '&page={page}');
			$this->data['pagination'] = $pagination->render();
			
			
			if (!isset($_GET['zaeb'])){			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/article.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/news/article.tpl';
				} else {
					$this->template = 'default/template/news/article.tpl';
				}
			} else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/article_z.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/news/article_z.tpl';
				} else {
					$this->template = 'default/template/news/article_z.tpl';
				}
				
			}
			
			$this->response->setOutput($this->render());
			
		}
		public function writecomment() {
			$this->language->load('news/article');
			
			$this->load->model('catalog/ncomments');
			
			$json = array();
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				
				if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 25)) {
					$json['error'] = $this->language->get('error_name');
				}
				
				if ((strlen(utf8_decode($this->request->post['text'])) < 25) || (strlen(utf8_decode($this->request->post['text'])) > 1000)) {
					$json['error'] = $this->language->get('error_text');
				}
				
				if (!isset($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
					$json['error'] = $this->language->get('error_captcha');
				}
				
				if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($json['error'])) {
					$this->model_catalog_ncomments->addComment($this->request->get['news_id'], $this->request->post);
					
					$json['success'] = $this->language->get('text_success');
				}
			}
			$this->response->setOutput(json_encode($json));
		}
		
		public function captcha() {
			$this->load->library('captcha');
			
			$captcha = new Captcha();
			
			$this->session->data['captcha'] = $captcha->getCode();
			
			$captcha->showImage();
		}
		
	}
	?>
