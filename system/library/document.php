<?php
	class Document {
		private $title 			= '';
		private $description 	= '';
		private $keywords 		= '';
		private $noindex 		= false;
		private $robots 		= '';
		private $links 			= [];		
		private $styles 		= [];
		private $scripts 		= [];
		private $opengraph 		= [];
		private $extra_tags 	= [];
		private $_meta 			= [];
		private $robots_meta 	= [];
		private $hreflangs 		= [];
		
		
		public function setTitle($title) {			
			if (defined('SITE_NAMESPACE') && !defined('THIS_IS_CATALOG')){
				$this->title =  mb_substr(SITE_NAMESPACE, 0, 1) . ' : ' . $title;
				} else {
				$this->title = $title;
			}
		}
		
		public function getTitle() {
			return $this->title;
		}

		public function setHrefLangs($hreflangs) {
			$this->hreflangs = $hreflangs;

			foreach ($hreflangs as $link){				
				$this->addLink($link['link'], 'alternate', $link['hreflang']);			
			}
		}
		
		public function getHrefLangs() {
			return $this->hreflangs;
		}
		
		public function setDescription($description) {
			$this->description = $description;
		}
		
		public function getDescription() {
			return $this->description;
		}
		
		public function setKeywords($keywords) {
			$this->keywords = $keywords;
		}
		
		public function getKeywords() {
			return $this->keywords;
		}
		
		public function addExtraTag($property, $content = '', $name=''){
			$this->extra_tags[md5($property)] = array(
			'property' => $property,
			'content'  => $content,
			'name'     => $name,
			);
		}
		
		public function getExtraTags(){
			return $this->extra_tags;
		}
		
		public function setRobots($content){
			$this->robots = $content;			
		}
		
		public function getRobots(){
			return $this->robots;			
		}
				
		public function addRobotsMeta( $content ) {
			$this->setRobots($content);
		}
		
		public function getRobotsMeta() {
			return $this->robots;
		}		
		
		public function addMeta( $key, $val, $type = 'name' ) {
			if( $val === '' ) return $this;
			
			$this->_meta[$type . ':' . $key] = array(
			$type    => $key,
			'type'   => $type,
			'key'    => $key,
			'content'=> $val
			);
			
			return $this;
		}
		
		public function getMeta() {
			return $this->_meta;
		}
		
		public function addLink($href, $rel, $hreflang = false) {

			if ($hreflang == 'de-DE'){
				return;			
			}
		
			$this->links[md5($href.$rel.$hreflang)] = array(
			'href' 		=> $href,
			'rel'  		=> $rel,
			'hreflang' 	=> $hreflang
			);			
		}
		
		public function getLinks() {	
			return $this->links;
		}	
		
		public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {		
		
			$this->styles[md5($href)] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
			);
		}
		
		public function getStyles() {
			return $this->styles;
		}	
		
		public function addOpenGraph($property, $content){
			$this->opengraph[$property] = array(
			'property'  => str_replace('og:og', 'og:', $property),
			'content'   => $content,
			);		
		}
		
		public function getOpenGraphs() {
			return $this->opengraph;
		}
		
		public function setNoindex($state = false) {
			$this->noindex = $state;
		}
		
		public function isNoindex() {
			return $this->noindex;
		}
		
		public function addScript($script) {
		
			if (stripos($script, '?v=')){
				$version = explode("?v=",$script);
				$script = $version[0];
			}
		
			$this->scripts[md5($script)] = $script;			
		}
		
		public function getScripts() {
			return $this->scripts;
		}
	}