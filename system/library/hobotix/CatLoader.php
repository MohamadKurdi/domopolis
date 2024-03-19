<?php

namespace hobotix;

final class CatLoader {
	private const width 	= 200;
	private const catdir 	= 'cats';

	private static function random_file($dir) {
		$files = glob($dir . '/*.{jpg,png,gif}', GLOB_BRACE); 

		if (!$files) {
			return null;
		}

		$names = [];
		foreach ($files as $file){
			$names[] = pathinfo($file,   PATHINFO_BASENAME);
		}

		$random_index = array_rand($names);
		return $names[$random_index];
	}


	public static function getRandomCatGif($width = null){
		if (!$width){
			$width = self::width;
		}

		$random_cat_file = self::random_file(DIR_IMAGE . self::catdir);
		if ($random_cat_file) {
			return '<img src="' . HTTPS_CATALOG . DIR_IMAGE_NAME . self::catdir . '/' . $random_cat_file . '" style="width:' . $width . 'px;"/>';
		}

		return '<i class="fa fa-spinner fa-spin"></i>';
	}
}