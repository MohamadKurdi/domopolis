<?php

$_ = $cfg = array();

// URL Params delimeters
$_['ocfilter_part_separator'] = ';';
$_['ocfilter_option_separator'] = ':';
$_['ocfilter_option_value_separator'] = ',';

// URL GET index
$_['ocfilter_url_index'] = 'filter_ocfilter';

if (!defined('DIR_APPLICATION')) {
	define('DIR_APPLICATION', '');
}

if (!defined('DIR_CATALOG')) {
	define('DIR_CATALOG', '');
}

/**
* Admin files
**/

$code_steps = array();


$code_steps[] = array(
	'file'    => DIR_CATALOG . 'controller/product/category.php',
	'actions' => array(


    array(
			'{SEARCH}' => '$this->data[\'sort\'] = $sort;',

			'{REPLACE}' => '
      // OCFilter Start
      $ocfilter_page_info = $this->getChild(\'module/ocfilter/getPageInfo\');

      if ($ocfilter_page_info) {
        $this->document->setTitle($ocfilter_page_info[\'meta_title\']);

        if ($ocfilter_page_info[\'meta_description\']) {
			    $this->document->setDescription($ocfilter_page_info[\'meta_description\']);
        }

        if ($ocfilter_page_info[\'meta_keyword\']) {
			    $this->document->setKeywords($ocfilter_page_info[\'meta_keyword\']);
        }

			  $this->data[\'heading_title\'] = $ocfilter_page_info[\'title\'];

        if ($ocfilter_page_info[\'description\'] && !isset($this->request->get[\'page\']) && !isset($this->request->get[\'sort\']) && !isset($this->request->get[\'order\']) && !isset($this->request->get[\'search\']) && !isset($this->request->get[\'limit\'])) {
        	$this->data[\'description\'] = html_entity_decode($ocfilter_page_info[\'description\'], ENT_QUOTES, \'UTF-8\');
        }
      } else {
        $meta_title = $this->document->getTitle();
        $meta_description = $this->document->getDescription();
        $meta_keyword = $this->document->getKeywords();

        $filter_title = $this->getChild(\'module/ocfilter/getSelectedsFilterTitle\');

        if ($filter_title) {
          if (false !== strpos($meta_title, \'{filter}\')) {
            $meta_title = trim(str_replace(\'{filter}\', \'(\' . $filter_title . \')\', $meta_title));
          } else {
            $meta_title .= \' (\' . $filter_title . \')\';
          }

          $this->document->setTitle($meta_title);

          if ($meta_description) {
            if (false !== strpos($meta_description, \'{filter}\')) {
              $meta_description = trim(str_replace(\'{filter}\', \'(\' . $filter_title . \')\', $meta_description));
            } else {
              $meta_description .= \' (\' . $filter_title . \')\';
            }

  			    $this->document->setDescription($meta_description);
          }

          if ($meta_keyword) {
            if (false !== strpos($meta_keyword, \'{filter}\')) {
              $meta_keyword = trim(str_replace(\'{filter}\', \'(\' . $filter_title . \')\', $meta_keyword));
            } else {
              $meta_keyword .= \' (\' . $filter_title . \')\';
            }

           	$this->document->setKeywords($meta_keyword);
          }

          $heading_title = $this->data[\'heading_title\'];

          if (false !== strpos($heading_title, \'{filter}\')) {
            $heading_title = trim(str_replace(\'{filter}\', \'(\' . $filter_title . \')\', $heading_title));
          } else {
            $heading_title .= \' (\' . $filter_title . \')\';
          }

          $this->data[\'heading_title\'] = $heading_title;

          $this->data[\'description\'] = \'\';
        } else {
          $this->document->setTitle(trim(str_replace(\'{filter}\', \'\', $meta_title)));
          $this->document->setDescription(trim(str_replace(\'{filter}\', \'\', $meta_description)));
          $this->document->setKeywords(trim(str_replace(\'{filter}\', \'\', $meta_keyword)));

          $this->data[\'heading_title\'] = trim(str_replace(\'{filter}\', \'\', $this->data[\'heading_title\']));
        }
      }
      // OCFilter End

      \\1'
		)
	)
);



$_['ocfilter_install_sql'] = "";

$_['ocfilter_install_code_steps'] = $code_steps;

$cfg = $_;