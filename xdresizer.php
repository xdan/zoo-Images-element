<?php
defined('_JEXEC') or die;
abstract class JHtmlXDResizer {
	static public $extensions = array('png','jpg','jpeg','gif');
	static public function upload($name = 'files') {
		$result = array('res'=>1,'files'=>array(),'error'=>array());
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.path');
		$app = JFactory::getApplication();
		$input = $app->input;
		$_file= $input->files->get('jform');
		$files = $_file[$name];
		if (self::isAssoc($files)) {
			$files = array($files);
		};
		$folder = self::imagepath();

		if (!is_dir($folder)) {
			JFolder::create($folder, 0777);
		}

		foreach ($files as $file) {
			if (!empty($file) and !empty($file['tmp_name']) and $file['size']) {
				if (!empty($file['error'])) {
					$result['error'][] = JText::_('Произошла ошибка ').$file['error'];
					continue;
				}

				$extension = JFile::getExt($file['name']);
				
				$filename = JFile::makeSafe(JFilterOutput::stringURLSafe(basename($file['name'],'.'.$extension)).'.'.$extension);

				if (!in_array($extension, self::$extensions)) {
					$result['error'][] = JText::_('Файл не является изображением');
					continue;
				}

				do{
					$randname = md5($filename.rand(100, 200).rand(100, 200).rand(100, 200)).'.'.$extension;
				}while(file_exists($folder . '/' . $randname));

				if (!JFile::upload($file['tmp_name'], $folder . '/' . $randname)) {
					$result['error'][] = JText::_('Ошибка загрузки файла. Возможно файл слишком большой');
					continue;
				}
				
				
				$result['files'][] = array(
					$filename,
					$randname,
					filesize($folder . '/' . $randname)
				);
			
			}
		}
		//ob_clean();
		return $result;
	}
	static public function getUniqueName($source_file){
		$path = str_replace(JPATH_ROOT,'', dirname($source_file));
		return preg_replace(array('#[\/\\\\]#',), array('-',), $path);
	}
	static public function resizecrop($source_file, $dst_dir, $max_width, $max_height,  $quality = 80){
		$newFile = $dst_dir.$max_width.'x'.$max_height.self::getUniqueName($source_file).'-'.basename($source_file);
		if (file_exists($newFile) and filemtime($newFile)>=filemtime($source_file)) {
			return $newFile;
		}
		
		$imgsize = getimagesize($source_file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];

		switch($mime){
			case 'image/gif':
				$image_create = "imagecreatefromgif";
				$image = "imagegif";
				break;

			case 'image/png':
				$image_create = "imagecreatefrompng";
				$image = "imagepng";
				$quality = 9;
				break;

			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				break;

			default:
				return false;
				break;
		}
		 
		$dst_img = imagecreatetruecolor($max_width, $max_height);
		
		switch ($mime) {
		case 'image/png':
			$background = imagecolorallocate($dst_img, 0, 0, 0);
			imagecolortransparent($dst_img, $background);
			imagealphablending($dst_img, false);
			imagesavealpha($dst_img, true);
			break;

		case 'image/gif':
			$background = imagecolorallocate($dst_img, 0, 0, 0);
			imagecolortransparent($dst_img, $background);
			break;
		}
		
		$src_img = $image_create($source_file);
		 
		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		if($width_new > $width){
			$h_point = (($height - $height_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}
		 
		$image($dst_img, $newFile, $quality);

		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
		return $newFile;
	}
	/**
	 * Режет фото на заданные размеры.
	 *
	 * @param	number	$newWidth		Размер по ширине
	 * @param	number	$newHeight		Размер по высоте
	 * @param	string	$originalFile	Путь до исходного изображения 
	 * @param	string	$targetPath		Путь до результирующего изображения  
	 * 
	 * @return  string Путь до созданного файла
	 */
		
	static public function resize($originalFile, $targetPath, $newWidth, $newHeight,  $quality = 80) {
		$newFile = $targetPath.$newWidth.'x'.$newHeight.self::getUniqueName($originalFile).'-'.basename($originalFile);
		if (file_exists($newFile)  and filemtime($newFile)>=filemtime($originalFile)) {
			return $newFile;
		}

		$info = getimagesize($originalFile);
		$mime = $info['mime'];

		switch ($mime) {
				case 'image/jpeg':
						$image_create_func = 'imagecreatefromjpeg';
						$image_save_func = 'imagejpeg';
						$new_image_ext = 'jpg';
						break;

				case 'image/png':
						$image_create_func = 'imagecreatefrompng';
						$image_save_func = 'imagepng';
						$new_image_ext = 'png';
						$quality = 9;
						break;

				case 'image/gif':
						$image_create_func = 'imagecreatefromgif';
						$image_save_func = 'imagegif';
						$new_image_ext = 'gif';
						break;

				default: 
						throw Exception('Unknown image type.');
		}

		$img = $image_create_func($originalFile);
		list($width, $height) = getimagesize($originalFile);

		$tmp = imagecreatetruecolor($newWidth, $newHeight);
		
		switch ($mime) {
		case 'image/png':
			$background = imagecolorallocate($tmp, 0, 0, 0);
			imagecolortransparent($tmp, $background);
			imagealphablending($tmp, false);
			imagesavealpha($tmp, true);
			break;

		case 'image/gif':
			$background = imagecolorallocate($tmp, 0, 0, 0);
			imagecolortransparent($tmp, $background);
			break;
		}
		
		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		
		$image_save_func($tmp, $newFile, $quality);
		return $newFile;
	}
	static public function thumb($img, $width, $height = 0, $mode = 0, $quality = 80) {
		$path = realpath(JPATH_ROOT . '/' . $img);
		if (!trim($path) or !is_file($path)) {
			$path = realpath(JPATH_ROOT .'/images/error.gif');
		}
		
		list($imgWidth, $imgHeight) = getimagesize($path);
		jimport('joomla.filesystem.folder');
		if (!is_dir(JPATH_ROOT .'/media/thumbs/')) {
			JFolder::create(JPATH_ROOT .'/media/thumbs/', 0777);
		}
		switch ($mode) {
		// по большей из сторон под размер заданного квадрата
		case 0:
			$height = $width;
			if ($imgWidth > $imgHeight) {
				$ratio = $width/$imgWidth;
				$height = (int)($ratio*$imgHeight);
			} else {
				$ratio = $height/$imgHeight;
				$width = (int)($ratio*$imgWidth);
			}
			if ($imgWidth>$width or $imgHeight>$height) {
				$path = self::resize($path, realpath(JPATH_ROOT .'/media/thumbs/').'/', $width, $height, $quality);
			}
		break;
		case 1:
			if ($imgWidth>$width or $imgHeight>$height) {
				$path = self::resizecrop($path, realpath(JPATH_ROOT .'/media/thumbs/').'/', $width, $height, $quality);
			}
		break;
		// по высоте
		case 2:
			if ($imgHeight > $height) {
				$ratio = $height/$imgHeight;
				$width = (int)($ratio*$imgWidth);
				$path = self::resize($path, realpath(JPATH_ROOT .'/media/thumbs/').'/', $width, $height, $quality);
			}
		break;
		// по ширине
		case 3:
			if ($imgWidth > $width) {
				$ratio = $width/$imgWidth;
				$height = (int)($ratio*$imgHeight);
				$path = self::resize($path, realpath(JPATH_ROOT .'/media/thumbs/').'/', $width, $height, $quality);
			}
		break;
		}
		
		return preg_replace('#[/]+#','/', str_replace(array(JPATH_ROOT . DIRECTORY_SEPARATOR, '\\'), array('', '/'), $path));
	}

}