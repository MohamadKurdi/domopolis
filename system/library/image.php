<?php
if (extension_loaded('imagick')){
	require_once(dirname(__FILE__) . '/image/imagick.php');
} else {
	require_once(dirname(__FILE__) . '/image/gd.php');
}