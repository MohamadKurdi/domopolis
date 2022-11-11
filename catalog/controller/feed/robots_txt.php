<?
class ControllerFeedRobotsTxt extends Controller {
	
	public function index() {

		$noindexHosts = [];
	
		if (in_array($this->request->server['HTTP_HOST'], $noindexHosts)) {
			$this->response->addHeader('Content-Type: text/plain');
			$output = 'User-agent: *';
			$output = 'Disallow: /';
			$output .= PHP_EOL;
			$output .= PHP_EOL;		
		} else {
			
			$this->response->addHeader('Content-Type: text/plain');
			$this->response->addHeader('Content-Disposition: inline; filename="robots.txt"');
			$output = file_get_contents('./robots_all.txt');
			$output .= PHP_EOL;
			$output .= PHP_EOL;			
			$output .= PHP_EOL;
			$output .= 'Host: https://'.$this->request->server['HTTP_HOST'] . PHP_EOL;
			$output .= 'Sitemap: https://'.$this->request->server['HTTP_HOST'].'/sitemap.xml';
		}
	
		$this->response->setNonHTTPSOutput($output);
	}
	
	
}