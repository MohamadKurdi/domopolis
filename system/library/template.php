<?php

class Template {
	public $data = [];

	public function getTemplates(){
		$results = [];

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);        
        foreach ($directories as $directory) {
            $results[] = pathinfo($directory,  PATHINFO_FILENAME);
        }

        return $results;
	}

	public function fetch($filename) {
		$file = DIR_TEMPLATE . $filename;

		if (file_exists($file)) {
			extract($this->data);

			ob_start();

			include($file);

			$content = ob_get_clean();

			return $content;
		} else {
			return '';				
		}
	}
}