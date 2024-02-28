<?

namespace hobotix;


final class OpenAIAdaptor
{

	private $db		= null;
	private $config	= null;
	private $log	= null;
	
	public 	$OpenAI = null;


	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');
		$this->log 		= $registry->get('log');

		if ($this->config->get('config_openai_enable') && $this->config->get('config_openai_api_key')){
			$this->OpenAI   = new \Orhanerday\OpenAi\OpenAi($this->config->get('config_openai_api_key'));
		}
	}

	public function checkIfItIsPossibleToMakeRequest(){
		$response = $this->OpenAI->chat([
			'model' 			=> $this->config->get('config_openai_category_alternatenames_model'),
			'messages' 			=> [
				[
					"role" 		=> "user",
					"content" 	=> 'hi!'						
				]], 
				'temperature' 		=> (float)$this->config->get('config_openai_category_alternatenames_temperature'),
				'max_tokens' 		=> (int)$this->config->get('config_openai_category_alternatenames_maxtokens'),
				'top_p' 			=> (float)$this->config->get('config_openai_category_alternatenames_top_p'),
				'frequency_penalty' => (float)$this->config->get('config_openai_category_alternatenames_freq_penalty'),
				'presence_penalty' 	=> (float)$this->config->get('config_openai_category_alternatenames_presence_penalty'),
			]);

		return $this->parseCompletionResponse($response, 'completion', true);
	}

	private function parseCompletionResponse($response = null, $endpoint = 'completion', $check = false){
		if ($json = json_decode($response, true)){
			if (empty($json['error'])){
				
				if ($endpoint == 'completion'){
					if (!empty($json['choices']) && !empty($json['choices'][0]) && !empty($json['choices'][0]['text'])){
						return trim($json['choices'][0]['text']);
					}
				} elseif ($endpoint == 'chat'){
					if (!empty($json['choices']) && !empty($json['choices'][0]) && !empty($json['choices'][0]['message']) && !empty($json['choices'][0]['message']['content'])){
						return trim($json['choices'][0]['message']['content']);
					}
				}

			} else {
				echoLine('[OpenAIAdaptor::parseCompletionError] ' . $json['error']['message'], 'e');

				if ($check){
					return [
						'error' 	=> true,
						'message' 	=> $json['error']['message']
					];
				}
				return false;
			}
		}

		return false;
	}

	private function removeListFromResponse($text){
		$result = [];
		$array = prepareEOLArray($text);

		foreach ($array as $line){
			$line = preg_replace('/[0-9]/', '', $line);
			$line = trim($line, ' .;,-"\'');
			$result[] = $line;
		}

		return implode(PHP_EOL, $result);
	}	

	public function alternateNames($request){
		if ($this->OpenAI && $this->config->get('config_openai_enable_category_alternatenames')){

			if ($this->config->get('config_openai_category_alternatenames_endpoint') == 'chat'){
				$response = $this->OpenAI->chat([
					'model' 			=> $this->config->get('config_openai_category_alternatenames_model'),
					'messages' 			=> [
						[
							"role" 		=> "user",
							"content" 	=> $request						
						]], 
						'temperature' 		=> (float)$this->config->get('config_openai_category_alternatenames_temperature'),
						'max_tokens' 		=> (int)$this->config->get('config_openai_category_alternatenames_maxtokens'),
						'top_p' 			=> (float)$this->config->get('config_openai_category_alternatenames_top_p'),
						'frequency_penalty' => (float)$this->config->get('config_openai_category_alternatenames_freq_penalty'),
						'presence_penalty' 	=> (float)$this->config->get('config_openai_category_alternatenames_presence_penalty'),
					]);	


			} elseif ($this->config->get('config_openai_category_alternatenames_endpoint') == 'completion'){
				$response = $this->OpenAI->completion([
					'model' 			=> $this->config->get('config_openai_category_alternatenames_model'),
					'prompt' 			=> $request,
					'temperature' 		=> (float)$this->config->get('config_openai_category_alternatenames_temperature'),
					'max_tokens' 		=> (int)$this->config->get('config_openai_category_alternatenames_maxtokens'),
					'top_p' 			=> (float)$this->config->get('config_openai_category_alternatenames_top_p'),
					'frequency_penalty' => (float)$this->config->get('config_openai_category_alternatenames_freq_penalty'),
					'presence_penalty' 	=> (float)$this->config->get('config_openai_category_alternatenames_presence_penalty'),
				]);			
			}

			if ($text = $this->parseCompletionResponse($response, $this->config->get('config_openai_category_alternatenames_endpoint'))){
				return $this->removeListFromResponse($text);
			} else {
				return false;
			}
		}

		return false;
	}

	public function categoryDescription($request){
		if ($this->OpenAI && $this->config->get('config_openai_enable_category_descriptions')){
			if ($this->config->get('config_openai_category_descriptions_endpoint') == 'chat'){	
				$response = $this->OpenAI->chat([
					'model' 			=> $this->config->get('config_openai_category_descriptions_model'),
					'messages' 			=> [
						[
							"role" 		=> "user",
							"content" 	=> $request						
						]], 
						'temperature' 		=> (float)$this->config->get('config_openai_category_descriptions_temperature'),
						'max_tokens' 		=> (int)$this->config->get('config_openai_category_descriptions_maxtokens'),
						'top_p' 			=> (float)$this->config->get('config_openai_category_descriptions_top_p'),
						'frequency_penalty' => (float)$this->config->get('config_openai_category_descriptions_freq_penalty'),
						'presence_penalty' 	=> (float)$this->config->get('config_openai_category_descriptions_presence_penalty'),
					]);

			} elseif ($this->config->get('config_openai_category_descriptions_endpoint') == 'completion'){
				$response = $this->OpenAI->completion([
					'model' 			=> $this->config->get('config_openai_category_descriptions_model'),
					'prompt' 			=> $request,
					'temperature' 		=> (float)$this->config->get('config_openai_category_descriptions_temperature'),
					'max_tokens' 		=> (int)$this->config->get('config_openai_category_descriptions_maxtokens'),
					'top_p' 			=> (float)$this->config->get('config_openai_category_descriptions_top_p'),
					'frequency_penalty' => (float)$this->config->get('config_openai_category_descriptions_freq_penalty'),
					'presence_penalty' 	=> (float)$this->config->get('config_openai_category_descriptions_presence_penalty'),
				]);
			}

			if ($text = $this->parseCompletionResponse($response, $this->config->get('config_openai_category_descriptions_endpoint'))){
				return $text;
			} else {
				return false;
			}
		}

		return false;
	}

	public function shortenName($name, $language_code){
		if ($this->OpenAI && $this->config->get('config_openai_shortennames_query_' . $language_code)){

			if ($this->config->get('config_openai_shortennames_endpoint') == 'chat'){	
				$response = $this->OpenAI->chat([
					'model' 			=> $this->config->get('config_openai_shortennames_model'),
					'messages' 			=> [
						[
							"role" 		=> "user",
							"content" 	=> sprintf($this->config->get('config_openai_shortennames_query_' . $language_code), $name, $this->config->get('config_openai_shortennames_length'))						
						]], 					
						'temperature' 		=> (float)$this->config->get('config_openai_shortennames_temperature'),
						'max_tokens' 		=> (int)$this->config->get('config_openai_shortennames_maxtokens'),
						'top_p' 			=> (float)$this->config->get('config_openai_shortennames_top_p'),
						'frequency_penalty' => (float)$this->config->get('config_openai_shortennames_freq_penalty'),
						'presence_penalty' 	=> (float)$this->config->get('config_openai_shortennames_presence_penalty'),
					]);
			} elseif ($this->config->get('config_openai_shortennames_endpoint') == 'completion'){
				$response = $this->OpenAI->completion([
					'model' 			=> $this->config->get('config_openai_shortennames_model'),
					'prompt' 			=> sprintf($this->config->get('config_openai_shortennames_query_' . $language_code), $name, $this->config->get('config_openai_shortennames_length')),
					'temperature' 		=> (float)$this->config->get('config_openai_shortennames_temperature'),
					'max_tokens' 		=> (int)$this->config->get('config_openai_shortennames_maxtokens'),
					'top_p' 			=> (float)$this->config->get('config_openai_shortennames_top_p'),
					'frequency_penalty' => (float)$this->config->get('config_openai_shortennames_freq_penalty'),
					'presence_penalty' 	=> (float)$this->config->get('config_openai_shortennames_presence_penalty'),
				]);
			}

			if ($text = $this->parseCompletionResponse($response, $this->config->get('config_openai_shortennames_endpoint'))){
				echoLine('[OpenAIAdaptor::shortenName] Old name: ' . $name, 'w');
				echoLine('[OpenAIAdaptor::shortenName] New name: ' . $text, 's');
				return $text;
			} else {
				return $name;
			}
		}

		return $name;
	}

	public function exportName($name, $language_code){
		if ($this->OpenAI && $this->config->get('config_openai_exportnames_query_' . $language_code)){			

			if ($this->config->get('config_openai_exportnames_endpoint') == 'chat'){
				$response = $this->OpenAI->chat([
					'model' 			=> $this->config->get('config_openai_exportnames_model'),
					'messages' 			=> [
						[
							"role" 		=> "user",
							"content" 	=> sprintf($this->config->get('config_openai_exportnames_query_' . $language_code), $name, $this->config->get('config_openai_exportnames_length'))						
						]], 
						'temperature' 		=> (float)$this->config->get('config_openai_exportnames_temperature'),
						'max_tokens' 		=> (int)$this->config->get('config_openai_exportnames_maxtokens'),
						'top_p' 			=> (float)$this->config->get('config_openai_exportnames_top_p'),
						'frequency_penalty' => (float)$this->config->get('config_openai_exportnames_freq_penalty'),
						'presence_penalty' 	=> (float)$this->config->get('config_openai_exportnames_presence_penalty'),
					]);
			} elseif ($this->config->get('config_openai_exportnames_endpoint') == 'completion'){
				$response = $this->OpenAI->completion([
					'model' 			=> $this->config->get('config_openai_exportnames_model'),
					'prompt' 			=> sprintf($this->config->get('config_openai_exportnames_query_' . $language_code), $name, $this->config->get('config_openai_exportnames_length')),
					'temperature' 		=> (float)$this->config->get('config_openai_exportnames_temperature'),
					'max_tokens' 		=> (int)$this->config->get('config_openai_exportnames_maxtokens'),
					'top_p' 			=> (float)$this->config->get('config_openai_exportnames_top_p'),
					'frequency_penalty' => (float)$this->config->get('config_openai_exportnames_freq_penalty'),
					'presence_penalty' 	=> (float)$this->config->get('config_openai_exportnames_presence_penalty'),
				]);
			}

			if ($text = $this->parseCompletionResponse($response, $this->config->get('config_openai_exportnames_endpoint'))){
				echoLine('[OpenAIAdaptor::exportName] Old name: ' . $name, 'w');
				echoLine('[OpenAIAdaptor::exportName] New name: ' . $text, 's');
				return $text;
			} else {
				return $name;
			}
		}

		return $name;
	}

	public function prepareHtmlBlockForTextField(){
	}
}