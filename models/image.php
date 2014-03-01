<?php namespace Models;

use Application\Uri;
use Application\Exceptions\ValidationException;
use DAL;

class Image {
	private $image_id = -1;
	private $image_name;
	private $image_path;
	private $image_date;
	
	public static function getImage($imageId) {
		return DAL\Factory::newQueryBuilder()->table('yabs_image')->where('image_id = ?', [[$imageId, \PDO::PARAM_INT]])->
			query(['image_id', 'image_date', 'image_name', 'image_path'])->fetchObject(__CLASS__); 
	}
	
	public static function getImages($limit, $offset = 0, $query = null, $order = null) {
		$qb = DAL\Factory::newQueryBuilder()->table('yabs_image');
		switch($order) {
			case 'date':
				$qb->orderBy(['image_date DESC']);
				break;
			default:
				$qb->orderBy(['image_name ASC']);
				break;
		}
		if ($query !== null) {
			$qb->where('image_name like ?', ['%' . $query . '%']);
		}
		return $qb->limit($limit, $offset)->query(['image_id', 'image_date', 'image_name', 'image_path'])->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}
	
	public function upload($name, $tmp_name) {
		try {
			$info = getimagesize($tmp_name);
			if ($info === false) {
				throw new ValidationException(_('Unable to determine image type of uploaded file.'));
			}
			
			$name = str_replace('/', '', $name);
			$name = explode('.', $name);
			$name = trim($name[0]);
			if (empty($name)) {
				throw new ValidationException(_('Invalid image name.'));
			}
			
			switch ($info[2]) {
				case IMAGETYPE_GIF:
					$name .= '.gif';
					break;
				case IMAGETYPE_JPEG:
					$name .= '.jpg';
					break;
				case IMAGETYPE_PNG:
					$name .= '.png';
					break;
				default:
					throw new ValidationException(_('Uploaded file is not a valid image type.'));
					break;
			}
			
			$this->image_path = date('Y/m');
			$fullPath = APP_ROOT . '/images/' . $this->image_path . '/';
			
			$this->image_name = $this->checkExists($fullPath, $name);			
			
			if (!file_exists($fullPath)) {
				mkdir($fullPath, 0755, true);
			}			
			
			$this->image_date = time();
			
			$this->save();
			
			move_uploaded_file($tmp_name, $fullPath . $this->image_name);
		} catch (Exception $e) {
			unlink($tmp_name);
			throw $e;
		}
	}
	
	private function checkExists($dir, $name)
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		if(file_exists($dir . $name)) {
			$name = explode('.', $name);
			$name[count($name) - 2] .= $chars[rand(0, strlen($chars) - 1)];
			$name = $this->checkExists($dir, implode('.', $name));
		}
		return $name;
	}

	public function save() {
		if ($this->image_id < 0) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	private function insert() {
		$this->image_id = DAL\Factory::newQueryBuilder()->table('yabs_image')->insert([
				'image_name' => $this->image_name,
				'image_path' => $this->image_path,
				'image_date' => [$this->image_date, \PDO::PARAM_INT]
			]);
	}
	
	public function getId() {
		return $this->image_id;
	}
	
	public function getName() {
		return $this->image_name;
	}
	
	public function getPath() {
		return $this->image_path;
	}
	
	public function getUri() {
		return Uri::getBase() . 'images/' . trim($this->image_path, '/') . '/' . $this->image_name; 
	}
	
	/**
	 * Get URI to the thumbnail image
	 * 
	 * Creates a thumbnail if it doesn't exist.
	 * 
	 * @return string Thumbnail Uri
	 */
	public function getThumbnailUri() {
		return Uri::getBase() . 'images/' . $this->image_path . 'thumbs/' . $this->image_name;
	}
}

?>