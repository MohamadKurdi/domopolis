<?php
class ControllerModuleMattimeoTheme extends Controller {
	private $error = array(); 
	private $_name = 'mattimeotheme';
	public function index() {   
		
		$this->language->load('module/mattimeotheme');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		$this->document->addStyle('view/stylesheet/jquery.colorpicker.css');
		$this->document->addScript('view/javascript/jquery/jquery.colorpicker.js');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		 		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	
		$this->data['text_apply'] = $this->language->get('text_apply');
		$this->data['text_reset'] = $this->language->get('text_reset');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['text_responsive'] = $this->language->get('text_responsive');
				$this->data['text_menu_full'] = $this->language->get('text_menu_full');
		$this->data['text_site_position'] = $this->language->get('text_site_position');
		$this->data['text_featured'] = $this->language->get('text_featured');
		$this->data['text_latest'] = $this->language->get('text_latest');
		$this->data['text_bestseller'] = $this->language->get('text_bestseller');
		$this->data['text_specials'] = $this->language->get('text_specials');
		$this->data['text_related'] = $this->language->get('text_related');
		$this->data['text_generalsett'] = $this->language->get('text_generalsett');
		$this->data['text_m_home'] = $this->language->get('text_m_home');
		$this->data['text_m_menucolumn'] = $this->language->get('text_m_menucolumn');
		$this->data['text_m_info'] = $this->language->get('text_m_info');
		$this->data['text_m_brand'] = $this->language->get('text_m_brand');
		$this->data['text_m_account'] = $this->language->get('text_m_account');
		$this->data['text_m_spec'] = $this->language->get('text_m_spec');
		$this->data['text_m_wishlist'] = $this->language->get('text_m_wishlist');
		$this->data['text_m_cart'] = $this->language->get('text_m_cart');
		$this->data['text_m_checkout'] = $this->language->get('text_m_checkout');
		$this->data['text_m_compare'] = $this->language->get('text_m_compare');
		$this->data['text_m_welcome'] = $this->language->get('text_m_welcome');
		$this->data['text_additional1'] = $this->language->get('text_additional1');
		$this->data['text_additional2'] = $this->language->get('text_additional2');
		$this->data['text_header'] = $this->language->get('text_header');
		$this->data['text_display_column'] = $this->language->get('text_display_column');
		$this->data['text_display_content'] = $this->language->get('text_display_content');
		$this->data['text_fixmenu'] = $this->language->get('text_fixmenu');
		$this->data['text_topcontrol'] = $this->language->get('text_topcontrol');
		$this->data['text_colorsite'] = $this->language->get('text_colorsite');
		$this->data['text_detail_view'] = $this->language->get('text_detail_view');
		
		$this->data['text_slider'] = $this->language->get('text_slider');
		$this->data['text_slider_effect'] = $this->language->get('text_slider_effect');
		$this->data['text_slider_speed'] = $this->language->get('text_slider_speed');
		$this->data['text_slider_pause'] = $this->language->get('text_slider_pause');
		$this->data['text_slider_start'] = $this->language->get('text_slider_start');
		$this->data['text_slider_arrows'] = $this->language->get('text_slider_arrows');
		$this->data['text_slider_navigation'] = $this->language->get('text_slider_navigation');
		$this->data['text_slider_hover'] = $this->language->get('text_slider_hover');
		
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_product_tab'] = $this->language->get('text_product_tab');
		$this->data['text_product_block'] = $this->language->get('text_product_block');
		$this->data['text_product_title'] = $this->language->get('text_product_title');
		$this->data['text_product_tabtext'] = $this->language->get('text_product_tabtext');
		$this->data['text_product_zoom'] = $this->language->get('text_product_zoom');
		$this->data['text_wishlist'] = $this->language->get('text_wishlist');
		$this->data['text_compare'] = $this->language->get('text_compare');
		$this->data['text_news'] = $this->language->get('text_news');
		$this->data['text_quickview'] = $this->language->get('text_quickview');
		
		$this->data['footer_heading'] = $this->language->get('footer_heading');
		$this->data['footer_title'] = $this->language->get('footer_title');
		$this->data['footer_descr'] = $this->language->get('footer_descr');
		$this->data['footer_theme'] = $this->language->get('footer_theme');
		$this->data['footer_twitterlink'] = $this->language->get('footer_twitterlink');
		$this->data['footer_twitternumb'] = $this->language->get('footer_twitternumb');
		$this->data['footer_help'] = $this->language->get('footer_help');
		$this->data['custom_comptext_text'] = $this->language->get('custom_comptext_text');
		$this->data['footer_twitter'] = $this->language->get('footer_twitter');
		$this->data['footer_facebook'] = $this->language->get('footer_facebook');
		$this->data['footer_vk'] = $this->language->get('footer_vk');
		
		$this->data['footer_icons'] = $this->language->get('footer_icons');
		$this->data['footer_payment'] = $this->language->get('footer_payment');
		$this->data['footer_add_icon'] = $this->language->get('footer_add_icon');
		
		$this->data['text_contact_heading'] = $this->language->get('text_contact_heading');
		$this->data['text_contact_header'] = $this->language->get('text_contact_header');
		$this->data['text_contact_footer'] = $this->language->get('text_contact_footer');
		$this->data['text_contact_phone'] = $this->language->get('text_contact_phone');
		$this->data['text_contact_fax'] = $this->language->get('text_contact_fax');
		$this->data['text_contact_email'] = $this->language->get('text_contact_email');
		$this->data['text_contact_skype'] = $this->language->get('text_contact_skype');
		$this->data['text_contact_address'] = $this->language->get('text_contact_address');
		$this->data['text_contact_copyright'] = $this->language->get('text_contact_copyright');
		
		$this->data['link_contact'] = $this->language->get('link_contact');
		$this->data['link_brand'] = $this->language->get('link_brand');
		$this->data['link_gift'] = $this->language->get('link_gift');
		$this->data['link_affiliates'] = $this->language->get('link_affiliates');
		$this->data['link_specials'] = $this->language->get('link_specials');
		$this->data['link_returns'] = $this->language->get('link_returns');
		$this->data['link_sitemap'] = $this->language->get('link_sitemap');
		$this->data['text_socicon'] = $this->language->get('text_socicon');
		$this->data['text_extras'] = $this->language->get('text_extras');
		
		$this->data['text_top_menu'] = $this->language->get('text_top_menu');
		$this->data['topmenu_help'] = $this->language->get('topmenu_help');
		$this->data['text_top_cusomlink'] = $this->language->get('text_top_cusomlink');
		$this->data['text_top_url'] = $this->language->get('text_top_url');
		$this->data['text_top_alllink'] = $this->language->get('text_top_alllink');
		$this->data['text_top_html'] = $this->language->get('text_top_html');
		$this->data['text_addlink'] = $this->language->get('text_addlink');
		$this->data['text_menu_topmenu'] = $this->language->get('text_menu_topmenu');
		$this->data['text_menu_generalmenu'] = $this->language->get('text_menu_generalmenu');
		
		$this->data['text_fonts'] = $this->language->get('text_fonts');
		$this->data['text_fonts_weight'] = $this->language->get('text_fonts_weight');
		$this->data['text_fonts_size'] = $this->language->get('text_fonts_size');
		$this->data['text_fonts_transf'] = $this->language->get('text_fonts_transf');
		$this->data['text_body_fonts'] = $this->language->get('text_body_fonts');
		$this->data['text_title_fonts'] = $this->language->get('text_title_fonts');
		$this->data['text_mattitle_fonts'] = $this->language->get('text_mattitle_fonts');
		$this->data['text_module_fonts'] = $this->language->get('text_module_fonts');
		$this->data['text_customf_fonts'] = $this->language->get('text_customf_fonts');
		$this->data['text_other_fonts'] = $this->language->get('text_other_fonts');
		$this->data['text_menu_fonts'] = $this->language->get('text_menu_fonts');
		$this->data['text_categ_fonts'] = $this->language->get('text_categ_fonts');
		$this->data['text_price_fonts'] = $this->language->get('text_price_fonts');
		$this->data['text_name_fonts'] = $this->language->get('text_name_fonts');
		$this->data['text_button_fonts'] = $this->language->get('text_button_fonts');
		$this->data['mattimeo_categ_text'] = $this->language->get('mattimeo_categ_text');
		
		$this->data['color_text'] = $this->language->get('color_text');
		$this->data['entry_colors_help'] = $this->language->get('entry_colors_help');
		$this->data['foncolor_text'] = $this->language->get('foncolor_text');
		$this->data['fonimage_text'] = $this->language->get('fonimage_text');
		$this->data['foncolor_header_text'] = $this->language->get('foncolor_header_text');
		$this->data['fonimage_header_text'] = $this->language->get('fonimage_header_text');
		$this->data['pagecolor_text'] = $this->language->get('pagecolor_text');
		$this->data['tab_main_text'] = $this->language->get('tab_main_text');
		$this->data['tab_menu_text'] = $this->language->get('tab_menu_text');
		$this->data['tab_header_text'] = $this->language->get('tab_header_text');
		$this->data['tab_buttons_text'] = $this->language->get('tab_buttons_text');
		$this->data['tab_arrows_text'] = $this->language->get('tab_arrows_text');
		$this->data['tab_category_text'] = $this->language->get('tab_category_text');
		$this->data['tab_products_text'] = $this->language->get('tab_products_text');
		$this->data['tab_footer_text'] = $this->language->get('tab_footer_text');
		$this->data['main_text_btext'] = $this->language->get('main_text_btext');
		$this->data['main_smalltext_btext'] = $this->language->get('main_smalltext_btext');
		$this->data['main_text_link'] = $this->language->get('main_text_link');
		$this->data['main_bread_link'] = $this->language->get('main_bread_link');
		$this->data['main_text_linkhover'] = $this->language->get('main_text_linkhover');
		$this->data['main_captable_text'] = $this->language->get('main_captable_text');
		$this->data['main_checkout_text'] = $this->language->get('main_checkout_text');
		
		$this->data['top_header_text'] = $this->language->get('top_header_text');
		$this->data['top_link_text'] = $this->language->get('top_link_text');
		$this->data['top_cart_text'] = $this->language->get('top_cart_text');
		$this->data['top_text_text'] = $this->language->get('top_text_text');
		$this->data['top_currency_text'] = $this->language->get('top_currency_text');
		
		$this->data['menulink_color_text'] = $this->language->get('menulink_color_text');
		$this->data['menu_bg_text'] = $this->language->get('menu_bg_text');
		$this->data['menulink2_color_text'] = $this->language->get('menulink2_color_text');
		$this->data['menu2_bg_text'] = $this->language->get('menu2_bg_text');
		
		$this->data['module_cat_text'] = $this->language->get('module_cat_text');
		$this->data['module_subcat_text'] = $this->language->get('module_subcat_text');
		$this->data['module_activcat_text'] = $this->language->get('module_activcat_text');
		$this->data['module_activsubcat_text'] = $this->language->get('module_activsubcat_text');
		
		$this->data['product_name_text'] = $this->language->get('product_name_text');
		$this->data['product_price_text'] = $this->language->get('product_price_text');
		$this->data['product_oldprice_text'] = $this->language->get('product_oldprice_text');
		$this->data['product_link_text'] = $this->language->get('product_link_text');
		$this->data['product_bg_text'] = $this->language->get('product_bg_text');
		$this->data['product_tabs_text'] = $this->language->get('product_tabs_text');
		
		$this->data['footer_bg_text'] = $this->language->get('footer_bg_text');
		$this->data['footer_heading_text'] = $this->language->get('footer_heading_text');
		$this->data['footer_text_text'] = $this->language->get('footer_text_text');
		$this->data['footer_link_text'] = $this->language->get('footer_link_text');
		$this->data['footer_fon_text'] = $this->language->get('footer_fon_text');
		
		$this->data['news_button_text'] = $this->language->get('news_button_text');
		$this->data['news_heading_text'] = $this->language->get('news_heading_text');
		$this->data['banner_heading_text'] = $this->language->get('banner_heading_text');
		$this->data['banner_slider_text'] = $this->language->get('banner_slider_text');
		
		$this->data['other_elements_text'] = $this->language->get('other_elements_text');
		$this->data['other_category_text'] = $this->language->get('other_category_text');
		$this->data['other_heading_text'] = $this->language->get('other_heading_text');
		$this->data['other_banners_text'] = $this->language->get('other_banners_text');
		$this->data['other_bannertext_text'] = $this->language->get('other_bannertext_text');
		$this->data['other_show_text'] = $this->language->get('other_show_text');
		$this->data['other_news_text'] = $this->language->get('other_news_text');
		$this->data['news_data_text'] = $this->language->get('news_data_text');
		$this->data['pagination_text'] = $this->language->get('pagination_text');
		$this->data['filterprice_text'] = $this->language->get('filterprice_text');
		
		$this->data['text_parallax_full'] = $this->language->get('text_parallax_full');
		$this->data['text_parallax_height'] = $this->language->get('text_parallax_height');
		$this->data['text_parallax_image'] = $this->language->get('text_parallax_image');
		$this->data['text_parallax_text'] = $this->language->get('text_parallax_text');
		$this->data['text_parallax_width'] = $this->language->get('text_parallax_width');
		$this->data['text_parallax_limit'] = $this->language->get('text_parallax_limit');
		$this->data['text_parallax_limit2'] = $this->language->get('text_parallax_limit2');

				
		
		$this->data['tab_module'] = $this->language->get('tab_module');
		
		$this->data['token'] = $this->session->data['token'];
		
		
		if (isset($this->request->get['store_id'])) {
            $this->data['store_id'] = $this->request->get['store_id'];
        } else {
            $this->data['store_id'] = 0;
        }
		
		if (isset($this->request->post['store_id'])){
			$store_id = $this->request->post['store_id'];
			unset($this->request->post['store_id']);
		} else {
			$store_id = 0;			
		}
		
		$this->load->model('setting/store');

        $this->data['stores'] = $this->model_setting_store->getStores();
        $this->data['stores'][] = array('store_id' => 0, 'name' => 'По умолчанию');       
		$this->data['action_without_store'] = $this->url->link('module/mattimeotheme', 'token=' . $this->session->data['token'], 'SSL');
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mattimeotheme', $this->request->post, $store_id);		
	
			if ($this->request->post['buttonForm'] == 'apply') {
				$this->redirect($this->url->link('module/' . $this->_name, 'store_id=' . $this->data['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'));
				$this->session->data['success'] = $this->language->get('text_success');
			}
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/mattimeotheme', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/mattimeotheme', 'store_id=' . $this->data['store_id'] . '&token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extended_module', 'token=' . $this->session->data['token'], 'SSL');
		
		//Featured product custom box
		
		if (isset($this->request->post['featured_product2'])) {
			$this->data['featured_product2'] = $this->request->post['featured_product2'];
		} else {
			$this->data['featured_product2'] = $this->config->get('featured_product2');
		}	
		$this->load->model('catalog/product');
				
		if (isset($this->request->post['featured_product2'])) {
			$products = explode(',', $this->request->post['featured_product2']);
		} else {		
			$products = explode(',', $this->config->get('featured_product2'));
		}
		
		$this->data['products'] = array();
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}	
	/////////////////////////////////////////////////////
		 $Variables_matt = array(
		  //GENERAL SETTINGS
			'gen_responsive',
			'site_position',
			'slider_position',
			'menu_width',
			'search_position',
			'showmore_featured',
			'showmore_latest',
			'showmore_bestseller',
			'showmore_specials',
			'showmore_related',
			'showmore2_featured',
			'showmore2_latest',
			'showmore2_bestseller',
			'showmore2_specials',
			'show_wishlist',
			'show_compare',
			'img_additional1',
			'img_additional2',
			'fixmenu',
			'topcontrol',
			'colorsite',
			'detail_view',
			'quick_view',
			
			 //HEADER
			'top_m_wish',
		    'top_m_home',
			'top_m_brand',
			'top_m_account',
			'top_m_spec',
			'top_m_checkout',
			'top_m_cart',
			'top_m_compare',
			'top_m_welcome',
			'top_menu',
			'top_news',
		
		   //TOP MENU
		    'gen_topmenu',
			'gen_m_home',
			'gen_m_column',
			'gen_m_info',
			'gen_m_brand',
			'gen_m_account',
			'gen_m_spec',
			'status_menu',
			'status_menu2',
			'topmenulink_lang',
			'topmenulink_custom',
			'gen_news',
			
			//SLIDESHOW
			'slider_status',
			'slider_effect',
			'slider_animSpeed',
			'slider_pauseTime',
			'slider_startSlide',
			'slider_directionNav',
			'slider_controlNav',
			'slider_pauseOnHover',
			
			//Product
			'status_product',
			'status_product_tab',
			'product_text',
			'product_title_tab',
			'product_text_tab',
			'product_zoom',
		
		    // Widget 
			'footer_status',
			'footer_status_home',
			
			
			
			'twitter_f_status',
			'twitter_f_title',
			'tweets_f',
			'twitter_f_user',
			'twitter_f_id',
			'twitter_f_theme',
			'twitter_f_link',
						
			'facebook_f_status',
			'facebook_f_title',
			'facebook_f_name',
			'facebook_f_theme',
			
			'vk_f_status',
			'vk_f_title',
			'vk_f_id',
			
			//Parallax box
			'comptext_status',
			'comptext_header_text',
			'comptext_text',
			'parallax_width',
			'parallax_height',
			'parallax_bg', 
			'parall_fonts',  'parall_fonts_size', 'parall_fonts_weight',  'parall_fonts_transf',  'parall_fonts_color', 
			'parall_p_width', 'parall_p_height',
			'parall_limit', 'parall_limit2',
			
			// FOOTER site
			'h_contact_status',
			'f_contact_status',
			'f_contact_phone',
			'f_contact_fax',
			'f_contact_email',
			'f_contact_skype',
			'f_contact_address',
			'f_contact_copyright',
			'compfootertext_title',
			'f_link1',
			'f_link2',
			'f_link3',
			'f_link4',
			'f_link5',
			'f_link6',
			'f_link7',
			 
			// Fonts
			'fonts_status',
			'body_fonts',            'body_fonts_size',     'body_fonts_weight',
			'title_fonts',           'title_fonts_size',    'title_fonts_weight',    'title_fonts_transf',
			'menu_fonts',            'menu_fonts_size',     'menu_fonts_weight',     'menu_fonts_transf',
			'module_fonts',          'module_fonts_size',   'module_fonts_weight',   'module_fonts_transf',
			'categ_fonts',           'categ_fonts_size',    'categ_fonts_weight',    'categ_fonts_transf',
			                         'news_fonts_size',     'news_fonts_weight',     'news_fonts_transf',
			'banner_fonts',	         'banner_fonts_size1',  'banner_fonts_weight',   'banner_fonts_transf',
			                         'banner_fonts_size2',
									 'banner_fonts_size3',
									 'banner_fonts_size4',
		    'banner_slider_fonts',   'banner_slider_size1',  'banner_slider_weight',  'banner_slider_transf',
			                         'banner_slider_size2',
									 'banner_slider_size3',
									 'banner_slider_size4',
		    'mattimeo_categ_fonts',  'mattimeo_categ_size', 'mattimeo_categ_weight', 'mattimeo_categ_transf',
			'price_fonts',           'price_fonts_size',    'price_fonts_weight',    'bigprice_fonts_size',
			'button_fonts',          'button_fonts_size',   'button_fonts_weight',   'button_fonts_transf',
			'name_fonts',            'name_fonts_size',     'name_fonts_weight',     'name_fonts_transf',
			
             // Colors
			'color_status',
			'color_bg',
			'image_bg',
			'position_bg',
			'repeat_bg',
			'attachment_bg',
			'page_bg',
			'page_bg_status',
			'main_btext',
			'main_smallbtext',
			'main_link',
			'main_linkhover',
			'main_pagetitle',
			'main_pagetitle_h2',
			'main_headermod', 'main_headermod_fon', 'main_headermod_border',
			'main_headermod2', 'main_headermod2_fon',
			'main_bread', 'main_bread_hover',
			'captable_bg', 'captable_font',
			'pagin_bg', 'pagin_font', 'pagin2_bg', 'pagin2_font',
			'filter_color',
			'checkout_color_text', 'checkout_color_bg',
			
			'header_bg',
			'color_header_bg',
			'image_header_bg',
			'position_header_bg',
			'repeat_header_bg',
			'top_header_bg',
			'color_top_header_bg',
			'top_link',
			'top_link_hover',
			'top_link_bg',
			'header_cart_bg',
			'header_cart_link',
			'currency_link', 'currency_fon',
			'top_headertext',
			
			'menu_bg_status',
			'menu_bg_color',
			'menu2_bg_color',
			'menulink_color',  'menulink_hover_color',  'menulink_bg_color',
			'menulink2_color', 'menulink2_hover_color', 'menulink2_bg_color',
			
			'button_bg_color', 'button_link_color',     'button_bg2_color', 'button_link2_color',
			'arrow_bg_color',  'arrow2_bg_color',
			
			'category_bg_color', 'category_link_color', 'category2_bg_color', 'category2_link_color',
			'category_subbg_color', 'category_sublink_color', 
			'categoryactiv_bg_color', 'categoryactiv_link_color', 'categoryactiv2_link_color',
			
			'product_name', 'product_name_hover',
			'product_price',
			'product_sale', 'product_sale_bg',
			'product_oldprice',
			'product_link', 'product_link_hover',
			'product_bg',  'product_bg_hover',
			'product_tabs_link', 'product_tabs_bg',
			
			'footer_bg',
			'footer_bg_status',
			'footer_h3',
			'footer_text',
			'footer_link', 'footer_link_hover', 'footer_link_bg',
			'powered_text', 'powered_bg',
			'footer_custom_t', 'footer_custom_bg',
			'image_footer_bg',
			'position_footer_bg',
			'repeat_footer_bg',
			
			'news_button_link', 'news_button_link_hover',  
			'news_button_bg', 'news_button_bg_hover',
			'news_heading', 'news_heading_hover',
			'news_data',
			
			'other_show1_link', 'other_show1_bg',
			'other_heading',
			'other_bg',
			'other_show2_link', 'other_show2_bg',
			'other_prod1_bg', 'other_prod2_bg',
			'other_banner_heading1', 'other_banner_heading2','other_banner_heading3', 'other_banner_heading4',
            'other_bannerslider1', 'other_bannerslider2', 'other_bannerslider3', 'other_bannerslider4',
			
		);

		$mattimeo_config = $this->model_setting_setting->getSetting('mattimeotheme', $this->data['store_id']);

        foreach ($Variables_matt as $Variables_matt_all) {
            if (isset($this->request->post[$Variables_matt_all])) {
                $this->data[$Variables_matt_all] = $this->request->post[$Variables_matt_all];
            } else {
                $this->data[$Variables_matt_all] = isset($mattimeo_config[$Variables_matt_all]) ? $mattimeo_config[$Variables_matt_all] : null;
				//$this->config->get($Variables_matt_all);
			}
		}
		
		$this->data['matt'] = array();
		if (isset($this->request->post['matt_array'])) {
			$this->data['matt'] = $this->request->post['matt_array'];
		} else { 
			$this->data['matt'] = isset($mattimeo_config['matt_array']) ? $mattimeo_config['matt_array'] : null;
			//$this->config->get('matt_array');
		}
		
		$this->data['mattData'] = array();
		if (isset($this->request->post['mattimeomod'])) {
			$this->data['mattData'] = $this->request->post['mattimeomod'];
		} else { 
			$this->data['mattData'] = isset($mattimeo_config['mattimeomod']) ? $mattimeo_config['mattimeomod'] : null;
			//$this->config->get('mattimeomod');
		}
		$this->data['mattlink'] = array();
		if (isset($this->request->post['mattimeolink'])) {
			$this->data['mattlink'] = $this->request->post['mattimeolink'];
		} else { 
			$this->data['mattlink'] = isset($mattimeo_config['mattimeolink']) ? $mattimeo_config['mattimeolink'] : null;
			//$this->config->get('mattimeolink');
		}
        $this->data['mattlink2'] = array();
		if (isset($this->request->post['mattimeolinkheader'])) {
			$this->data['mattlink2'] = $this->request->post['mattimeolinkheader'];
		} else { 
			$this->data['mattlink2'] = isset($mattimeo_config['mattimeolinkheader']) ? $mattimeo_config['mattimeolinkheader'] : null;
			//$this->config->get('mattimeolinkheader');
		}
			
		//Payment icons
		$this->load->model('tool/image');
	
		if (isset($this->request->post['mattimg'])) {
			$mattimgs = $this->request->post['mattimg'];
		}else { 
			$mattimgs = isset($mattimeo_config['mattimg']) ? $mattimeo_config['mattimg'] : array();
			// $this->config->get('mattimg');
		}
		
	
		foreach ($mattimgs as $mattimg) {
			if ($mattimg['image'] && file_exists(DIR_IMAGE . $mattimg['image'])) {
				$image = $mattimg['image'];
			} else {
				$image = 'no_image.jpg';
			}			
			
			$this->data['mattimgs'][] = array(
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($image, 50, 50),
				'title'                    => $mattimg['title'] ,
			);	
		} 
	
	    //Social networks
		if (isset($this->request->post['mattnetwork'])) {
			$mattnetworks = $this->request->post['mattnetwork'];
		} else { 
			$mattnetworks =  isset($mattimeo_config['mattnetwork']) ? $mattimeo_config['mattnetwork'] : array();
			//$this->config->get('mattnetwork');
		}
		
	
		foreach ($mattnetworks as $mattnetwork) {
			if ($mattnetwork['image'] && file_exists(DIR_IMAGE . $mattnetwork['image'])) {
				$image = $mattnetwork['image'];
			} else {
				$image = 'no_image.jpg';
			}			
			
			$this->data['mattnetworks'][] = array(
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($image, 50, 50),
				'title'                    => $mattnetwork['title'] ,
				'href'                     => $mattnetwork['href'] ,
			);	
		} 
	


			
		$this->data['text_image_manager'] = 'Image manager';
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$getLayouts = $this->data['layouts'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->template = 'module/mattimeotheme.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		 if (isset($this->data['image_bg']) && $this->data['image_bg'] != "" && file_exists(DIR_IMAGE . $this->data['image_bg'])) {
            $this->data['image_preview'] = $this->model_tool_image->resize($this->data['image_bg'], 70, 70);
        } else {
            $this->data['image_preview'] = $this->model_tool_image->resize('no_image.jpg', 70, 70);
        }
		
		 if (isset($this->data['image_header_bg']) && $this->data['image_header_bg'] != "" && file_exists(DIR_IMAGE . $this->data['image_header_bg'])) {
            $this->data['image_header_preview'] = $this->model_tool_image->resize($this->data['image_header_bg'], 70, 70);
        } else {
            $this->data['image_header_preview'] = $this->model_tool_image->resize('no_image.jpg', 70, 70);
        }
		 if (isset($this->data['image_footer_bg']) && $this->data['image_footer_bg'] != "" && file_exists(DIR_IMAGE . $this->data['image_footer_bg'])) {
            $this->data['image_footer_preview'] = $this->model_tool_image->resize($this->data['image_footer_bg'], 70, 70);
        } else {
            $this->data['image_footer_preview'] = $this->model_tool_image->resize('no_image.jpg', 70, 70);
        }
		
		 if (isset($this->data['parallax_bg']) && $this->data['parallax_bg'] != "" && file_exists(DIR_IMAGE . $this->data['parallax_bg'])) {
            $this->data['parallax_preview'] = $this->model_tool_image->resize($this->data['parallax_bg'], 70, 70);
        } else {
            $this->data['parallax_preview'] = $this->model_tool_image->resize('no_image.jpg', 70, 70);
        }
		
		
		// NO IMAGE
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 50, 50);
		
			
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/mattimeotheme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}	
	
}
?>