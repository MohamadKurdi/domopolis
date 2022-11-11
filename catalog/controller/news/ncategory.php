<?php 
	class ControllerNewsNcategory extends Controller {  
		public function index() { 
			
			$this->language->load('news/ncategory');
			
			$this->load->model('catalog/ncategory');
			
			$this->load->model('catalog/news');
			
			$this->document->addStyle('catalog/view/theme/default/stylesheet/blog-news.css');
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
			);
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . (int)$this->request->get['page'];
			}	
			
			if (isset($this->request->get['ncat'])) {
				$ncat = '';
				
				$parts = explode('_', (string)$this->request->get['ncat']);
				
				foreach ($parts as $ncat_id) {
					if (!$ncat) {
						$ncat = $ncat_id;
						} else {
						$ncat .= '_' . $ncat_id;
					}
					
					$ncategory_info = $this->model_catalog_ncategory->getncategory($ncat_id);
					
					if ($ncategory_info) {
					/*	$this->data['breadcrumbs'][] = array(
   	    				'text'      => $ncategory_info['name'],
						'href'      => $this->url->link('news/ncategory', 'ncat=' . $ncat),
        				'separator' => $this->language->get('text_separator')
						);
					*/
					}
				}		
				$ncategory_id = array_pop($parts);
				$this->document->addLink($this->url->link('news/ncategory', 'ncat=' . $ncategory_id), 'canonical');
				} else {
				$ncategory_id = 0;
			}
			
			if (isset($this->request->get['author'])) {
				$author_id = (int)$this->request->get['author'];
				} else {
				$author_id = 0;
			}
			
			$author_info = $this->model_catalog_news->getNauthor($author_id);
			if ($author_info) {
				$this->data['breadcrumbs'][] = array(
   	    		'text'      => $author_info['name'],
				'href'      => $this->url->link('news/ncategory', 'author=' . $author_id),
        		'separator' => $this->language->get('text_separator')
				);
				$this->document->setTitle($author_info['name']);
				
				$this->data['heading_title'] = $author_info['name'];
				
				$authordesc = $this->model_catalog_news->getNauthorDescriptions($author_info['nauthor_id']);
				
				if (isset($authordesc[$this->config->get('config_language_id')])) {
					$this->document->setDescription($authordesc[$this->config->get('config_language_id')]['meta_description']);
					$this->document->setKeywords($authordesc[$this->config->get('config_language_id')]['meta_keyword']);
					if ($authordesc[$this->config->get('config_language_id')]['ctitle']) {
						$this->document->setTitle($authordesc[$this->config->get('config_language_id')]['ctitle']);
					}
				}
				
			}
			
			
			$ncategory_info = $this->model_catalog_ncategory->getncategory($ncategory_id);
			
			if ($ncategory_info) {
				$settings = $ncategory_info;
				} elseif ($author_info) {
				$settings = array('author' => $author_info, 'author_info' => $authordesc);
				} else {
				$settings = array();
			}
			
			if (!$ncategory_info && !$author_info) {
			
			$this->data['breadcrumbs'][] = array(
   	    		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('news/ncategory', $url),
        		'separator' => $this->language->get('text_separator')
				);
			
			
			$this->data['heading_title'] = $this->language->get('heading_title');
				$this->document->setTitle($this->language->get('heading_title'));
			}
			
			$all_tags = $this->model_catalog_news->getTags();
			
			$this->data['_ntags'] = array();
			foreach ($all_tags as $_tag => $_num){
				
				if ($_num > 1){
					$this->data['_ntags'][] = array(
						'ntag' => $_tag,
						'href'  => $this->url->link('news/search', 'article_tag='.trim($_tag))
					);
				}

			}
			
			if ((!isset($this->request->get['ncat']) && !isset($this->request->get['author'])) || (isset($this->request->get['ncat']) && $ncategory_info) || (isset($this->request->get['author']) && $author_info)) {
				if ($ncategory_info) {
					$this->document->setTitle($ncategory_info['name']);
					$this->document->setDescription($ncategory_info['meta_description']);
					$this->document->setKeywords($ncategory_info['meta_keyword']);
					$this->data['heading_title'] = $ncategory_info['name'];
				} 
				
				$this->data['button_continue'] = $this->language->get('go_to_headlines');
				$this->data['continue'] = $this->url->link('news/ncategory');
				
				$this->data['description'] = $this->getChild('news/ncategory/getPageContent', $settings);
				
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
				$url = '';
				
				if (isset($this->request->get['ncat'])) {
					$url .= '&ncat=' . $this->request->get['ncat'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('news/ncategory', $url),
				'separator' => $this->language->get('text_separator')
				);
				
				$this->document->setTitle($this->language->get('text_error'));
				
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
		protected function getPageContent($settings) {	
			
			if(isset($this->request->get['route'])) {
				if(strpos(strtolower($this->request->get['route']), 'getpagecontent')) {
					$this->redirect($this->url->link('news/ncategory'));
				}
			} 
			
			$this->language->load('news/ncategory');
			
			$this->load->model('catalog/ncategory');
			
			$this->load->model('catalog/news');
			
			$this->load->model('tool/image'); 
			
			$this->load->model('catalog/ncomments');
			
			$this->data['text_empty'] = $this->language->get('text_empty');			
			$this->data['button_more'] = $this->language->get('button_more');
			$this->data['text_refine'] = $this->language->get('text_refine');
			
			$this->data['text_posted_by'] = $this->language->get('text_posted_by');
			$this->data['text_posted_on'] = $this->language->get('text_posted_on');
			$this->data['text_posted_pon'] = $this->language->get('text_posted_pon');
			$this->data['text_posted_in'] = $this->language->get('text_posted_in');
			$this->data['text_updated_on'] = $this->language->get('text_updated_on');
			$this->data['text_comments'] = $this->language->get('text_comments');	
			$this->data['text_comments_v'] = $this->language->get('text_comments_v');	
			$this->data['continue'] = $this->url->link('common/home');
			$this->data['is_category'] = false;
			$this->data['is_author'] = false;
			$this->data['disqus_sname'] = $this->config->get('bnews_disqus_sname');
			$this->data['disqus_status'] = $this->config->get('bnews_disqus_status');
			$this->data['fbcom_status'] = $this->config->get('bnews_fbcom_status');
			$this->data['fbcom_appid'] = $this->config->get('bnews_fbcom_appid');
			$this->data['fbcom_theme'] = $this->config->get('bnews_fbcom_theme');
			$this->data['fbcom_posts'] = $this->config->get('bnews_fbcom_posts');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else { 
				$page = 1;
			}	
			
			$limit = $this->config->get('config_catalog_limit');
			
			if (isset($this->request->get['ncat'])) {
				$parts = explode('_', (string)$this->request->get['ncat']);	
				$ncategory_id = array_pop($parts);
				$ncategory_info = $settings;
				
				if ($ncategory_info) {
					$this->data['is_category'] = true;
					$limit = $ncategory_info['column'];
					$display_image = $ncategory_info['top'];
					
					if ($ncategory_info['image']) {
						$this->data['thumb'] = $this->model_tool_image->resize($ncategory_info['image'], 100, 100);
						} else {
						$this->data['thumb'] = '';
					}
					$this->data['heading_title'] = $ncategory_info['name'];						
					$this->data['description'] = html_entity_decode($ncategory_info['description'], ENT_QUOTES, 'UTF-8');
					
					$this->data['ncategories'] = array();
					
					$results = $this->model_catalog_ncategory->getncategories($ncategory_id);
					
					foreach ($results as $result) {
						$this->data['ncategories'][] = array(
						'name'  => $result['name'],
						'href'  => $this->url->link('news/ncategory', 'ncat=' . $this->request->get['ncat'] . '_' . $result['ncategory_id'])
						);
					}
				}
				} else {
				$ncategory_id = 0;
				$ncategory_info = '';
			}
			
			
			
			if (isset($this->request->get['author'])) {
				$author_id = (int)$this->request->get['author'];
				$author_info = $settings['author'];
				if ($author_info) {
					$this->data['is_author'] = true;
					$this->data['author'] = $author_info['name'];
					$this->data['author_image'] = ($author_info['image']) ? $this->model_tool_image->resize($author_info['image'], 80, 80) : false;
					$authordesc = $settings['author_info'];
					if (isset($authordesc[$this->config->get('config_language_id')])) {
						$this->data['author_desc'] = html_entity_decode($authordesc[$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
						} else { 
						$this->data['author_desc'] = ''; 
					}
				}
				} else {
				$author_id = 0;
				$author_info = '';
			}
			
			$all_tags = $this->model_catalog_news->getTags();
			
			$this->data['_ntags'] = array();
			foreach ($all_tags as $_tag => $_num){
				
				if ($_num > 1){
					$this->data['_ntags'][] = array(
						'ntag' => $_tag,
						'href'  => $this->url->link('news/search', 'article_tag='.trim($_tag))
					);
				}

			}
			
			
			$this->data['article'] = array();
			
			if ($ncategory_info) {
				$data = array(
				'filter_ncategory_id' => $ncategory_id,
				'start'           => ($page - 1) * $limit,
				'limit'           => $limit 
				);
				$this->data['display_style'] = $ncategory_info['top'];
				} elseif ($author_id) {
				$data = array(
				'filter_author_id' => $author_id,
				'start'           => ($page - 1) * $limit,
				'limit'           => $limit 
				);
				$this->data['display_style'] = $this->config->get('bnews_display_style');
				} else {
				$data = array(
				'start'           => ($page - 1) * $limit,
				'limit'           => $limit 
				);
				$this->data['display_style'] = $this->config->get('bnews_display_style');
			}
			$bbwidth = ($this->config->get('bnews_image_width')) ? $this->config->get('bnews_image_width') : 80;
			$bbheight = ($this->config->get('bnews_image_height')) ? $this->config->get('bnews_image_height') : 80;
			
			if($this->config->get('bnews_display_elements')) {
				$elements = $this->config->get('bnews_display_elements');
				} else {
				$elements = array("name","image","da","du","author","category","desc","button","com","custom1","custom2","custom3","custom4");
			}
			$news_total = $this->model_catalog_news->getTotalNews($data);
			$results = $this->model_catalog_news->getNews($data);
			
			foreach ($results as $result) {
				$name = (in_array("name", $elements) && $result['title']) ? $result['title'] : '';
				$da = (in_array("da", $elements)) ? date('d.m.Y', strtotime($result['date_added'])) : '';
				$du = (in_array("du", $elements) && $result['date_updated'] && $result['date_updated'] != $result['date_added']) ? date('d.m.Y', strtotime($result['date_updated'])) : '';
				$button = (in_array("button", $elements)) ? true : false;
				$custom1 = (in_array("custom1", $elements) && $result['cfield1']) ? html_entity_decode($result['cfield1'], ENT_QUOTES, 'UTF-8') : '';
				$custom2 = (in_array("custom2", $elements) && $result['cfield2']) ? html_entity_decode($result['cfield2'], ENT_QUOTES, 'UTF-8') : '';
				$custom3 = (in_array("custom3", $elements) && $result['cfield3']) ? html_entity_decode($result['cfield3'], ENT_QUOTES, 'UTF-8') : '';
				$custom4 = (in_array("custom4", $elements) && $result['cfield4']) ? html_entity_decode($result['cfield4'], ENT_QUOTES, 'UTF-8') : '';
				if (in_array("image", $elements) && ($result['image'] || $result['image2'])) {
					if ($result['image2']) {
						$image = 'image/'.$result['image2'];
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
				$href = ($ncategory_info) ? $this->url->link('news/article','ncat=' . $this->request->get['ncat'] . '&news_id=' . $result['news_id']) : $this->url->link('news/article','news_id=' . $result['news_id']);
				$canhref =  $this->url->link('news/article','news_id=' . $result['news_id']);
				
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
				'canhref'     => $canhref,
				'total_comments' => $com,
				'viewed' => $result['viewed'],
				);
			}
			
			$url = '';
			
			$pagination = new Pagination();
			$pagination->total = $news_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			if (isset($this->request->get['ncat'])) {
				$pagination->url = $this->url->link('news/ncategory', 'ncat=' . $this->request->get['ncat'] . $url . '&page={page}');
				} else {
				$pagination->url = $this->url->link('news/ncategory', $url . '&page={page}');
			}
			
			$this->data['pagination'] = $pagination->render();
			
			
			if (!isset($_GET['test'])){		
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/ncategory.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/news/ncategory.tpl';
					} else {
					$this->template = 'default/template/news/ncategory.tpl';
				}	
				$this->response->setOutput($this->render());	
			}
			else {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/ncategory_z.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/news/ncategory_z.tpl';
					} else {
					$this->template = 'default/template/news/ncategory_z.tpl';
				}	
				$this->response->setOutput($this->render());	
			}
		}
	}
?>