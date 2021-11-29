<?php  namespace App\Http\Classes;

class uploadImage {

	private $filename;
	private $pasta;

	public function upload($filename,$nomeAnterior,$nomeNovo,$pasta="",$width="",$height="") {
		$this->filename = $filename;
		$this->pasta = $pasta;

		if($filename != "" && $nomeNovo != "" && $pasta != ""){
			$this->checkEsxtension();
			if($nomeAnterior != ""){ $this->deleteImages($nomeAnterior); }
			$this->resizeImage($width,$height,$nomeNovo);
		}
		return $nomeNovo;
	}

	private function deleteImages($nomeAnterior) {
		$fh = fopen($this->pasta.$nomeAnterior, 'w') or die("can't open file");
		fclose($fh);
		unlink($this->pasta.$nomeAnterior);
	}

	private function checkEsxtension() {
		$extensao = strtolower($this->filename->getClientOriginalExtension());
		$extensoesSuportadas = ['jpg','jpeg','png','gif','ico'];
		if(!in_array($extensao, $extensoesSuportadas)){ return exit(trans('backoffice.Unsupportedextension').' '.$this->filename->getClientOriginalExtension()); }
	}

	private function resizeImage($max_width="500",$max_height="500",$nomeNovo) {
		//$this->filename->resize($max_width,$max_height);
		//$file->move($destinationPath, $this->pasta.$nomeNovo.".".$nomeImagemTmp->getClientOriginalExtension());

		$sizes = getimagesize($this->filename->getPathName());
		$old_width  = $sizes[0];
		$old_height = $sizes[1];
		$scale      = min($max_width/$old_width, $max_height/$old_height);
		if(($old_width > $max_width) || ($old_height > $max_height)){
			$new_width  = ceil($scale*$old_width);
			$new_height = ceil($scale*$old_height);
		}else{
			$new_width  = $old_width;
			$new_height = $old_height;
		}

		$extensao = strtolower($this->filename->getClientOriginalExtension());
		switch($extensao){
			case 'jpg':
			case 'jpeg':
				$imageCreated = imagecreatefromjpeg($this->filename->getPathName());
				$new = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($new, $imageCreated, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
				imagedestroy($imageCreated);
				$destfile = $this->pasta.$nomeNovo;
				imagejpeg($new, $destfile, 100);
				break;
			case 'png':
				$imageCreated = imagecreatefrompng($this->filename->getPathName());
				$new = imagecreatetruecolor($new_width, $new_height);
				imagealphablending($new, false);
				imagesavealpha($new,true);
				$transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
				imagefilledrectangle($new, 0, 0, $new_width, $new_height, $transparent);
				imagecopyresampled($new, $imageCreated, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
				imagedestroy($imageCreated);
				$destfile = $this->pasta.$nomeNovo;
				imagepng($new, $destfile, 0);
				break;
			case 'gif':
				$imageCreated = imagecreatefromgif($this->filename->getPathName());
				$new = imagecreatetruecolor($new_width, $new_height);
				$transparent = imagecolorallocate($new, 255, 255, 255);
				imagefill($new, 0, 0, $transparent);
				imagecolortransparent($new, $transparent);
				imagecopyresampled($new, $imageCreated, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
				imagedestroy($imageCreated);
				$destfile = $this->pasta.$nomeNovo;
				imagetruecolortopalette($new, true, 256);
				imagegif($new, $destfile, 0);
				break;
			default:
				move_uploaded_file($this->filename->getPathName(), $this->pasta.$nomeNovo);
		}
	}
}