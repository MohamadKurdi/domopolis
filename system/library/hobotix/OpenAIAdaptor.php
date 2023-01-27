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
				echoLine('[OpenAIAdaptor::shortenName] Old name: ' . $name, 'w');
				echoLine('[OpenAIAdaptor::shortenName] New name: ' . $text, 's');
				return $text;
			}
		}

		return $name;
	}

	public function prepareHtmlBlockForTextField(){
	}
}