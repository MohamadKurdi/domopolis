<?php
class Response {
  /**
  * @var array $headers Array of HTTP response headers
  */
  private $headers = [];


  /**
   * @var int $level Compression level 0-9
   */  
  private $level = 0;


  /**
   * @var string $output Response output
   */
  private $output = '';


  /**
   * @var object $db Database object
   */
  private $db = null;


  /**
   * @var object $config Configuration object
   */
  private $config = null;


  /**
   * @var object $cache Caching object  
   */
  private $cache = null;


  /**
   * @var array $fastTranslate Array for fast text translate
   */
  private $fastTranslate = null;


  /**
   * Constructor
   *
   * @param object $registry Registry object to access dependencies
   */  
  public function __construct($registry = false) {
  	if ($registry) {				
  		$this->cache  	= $registry->get('cache');
  		$this->config 	= $registry->get('config');
  		$this->db 		= $registry->get('db');
  		$this->language = $registry->get('language');

  		if (defined('THIS_IS_CATALOG') && $this->db && $this->cache && $this->config && $this->config->get('config_language_id')){
  			$this->fastTranslate = $this->prepareFastTranslateArray($registry->get('languages')[$this->config->get('config_language')]['fasttranslate']);
  		}				
  	}
  }


  /**
   * Add HTTP header
   * 
   * @param string $header Header string
   */
  public function addHeader($header) {
  	$this->headers[] = $header;
  }

			
  /**
 * Prepare array for fast text translate
 * 
 * Parses fast translate text into arrays
 *
 * @param string $fastTranslate Fast translate text 
 * 
 * @return array Associative array with 'fTFrom' and 'ftTo' keys 
 */
  private function prepareFastTranslateArray($fastTranslate){
  	$fTFrom = array();
  	$ftTo = array();

  	$fTexploded = explode(PHP_EOL, $fastTranslate);
  	if (is_array($fTexploded)){
  		foreach ($fTexploded as $line){
  			$lineExploded = explode('::', $line);

  			if (is_array($lineExploded) && count($lineExploded) == 2){
  				$fTFrom[] = trim($lineExploded[0]);
  				$ftTo[] = trim($lineExploded[1]);
  			}
  		}
  	}

  	return [
  		'fTFrom' 	=> $fTFrom,
  		'ftTo' 		=> $ftTo
  	];
  }


  /**
 * Perform fast text translate
 *
 * Replaces text in output using fast translate arrays
 *
 * @return Response object for chaining 
 */
  private function doFastTranslate(){			
  	if ($this->fastTranslate){
  		$this->output = str_replace($this->fastTranslate['fTFrom'], $this->fastTranslate['ftTo'], $this->output);
  	}

  	return $this;
  }


   /**
   * Redirect to URL
   *
   * @param string $url URL to redirect
   */
  public function redirect($url) {
  	header('Location: ' . $url);
  	exit;
  }


  /**
   * Set compression level
   *
   * @param int $level Compression level 0-9 
   */
  public function setCompression($level) {
  	$this->level = $level;
  }


  /**
   * Set JSON response
   *
   * @param array|object $json JSON data
   *
   * @return Response object for chaining
   */
  public function setJSON($json) {
  	$this->addHeader('Content-Type: application/json');		
  	$this->output = trim(json_encode($json));

  	return $this;
  }

  /**
   * Set XML response
   *
   * @param string $xml XML data
   *
   * @return Response object for chaining  
   */
  public function setXML($xml) {
  	$this->addHeader('Content-Type: text/xml');		
  	$this->output = trim($xml);

  	return $this;
  }

  /**
   * Set CSV response
   *
   * @param array $data data
   * @param array $header header line
   * @param string $filename
   * @param string $charset
   *
   * @return Response object for chaining  
   */
  public function outputCSV(array $data, array $header = [], string $filename = '', string $charset = "utf-8"):void {
    set_time_limit(1000);
    ini_set('memory_limit','4G');

    if (!$filename){
      $filename = HTTP_DOMAIN . '_csv_export_' . date('Y_m_d') . '.csv';
    } else {
      $filename = HTTP_DOMAIN . $filename . date('Y_m_d') . '.csv';
    }

    if (!$header){
      $header = array_keys($data[0]);
    }

    header("Content-Type: text/csv charset=$charset"); 
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = fopen('php://output', 'w');   
    fputcsv($output, $header);    

    foreach ($data as $line){   
      fputcsv($output, $line);
    }

    fclose($output);
  } 


  /**
   * Set response output
   *
   * @param string $output Response output
   *
   * @return Response object for chaining
   */
  public function setOutput($output) {
  	if (IS_HTTPS && defined('THIS_IS_CATALOG')){
  		$output = str_ireplace('http://', 'https://', $output);
  	}			

  	$this->output = $output;								
  	$this->doFastTranslate();			

  	return $this;
  }


   /**
   * Set output without HTTPS rewrite
   *
   * @param string $output Output data
   * 
   * @return Response object for chaining
   */
  public function setNonHTTPSOutput($output) {
  	$this->output = trim($output);

  	return $this;
  }


   /**
   * Get response output
   *
   * @return string Response output
   */
  private function compress($data, $level = 0) {
  	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
  		$encoding = 'gzip';
  	} 

  	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
  		$encoding = 'x-gzip';
  	}

  	if (!isset($encoding)) {
  		return $data;
  	}

  	if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
  		return $data;
  	}

  	if (headers_sent()) {
  		return $data;
  	}

  	if (connection_status()) { 
  		return $data;
  	}

  	$this->addHeader('Content-Encoding: ' . $encoding);			

  	if ((int)$level > 0){
  		return gzencode($data, (int)$level);
  	} else {
  		return $data;
  	}
  }

  /**
   * Get response output
   *
   * @return string Response output
   */
  public function returnOutput(){			
  	return $this->output;
  }


  /**
   * Output response
   */
  public function output() {
  	if ($this->output) {								
  		if (!headers_sent()) {
  			foreach ($this->headers as $header) {
  				header($header, true);
  			}
  		}

  		echo trim($this->output);
  		echo $this->outputDebug();				
  	}
  }


  /**
   * Output debug data
   *
   * @return string Debug output
   */
  public function outputDebug(){
  	$output = '';
  	if (defined('DEBUGSQL') && DEBUGSQL) {						
  		global $FPCTimer;

  		$totalSQLTime = 0;
  		foreach ($GLOBALS['sql-debug'] as $query) {
  			$totalSQLTime += $query['querytime'];
  		}

  		$output .= '<div id="debug" style="position:relative; bottom:0; z-index:1000; width:100%;min-height:100px; padding:20px; background: #ff3333; ">';
  		$output .= '<div style="width:1000px;margin:0 auto;">';
  		$output .= '	<div style="color:white; font-size:14px; line-height:20px">Page gen time ' . $FPCTimer->getTime() . ' sec</div>';
  		$output .= '	<div style="color:white; font-size:14px; line-height:20px">Total SQL time ' . $totalSQLTime . ' sec</div>';
  		$output .= '	<div style="color:white; font-size:14px; line-height:20px">Total SQL queries ' . count($GLOBALS['sql-debug']) . '</div>';
  		$output .= '	<div style="color:white; font-size:14px; line-height:20px">Mem PHP ' . size_convert(memory_get_usage(false)) . '</div>';
  		$output .= '	<div style="color:white; font-size:14px; line-height:20px">Mem SYS ' . size_convert(memory_get_usage(true)) . '</div>';
  		$output .= '	<div style="color:white; font-size:14px; line-height:20px">Peak Mem PHP ' . size_convert(memory_get_peak_usage(false)) . '</div>';
  		$output .= '	<div style="color:white; font-size:14px; line-height:20px">Peak Mem SYS ' . size_convert(memory_get_peak_usage(true)) . '</div>';
  		foreach ($GLOBALS['sql-debug'] as $query) {																		
  			$output .= '	<div style=" width:990px; color:'  . ($query['bad']?'#ff3333':'#009900') . '; font-size:12px;background:white;margin-bottom:0px;padding:2px;">';
  			$output .= ' [' . (($query['bad'])?'LONG QUERY':'OK QUERY') . ']';
  			$output .= ' [' . sprintf('%.7f', $query['querytime'])  . ' sec] <span style="color:black;">[' . $query['cached'] . ']</span></div>'; 

  			$output .= '	<div style="width:990px;color:#666; font-size:8px;background:white;padding:5px;border-top:1px solid #ddd; font-family:Courier">';

  			foreach ($query['backtrace'] as $key => $backtrace){
  				$path = explode('/', $backtrace['file']);
  				$path = array_slice($path, -3, 3, true);

  				$output .= '<div style="line-height:10px;">';
  				$output .= $key . ': ' . ((!empty($backtrace['class']))?$backtrace['class']:'UNKNOWN') . ((!empty($backtrace['type']))?$backtrace['type']:'??') . $backtrace['function'] . ' called from @' . implode('/', $path) . ' at line ' . $backtrace['line'];
  				$output .= '</div>';
  			}

  			$output .= '	</div>';

  			$output .= '<div style=" width:990px; color:#666; font-size:10px;background:white;margin-bottom:3px;padding:5px; border-top:1px solid #ddd;  font-family:Courier">' . $query['sql'] . '</div>';

  		};

  		$output .= '</div>';
  		$output .= '</div>';				
  	}

  	return $output;
  }
}			