<?

namespace hobotix;


final class OpenAIAdaptor
{

	private $db		= null;
	private $config	= null;
	
	public 	$OpenAI = null;


	public function __construct($registry){
		$this->config 	= $registry->get('config');
		$this->db 		= $registry->get('db');

		if ($this->config->get('config_openai_enable') && $this->config->get('config_openai_api_key')){
			$this->OpenAI   = new \Orhanerday\OpenAi\OpenAi($this->config->get('config_openai_api_key'));
		}
	}

	private function parseCompletionResponse($response){
		if ($json = json_decode($response, true)){
			if (empty($json['error'])){
				
				if (!empty($json['choices']) && !empty($json['choices'][0]) && !empty($json['choices'][0]['text'])){
					return trim($json['choices'][0]['text']);
				}

			} else {
				echoLine('[OpenAIAdaptor::parseCompletionError] ' . $json['error']['message'], 'e');
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
			$response = $this->OpenAI->completion([
				'model' 			=> $this->config->get('config_openai_category_alternatenames_model'),
				'prompt' 			=> $request,
				'temperature' 		=> (float)$this->config->get('config_openai_category_alternatenames_temperature'),
				'max_tokens' 		=> (int)$this->config->get('config_openai_category_alternatenames_maxtokens'),
				'top_p' 			=> (float)$this->config->get('config_openai_category_alternatenames_top_p'),
				'frequency_penalty' => (float)$this->config->get('config_openai_category_alternatenames_freq_penalty'),
				'presence_penalty' 	=> (float)$this->config->get('config_openai_category_alternatenames_presence_penalty'),
			]);

			if ($text = $this->parseCompletionResponse($response)){
				return $this->removeListFromResponse($text);
			}
		}

		return $name;
	}

	public function categoryDescription($request){
		if ($this->OpenAI && $this->config->get('config_openai_enable_category_descriptions')){
			$response = $this->OpenAI->completion([
				'model' 			=> $this->config->get('config_openai_category_descriptions_model'),
				'prompt' 			=> $request,
				'temperature' 		=> (float)$this->config->get('config_openai_category_descriptions_temperature'),
				'max_tokens' 		=> (int)$this->config->get('config_openai_category_descriptions_maxtokens'),
				'top_p' 			=> (float)$this->config->get('config_openai_category_descriptions_top_p'),
				'frequency_penalty' => (float)$this->config->get('config_openai_category_descriptions_freq_penalty'),
				'presence_penalty' 	=> (float)$this->config->get('config_openai_category_descriptions_presence_penalty'),
			]);

			if ($text = $this->parseCompletionResponse($response)){
				return $text;
			}
		}

		return $name;
	}

	public function shortenName($name, $language_code){
		if ($this->OpenAI && $this->config->get('config_openai_shortennames_query_' . $language_code)){
			$response = $this->OpenAI->completion([
				'model' 			=> $this->config->get('config_openai_shortennames_model'),
				'prompt' 			=> sprintf($this->config->get('config_openai_shortennames_query_' . $language_code), $name, $this->config->get('config_openai_shortennames_length')),
				'temperature' 		=> (float)$this->config->get('config_openai_shortennames_temperature'),
				'max_tokens' 		=> (int)$this->config->get('config_openai_shortennames_maxtokens'),
				'top_p' 			=> (float)$this->config->get('config_openai_shortennames_top_p'),
				'frequency_penalty' => (float)$this->config->get('config_openai_shortennames_freq_penalty'),
				'presence_penalty' 	=> (float)$this->config->get('config_openai_shortennames_presence_penalty'),
			]);

			if ($text = $this->parseCompletionResponse($response)){
				echoLine('[OpenAIAdaptor::shortenName] Old name: ' . $name, 'w');
				echoLine('[OpenAIAdaptor::shortenName] New name: ' . $text, 's');
				return $text;
			}
		}

		return $name;
	}

	public function exportName($name, $language_code){
		if ($this->OpenAI && $this->config->get('config_openai_exportnames_query_' . $language_code)){
			$response = $this->OpenAI->completion([
				'model' 			=> $this->config->get('config_openai_exportnames_model'),
				'prompt' 			=> sprintf($this->config->get('config_openai_exportnames_query_' . $language_code), $name, $this->config->get('config_openai_exportnames_length')),
				'temperature' 		=> (float)$this->config->get('config_openai_exportnames_temperature'),
				'max_tokens' 		=> (int)$this->config->get('config_openai_exportnames_maxtokens'),
				'top_p' 			=> (float)$this->config->get('config_openai_exportnames_top_p'),
				'frequency_penalty' => (float)$this->config->get('config_openai_exportnames_freq_penalty'),
				'presence_penalty' 	=> (float)$this->config->get('config_openai_exportnames_presence_penalty'),
			]);

			if ($text = $this->parseCompletionResponse($response)){
				echoLine('[OpenAIAdaptor::exportName] Old name: ' . $name, 'w');
				echoLine('[OpenAIAdaptor::exportName] New name: ' . $text, 's');
				return $text;
			}
		}

		return $name;
	}

	public function prepareHtmlBlockForTextField(){
	}
}