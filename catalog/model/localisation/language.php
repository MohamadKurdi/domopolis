<?php
	class ModelLocalisationLanguage extends Model {
		public function getLanguage($language_id) {
			if (!empty($this->registry->get('languages_id_mapping')[$language_id])){
				return $this->registry->get('languages_id_mapping')[$language_id];
			}

			return false;
		}
		
		public function getLanguageByCode($code) {
			if (!empty($this->registry->get('languages')[$code])){
				return $this->registry->get('languages')[$code]['language_id'];
			}

			return false;
		}
		
		public function getFullLanguageByCode($code) {
			if (!empty($this->registry->get('languages')[$code])){
				return $this->registry->get('languages')[$code];
			}

			return false;
		}
				
		public function getLanguages() {
			return $this->registry->get('languages');
		}
	}