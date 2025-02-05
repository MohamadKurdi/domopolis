<?php

namespace hobotix;


final class MinifyAdaptor {

	const npmPackageLockFile = DIR_ENGINE . 'js/' . 'package-lock.json';

	public static function parseNPM(){
        $npmConfig = loadJsonConfig('npm');

		$npmScripts 			= [];
		if (file_exists(self::npmPackageLockFile)){
			if ($npmDependencies = json_decode(file_get_contents(self::npmPackageLockFile), true)){
				foreach ($npmDependencies['dependencies'] as $npmDependency => $npmInfo){

					$npmPackageDirectory 	= DIR_ENGINE . 'js/node_modules/' . $npmDependency . DIRECTORY_SEPARATOR;
					$npmPackageRelative		= '/js/node_modules/' . $npmDependency . DIRECTORY_SEPARATOR;
					$npmPackageInfoFile 	= $npmPackageDirectory . 'package.json';

					if (file_exists($npmPackageInfoFile)){
						if ($npmPackageInfo = json_decode(file_get_contents($npmPackageInfoFile), true)){
                            if (!empty($npmConfig[$npmPackageInfo['name']])){
                                if (!empty($npmConfig[$npmPackageInfo['name']]['main'])){
                                    $npmPackageInfo['main'] = $npmConfig[$npmPackageInfo['name']]['main'];
                                }
                            }

							if (!empty($npmPackageInfo['main'])){
								if (file_exists($npmPackageDirectory . $npmPackageInfo['main']) && pathinfo($npmPackageInfo['main'],  PATHINFO_EXTENSION) == 'js'){											
									$npmScripts[] = ($npmPackageRelative . $npmPackageInfo['main']);
								}
							}
						}
					}
				}
			}
		}

		return $npmScripts;
	}

	private static function unlinkCacheDir($time) {
		if (is_dir(DIR_MINIFIED . $time)){

			foreach ($files = array_diff(scandir(DIR_MINIFIED . $time), ['.','..']) as $file){
				unlink(DIR_MINIFIED . $time . DIRECTORY_SEPARATOR . $file);
			}

			rmdir(DIR_MINIFIED . $time);
		}
	}

	public static function getCacheTime() {
        if (!is_dir(DIR_MINIFIED)){
            mkdir(DIR_MINIFIED, 0777, true);
        }
        
		foreach (scandir(DIR_MINIFIED) as $subdir) {
			if (ctype_digit($subdir)) {
				return $subdir;
			}
		}

		$time = (string)time();
		mkdir(DIR_MINIFIED . DIRECTORY_SEPARATOR . $time, 0777, true);
		chmod(DIR_MINIFIED . DIRECTORY_SEPARATOR . $time, 0777);

		return $time;
	}


	public static function createFile($input, $type){
		if ($type == 'js'){
			$minifier = new \MatthiasMullie\Minify\JS;
		} elseif ($type == 'css'){
			$minifier = new \MatthiasMullie\Minify\CSS;
		}

		$files = [];
		$times = [];
		foreach ($input as $file){
			$file = DIR_ENGINE . ltrim($file, DIRECTORY_SEPARATOR);
			if (file_exists($file) && $time = filemtime($file)){
				$files[] 		= $file;
				$times[$file] 	= $time;	
			}
		}

		if ($files){
			$cacheTime 	= self::getCacheTime();
			$maxTime	= max($times);

			if ($_SERVER['REMOTE_ADDR'] == ''){

			}

			if ($maxTime > $cacheTime){
				self::unlinkCacheDir($cacheTime);
				$cacheTime 	= self::getCacheTime();
			}

			$code 	  = md5(serialize($files));
			$absolute = DIR_MINIFIED . $cacheTime . DIRECTORY_SEPARATOR . $code . '.' . $type;
			$relative = DIR_MINIFIED_NAME . $cacheTime . DIRECTORY_SEPARATOR . $code . '.' . $type;

			if (!file_exists($absolute)){
				foreach($files as $file){
					$minifier->add($file);
				}

				$minifier->minify($absolute);
				chmod($absolute, 0777);
			}

			return $relative;
		}

		return false;
	}
}