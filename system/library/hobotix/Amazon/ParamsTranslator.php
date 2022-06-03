<?
	
	namespace hobotix\Amazon;
	
	class ParamsTranslator
	{
	
		const CLASS_NAME = 'hobotix\\Amazon\\ParamsTranslator';
	
		private $translatorData = [
					
		
		
		
		
		
		
		
		
		
		
		
		
		
		];
		
	
		public function translateParam($name){
			
			if (!empty($this->translatorData[$name])){
				return $this->translatorData[$name];
			} else {
				return $name;
			}

		}
	
	
	
	
	
	
	}