<?php 
/**
* @author Shashakhmetov Talgat <talgatks@gmail.com>
*/
$_['heading_title'] = 'PhotoGallery';
//Album
$_['album_types'][] = 'All product images in selected category(ies)';
$_['album_types'][] = 'Images of the directories';
$_['album_types'][] = 'Selected images';

$_['module_types'][] = 'Galleries';
$_['module_types'][] = 'Photos';

$_['arr_js_lib_types'][] = 'ColorBox';
$_['arr_js_lib_types'][] = 'LightBox';
$_['arr_js_lib_types'][] = 'FancyBox';

//sql layout names
$_['text_layout_galleries'] = 'Galleries';
$_['text_layout_photos'] = 'Photos';

//Image manager
$_['text_image_manager'] = 'Image Manager';

$_['entry_album_name'] 		= 'Gallery\'s Title:';
$_['entry_album_seo_name'] 		= 'SEO URL: <span class="help">Must be unique throughout the system.</span>';
$_['entry_album_type'] 		= 'Gallery\'s Type:';
$_['entry_cover_image'] 		= 'Gallery cover:';
$_['entry_gallery_photos_limit'] 		= 'Photos limit on the gallery\'s page: <span class="help">0 - Without limit (show all)</span>';
$_['entry_gallery_show_limiter'] 		= 'Show limiter:';
$_['entry_thumb_size'] 		= 'Size small images on the gallery page (Width x Height):';
$_['entry_thumb_size_mod'] 		= 'Size small images on the module (Width x Height):';
$_['entry_popup_size'] 		= 'Size pop-up images on the gallery page (Width x Height):';
$_['entry_popup_size_mod'] 		= 'Size pop-up images on the module (Width x Height):';
$_['entry_include_additional_images'] 	= 'Show product additional images:';
$_['entry_category_list'] 	= 'Select a category:';
$_['text_show_album_text'] 		= 'Go to Gallery';
$_['text_show_galleries_text'] 		= 'Go to section galleries';
$_['entry_directory'] 		= 'Enter the path and file mask:<span class="help">The path is relative to the root directory of a site. <br> Processed only folder image. <br> can list several ways to mask each way on a new line.</span>';
$_['entry_galery_images'] 	= 'Select images:';
$_['text_new_album'] 		= 'New Gallery';
$_['text_placeholder_description'] 		= 'Caption';
$_['entry_title'] 				= 'HTML-tag Title:';
$_['entry_h1'] 					= 'HTML-tag H1:';
$_['entry_meta_keywords'] 		= 'Мета-tag Keywords:';
$_['entry_meta_description'] 	= 'Мета-tag Description:';
$_['entry_description'] 		= 'Description:';
$_['entry_album_status'] 		= 'Status:<span class="help">Even after turning off this album can be used in modules, but it will not appear on the page galleries.</span>';
$_['entry_sort_order'] 			= 'Sort order:';
$_['entry_gallery_cover_image_dimension'] 			= 'Size cover images in the galleries section:';
$_['entry_gallery_show_counter'] 			= 'Show number of images of each gallery page galleries:';
$_['entry_gallery_show_description'] 			= 'Show galleries description:';

$_['entry_module_category'] 		= 'Show on category: <span class="help">If you want the module to be displayed in all categories - leave blank.</span>';
$_['entry_module_product'] 			= 'Show on product: <span class="help">If you want the module to be displayed on the pages of all the goods - leave blank.</span>';
// h2
$_['text_album_page_settings'] = 'Settings for individual page gallery';
$_['text_album_data']			= 'Photos';
$_['text_js_library_settings']	= 'Settings JavaScript libraries';
$_['text_albums_settings']		= 'Settings section galleries';
$_['text_general_settings']		= 'General settings';
$_['text_modules_settings']		= 'Modules';

//Settings
$_['entry_enable_full_cache']   = 'Full caching: <span class="help">This will reduce the number of requests to the database.</span>';
$_['entry_js_library']   		= 'JS library gallery:';
$_['entry_galleries_include_seo_path'] 	= 'Include into seo url galleries section:';
$_['entry_galleries_seo_name'] 		= 'Galleries page SEO URL: <span class="help">Must be unique throughout the system.</span>';

$_['entry_gallery_include_colorbox'] 	= 'Insert a file colorbox: <span class="help">If header.tpl already included colorbox, then switch it off.</span>';
$_['entry_gallery_include_lightbox'] 	= 'Insert a file lightbox: <span class="help">If header.tpl already included lightbox, then switch it off.</span>';
$_['entry_gallery_include_fancybox'] 	= 'Insert a file fancybox: <span class="help">If header.tpl already included fancybox, then switch it off.</span>';
$_['entry_gallery_include_jstabs'] 		= 'Insert a file tabs.js: <span class="help">If header.tpl already included tabs.js, then switch it off.</span>';
$_['entry_gallery_include_lazyload']	= 'Insert a file lazyload.js: <span class="help">If header.tpl already included lazyload.js, then switch it off.</span>';
$_['entry_category_layout']   		= 'Category layout: <span class="help">Necessary in order to be able to display modules on a specific category page.</span>';
$_['entry_product_layout']   		= 'Product layout: <span class="help">Necessary in order to be able to display modules on a specific category page.</span>';

//Messages 
$_['text_success_edited'] 					= 'Gallery successfully edited and saved.';
$_['text_success_added'] 					= 'Gallery added successfully.';
$_['text_success_modules'] 					= 'Modules saved successfully.';
$_['text_success_deleted'] 					= 'Selected objects were successfully removed.';
$_['text_success_copied'] 					= 'Selected objects have been copied successfully.';
$_['text_success_settings'] 				= 'Settings saved successfully.';
$_['text_success_updated'] 					= 'Module succesfully updated.';
$_['text_error_no_albums'] 					= 'No galleries. To create a module, first create a gallery.';
$_['text_error_check_form'] 				= 'Fill in all fields.';

//Tabs
$_['tab_general'] 	= 'Common';
$_['tab_data'] 		= 'Data';

//Table
$_['column_name'] 			= 'Gallery\'s Title:';
$_['column_album_type'] 	= 'Gallery\'s Type:';
$_['column_enabled'] 		= 'Status:';
$_['column_sort_order'] 	= 'Sort order:';
$_['column_action'] 		= 'Action:';
$_['text_list_empty'] 		= 'List is empty';

//Section
$_['section_new_album'] = 'New Gallery';
$_['section_edit_album'] = 'Editing gallery';
$_['section_album'] = 'Photo Gallery';
$_['section_album_list'] = 'List of galleries';
$_['section_modules'] = 'Modules galleries';
$_['section_settings'] = 'Settings';

//Buttons
$_['button_text_save'] 		= 'Save';
$_['button_text_insert'] 	= 'New';
$_['button_text_copy'] 		= 'Copy';
$_['button_text_edit'] 		= 'Edit';
$_['button_text_delete'] 	= 'Delete';
$_['button_text_cancel'] 	= 'Cancel';

//Modules

// Text
$_['text_module']         = 'Modules';
$_['text_new_module_name']= 'New module';
$_['text_success']        = 'Module configuration updated!';
$_['text_content_top']    = 'Top of page';
$_['text_content_bottom'] = 'Bottom of page';
$_['text_column_left']    = 'The left column';
$_['text_column_right']   = 'The right column';

// Entry
$_['entry_module_name']         = 'Name of module:<span class="help">The name for the administrative part.</span>';
$_['entry_module_header']         = 'Title of the module:';
$_['entry_show_gallery_album_description']         = 'Show description of the gallery on the gallery page:';
$_['entry_use_lazyload']         = 'Use LazyLoad:<span class="help">Lazy load images on the gallery page.</span>';
$_['entry_show_module_header']         = 'Show title';
$_['entry_module_type']         = 'Module Type:';
$_['entry_album_list']         = 'List of items:';
$_['entry_show_covers']         = 'Show the cover picture:';
$_['entry_cover_size']         = 'Cover Size (Width x Height):';
$_['entry_show_counter']         = 'Show count of photos:';
$_['entry_show_album_galleries_link']         = 'Show button for transition:<span class="help">Will be shown below the button to change to the galleries.</span>';
$_['entry_photo_album_list']         = 'List of galleries: <span class="help">If you select several galleries, they will be placed into tabs.</span>';
$_['entry_show_album_description']         = 'Show description of the gallery:';
$_['entry_show_album_link']         = 'Show button for transition:<span class="help">Will be shown below the button to change to the active picture.</span>';
$_['entry_show_album_text']         = 'Button text:';
$_['entry_photos_limit']         = 'Limit images in the module: <span class="help">0 - No Limit</span>';
$_['entry_layout']        = 'Layout:';
$_['entry_position']      = 'Position:';
$_['entry_status']        = 'Status:';
$_['entry_sort_order']    = 'Sort order:';

// Error
$_['error_permission']    = 'You have no rights to control this module!';

$_['text_copy'] = ' (copy)';
$_['text_yes'] = 'Yes';
$_['text_no'] = 'No';
$_['text_browse'] = 'Overview';
$_['text_clear'] = 'Clear';
?>