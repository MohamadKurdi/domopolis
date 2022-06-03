<?php
class ControllerFeedGallery extends Controller {
	public function index() {
		$output  = '<?xml version="1.0" encoding="UTF-8"?>';
		$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		$this->load->model('catalog/gallery');

		$albums = $this->model_catalog_gallery->getAlbums();
		
		$output .= '<url>';
		$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('gallery/gallery'))) . '</loc>';
		$output .= '<changefreq>weekly</changefreq>';
		$output .= '<priority>0.7</priority>';
		$output .= '</url>';

		foreach ($albums as $album) {
			$output .= '<url>';
			$output .= '<loc>' . str_replace('&', '&amp;', str_replace('&amp;', '&', $this->url->link('gallery/album', 'album_id=' . $album['album_id']))) . '</loc>';
			
			if (isset($album['last_modified']) && $album['last_modified'] != '0000-00-00 00:00:00') {
				$output .= '<lastmod>' . date('Y-m-d', strtotime($album['last_modified'])) . '</lastmod>';
			}else{
				$output .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
			}
			
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';
		}

		$output .= '</urlset>';

		$this->response->addHeader('Content-Type: application/xml');
		$this->response->setOutput($output);
	}
}
?>