<?

namespace hobotix;

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\{QRGdImage, QRCodeOutputException};

use function imagecopyresampled, imagecreatefrompng, imagesx, imagesy, is_file, is_readable;

class QRImageWithLogo extends QRGdImage{

	/**
			* @param string|null $file
			* @param string|null $logo
			*
			* @return string
			* @throws \chillerlan\QRCode\Output\QRCodeOutputException
	**/

	public function dump(string $file = null, string $logo = null):string{
		$this->options->returnResource = true;

		if(!is_file($logo) || !is_readable($logo)){
			throw new QRCodeOutputException('invalid logo');
		}

		parent::dump($file);

		$im = imagecreatefrompng($logo);

		$w = imagesx($im);
		$h = imagesy($im);

		$lw = ($this->options->logoSpaceWidth - 2) * $this->options->scale;
		$lh = ($this->options->logoSpaceHeight - 2) * $this->options->scale;

		$ql = $this->matrix->size() * $this->options->scale;

		imagecopyresampled($this->image, $im, ($ql - $lw) / 2, ($ql - $lh) / 2, 0, 0, $lw, $lh, $w, $h);

		$imageData = $this->dumpImage();

		if($file !== null){
			$this->saveToFile($imageData, $file);
		}

		if($this->options->imageBase64){
			$imageData = $this->base64encode($imageData, 'image/'.$this->options->outputType);
		}

		return $imageData;
	}

}

final class QRCodeExtender {

	public function doQR($codeContents, $qr_file){

		$options = new QROptions([
			'version'             => 5,
			'eccLevel'            => EccLevel::H,
			'imageBase64'         => false,
			'addLogoSpace'        => true,
			'logoSpaceWidth'      => 13,
			'logoSpaceHeight'     => 13,
			'scale'               => 4,
			'imageTransparent'    => false,
			'drawCircularModules' => true,
			'circleRadius'        => 0.45,
			'keepAsSquare'        => [QRMatrix::M_FINDER, QRMatrix::M_FINDER_DOT],
		]);

		$qrCode = new QRCode($options);
		$qrCode->addByteSegment($codeContents);

		$qrOutputInterface = new QRImageWithLogo($options, $qrCode->getMatrix());

		if (file_exists(DIR_IMAGE . 'payment_qr_logo.png')){
			$qrOutputInterface->dump($qr_file, DIR_IMAGE . 'payment_qr_logo.png');
		} else {
			$qrOutputInterface->dump($qr_file, DIR_CATALOG . '../icon/android-chrome-192x192.png');
		}			
	}		
}		