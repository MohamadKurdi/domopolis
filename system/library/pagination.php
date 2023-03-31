<?php
class Pagination {
	public $total 			= 0;
	public $page 			= 1;
	public $limit 			= 20;
	public $num_links 		= 5;
	public $num_pages 		= 0;
	public $url 			= '';
	public $text 			= 'Showing {start} to {end} of {total} ({pages} Pages)';
	public $text_first 		= '|&lt;';
	public $text_last 		= '&gt;|';
	public $text_next 		= '&gt;';
	public $text_prev 		= '&lt;';
	public $style_links 	= 'links';
	public $style_prev 		= 'prev';
	public $style_next 		= 'next';
	public $id_next 		= 'pagination-next';
	public $style_results 	= 'results';
	public $paging_style 	= '';
	private $language 		= null;


	public function __construct($registry = null){	
		if ($registry){				
			$this->language = $registry->get('language');							
		}
	}

	public function setData($data){
		if (!empty($data['total'])){
			$this->total = (int)$data['total'];
		}

		if (!empty($data['page'])){
			$this->page = (int)$data['page'];
		}

		if (!empty($data['num_links'])){
			$this->num_links = (int)$data['num_links'];
		}

		if (!empty($data['num_pages'])){
			$this->num_pages = (int)$data['num_pages'];
		}

		if (!empty($data['limit'])){
			$this->limit = (int)$data['limit'];
		}

		if (!empty($data['text'])){
			$this->text = trim($data['text']);
		}

		if (!empty($data['url'])){
			$this->url = trim($data['url']);
		}
	}

	public function render_text(){			
		$total = $this->total;

		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}

		if (!(int)$this->limit) {
			$limit = 20;
		} else {
			$limit = $this->limit;
		}

		$num_links = $this->num_links;
		$num_pages = ceil($total / $limit);

		$output = '';

		$find = array(
			'{start}',
			'{end}',
			'{total}',
			'{pages}',
			'{products}'
		);

		$replace = [
			($total) ? (($page - 1) * $limit) + 1 : 0,	
			((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),	
			$total, 
			$num_pages,
			morphos\Russian\NounPluralization::pluralize($total, 'товар')
		];

		return str_replace($find, $replace, $this->text);
	}

	public function render_simple($data = []){
		if ($data){
			$this->setData($data);
		}

		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}

		$num_pages = $this->num_pages;
		$num_links = $this->num_links;

		$output = '';

		if ($page > 1) {
			$output .= ' <a onclick="page(' . 1 . ')">' . $this->text_first . '</a> ';
			$output .= ' <a onclick="page(' . ($page - 1) . ')" class="' . $this->style_prev . '">' . $this->text_prev . '</a> ';
		}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);

				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}

				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			if ($start > 1) {
				$output .= '<span class="pagination-dots">...</span>';
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					$output .= ' <b>' . $i . '</b> ';
				} else {
					$output .= ' <a onclick="page(' . $i . ')">' . $i . '</a> ';
				}	
			}

			if ($end < $num_pages) {
				$output .= '<span class="pagination-dots">...</span>';
			}
		}

		if ($page < $num_pages) {
			$output .= ' <a onclick="page(' . ($page + 1) . ')">' . $this->text_next . '</a> '; 
			$output .= ' <a onclick="page(' . $num_pages . ')">' . $this->text_last . '</a> ';
		}

		return ($output ? '<div class="' . $this->style_links . '">' . $output . '</div>' : '');
	}	

	public function render($data = []) {
		if ($data){
			$this->setData($data);
		}

		$total = $this->total;

		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}

		if (!(int)$this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}

		$num_links = $this->num_links;
		$num_pages = ceil($total / $limit);

		$output = '';

		if (defined('THIS_IS_CATALOG') && THIS_IS_CATALOG && $num_pages == 1){
			return $output;
		}

		if ($page > 1) {
			$output .= str_replace(array('?page=1','&page=1'), array('',''), ' <a href="' . str_replace('{page}', 1, $this->url) . '">' . $this->text_first . '</a> ');

			if ($page == 2) {
				$output .= str_replace(array('?page=1','&page=1'), array('',''), '<a class="' . $this->style_prev . '" href="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->text_prev . '</a> ');
			} else {
				$output .= '<a class="' . $this->style_prev . '" href="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->text_prev . '</a> ';
			}
		}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);

				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}

				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			if ($start > 1) {
				$output .= '<span class="pagination-dots">...</span>';
			}

			if($this->paging_style == 'select'){
				$output .= '<select onchange="if(this.value) window.location.href=this.value" style="border: 1px solid #DDD; color: #A3A3A3; font-size: 12px; display: inline-block; height: 25px; margin: 0; padding: 3px 5px; vertical-align: 0;">';
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					if($this->paging_style == 'select'){
						$output .= '<option value="" selected="selected">' . $i . '</option>';
					} else {
						$output .= ' <b>' . $i . '</b> ';
					}
				} else {
					if($this->paging_style == 'select'){
						$output .= ' <option value="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</option> ';
					} else {
						if ($i == 1){
							$output .= str_replace(array('?page=1','&page=1'), array('',''), ' <a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a> ');
						} else {
							$output .= ' <a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a> ';
						}
					}
				}	
			}

			if($this->paging_style == 'select'){
				$output .= '</select>';
			}

			if ($end < $num_pages) {
				$output .= '<span class="pagination-dots">...</span>';
			}
		}

		if ($page < $num_pages) {
			$output .= ' <a id="' . $this->id_next . '" class="' . $this->style_next . '" href="' . str_replace('{page}', $page + 1, $this->url) . '">' . $this->text_next . '</a> <a href="' . str_replace('{page}', $num_pages, $this->url) . '">' . $this->text_last . '</a> ';
		}

		$find = array(
			'{start}',
			'{end}',
			'{total}',
			'{pages}',
			'{products}'
		);


		$replace = array(($total) ? (($page - 1) * $limit) + 1 : 0,	((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),	$total, $num_pages, ($total) ? morphos\Russian\NounPluralization::pluralize((int)$total, 'товар'):'');

		if (defined('THIS_IS_CATALOG') && THIS_IS_CATALOG) {
			return ($output ? '<div class="' . $this->style_links . '">' . $output . '</div>' : '');				
		} else {
			return ($output ? '<div class="' . $this->style_links . '">' . $output . '</div>' : '') . '<div class="' . $this->style_results . '">' . str_replace($find, $replace, $this->text) . '</div>';
		}
	}
}		