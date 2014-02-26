<?php namespace Models;

use Application\Exceptions\ValidationException;

use Application\Registry;

use DAL;

class Entry {	
	private $entry_id = -1;
	private $entry_title;
	private $entry_teaser;
	private $entry_content = NULL;
	private $entry_date;
	private $entry_uri;
	private $user_id;
	private $user_name;
	private $entry_visible;
	private $category_id;
	private $category_name;
	
	protected static function getAllEntries() {
		$qb = DAL\Factory::newQueryBuilder();
		$qb->table('yabs_entry e');
		$qb->leftJoin('yabs_user u', 'u.user_id = e.user_id');
		$qb->leftJoin('yabs_category c', 'c.category_id = e.category_id');
		return $qb;
	}
	
	protected static function getAllVisibleEntries() {
		$qb = static::getAllEntries();		
		$qb->where('e.entry_date < ? and e.entry_visible = ?', [[time(), \PDO::PARAM_INT], [1, \PDO::PARAM_INT]]);
		return $qb;
	}
	
	public static function getVisibleEntries($limit, $offset = 0) {
		$qb = static::getAllVisibleEntries();		
		$qb->limit($limit, $offset);
		$qb->orderBy(['e.entry_date DESC']);
		$sth = $qb->query([ 
				'e.entry_title', 
				'e.entry_teaser', 
				'e.entry_content',
				'e.entry_date', 
				'e.entry_uri', 
				'u.user_name',
				'c.category_name'
			]);
		return $sth->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function getEntries($limit, $offset = 0) {
		$qb = static::getAllEntries();
		$qb->limit($limit, $offset)->orderBy(['e.entry_id DESC']);
		$sth = $qb->query([
				'e.entry_id', 
				'e.entry_title', 
				'e.entry_teaser', 
				'e.entry_content',
				'e.entry_date', 
				'e.entry_uri', 
				'e.entry_visible',
				'u.user_id',
				'u.user_name',
				'c.category_id',
				'c.category_name'
			]);
		return $sth->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function getEntriesForUser($userId, $limit, $offset = 0) {
		$qb = static::getAllEntries();
		$qb->where('user_id = ?', [[$userId, \PDO::PARAM_INT]])->limit($limit, $offset)->orderBy(['e.entry_date DESC']);
		$sth = $qb->query([
				'e.entry_id', 
				'e.entry_title', 
				'e.entry_teaser', 
				'e.entry_content',
				'e.entry_date', 
				'e.entry_uri', 
				'e.entry_visible',
				'u.user_id',
				'u.user_name',
				'c.category_id',
				'c.category_name'
			]);
		return $sth->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function getEntry($entryId) {
		$qb = static::getAllEntries();
		$qb->where('e.entry_id = ?', [[$entryId, \PDO::PARAM_INT]]);
		$sth = $qb->query([
				'e.entry_id', 
				'e.entry_title', 
				'e.entry_teaser', 
				'e.entry_content', 
				'e.entry_date', 
				'e.entry_uri', 
				'e.entry_visible',
				'u.user_id',
				'u.user_name',
				'c.category_id',
				'c.category_name'
			]);
		return $sth->fetchObject(__CLASS__);
	}
	
	public static function getEntryByUri($uri) {
		$qb = static::getAllVisibleEntries();
		$qb->where('e.entry_uri = ?', [$uri]);
		$sth = $qb->query([
				'e.entry_id',
				'e.entry_title', 
				'e.entry_teaser', 
				'e.entry_content', 
				'e.entry_date', 
				'e.entry_uri',
				'u.user_name',
				'c.category_name'
			]);
		return $sth->fetchObject(__CLASS__);
	}
	
	public function delete() {
		Registry::getInstance()->db->beginTransaction();
		try {
			$qb = DAL\Factory::newQueryBuilder();
			$qb->table('yabs_comment')->where('entry_id = ?', [[$this->entry_id, \PDO::PARAM_INT]])->delete();
			
			$qb = DAL\Factory::newQueryBuilder();			
			$qb->table('yabs_entry')->where('entry_id = ?', [[$this->entry_id, \PDO::PARAM_INT]])->delete();
						
			Registry::getInstance()->db->commit();
		} catch (\PDOException $e) {
			Registry::getInstance()->db->rollBack();
			throw $e;
		}
	}
	
	public function save() {
		if ($this->getId() < 0) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	private function update() {
		$qb = DAL\Factory::newQueryBuilder();
		$qb->table('yabs_entry')->where('entry_id = :entry_id', ['entry_id' => [$this->entry_id, \PDO::PARAM_INT]]);
		$qb->update([
				'entry_title' => $this->entry_title,
				'entry_teaser' => $this->entry_teaser,
				'entry_content' => $this->entry_content,
				'entry_visible' => [$this->entry_visible, \PDO::PARAM_INT],
				'entry_uri' => $this->entry_uri,
				'entry_date' => [$this->entry_date, \PDO::PARAM_INT],
				'category_id' => [$this->category_id, \PDO::PARAM_INT]
				]);
	}
	
	private function insert() {		
		$qb = DAL\Factory::newQueryBuilder();
		Registry::getInstance()->db->beginTransaction();
		try {		
			$qb->table('yabs_entry')->insert([
					'entry_title' => $this->entry_title,
					'entry_teaser' => $this->entry_teaser,
					'entry_content' => $this->entry_content,
					'entry_visible' => [$this->entry_visible, \PDO::PARAM_INT],
					'entry_uri' => $this->entry_uri,
					'entry_date' => [$this->entry_date, \PDO::PARAM_INT],
					'category_id' => [$this->category_id, \PDO::PARAM_INT],
					'user_id' => [$this->user_id, \PDO::PARAM_INT]
					]);
			$this->entry_id = Registry::getInstance()->db->lastInsertId();
						
			Registry::getInstance()->db->commit();
		} catch (\PDOException $e) {
			Registry::getInstance()->db->rollBack();
			throw $e;
		}
	}
	
	public function getId() {
		return $this->entry_id;
	}
	
	public function getTitle() {
		return $this->entry_title;
	}
	
	public function setTitle($value) {
		$value = substr($value, 0, 255);
		$this->entry_title = $value;
	}
	
	public function hasTeaser() {
		return !empty($this->entry_teaser);
	}
	
	public function getTeaser() {
		return $this->entry_teaser;
	}
	
	public function setTeaser($value) {
		$this->entry_teaser = $value;
	}
	
	public function getContent() {
		return $this->entry_content;
	}
	
	public function setContent($value) {
		$this->entry_content = $value;
	}
	
	public function getUri() {	
		return $this->entry_uri;
	}
	
	public function setUri($value) {
		if (empty($value)) {
			$value = $this->entry_title;
		}
		
		if (empty($value)) {
			throw new ValidationException(_('Entry URI cannot be empty.'));
		}
		
		$value = substr($value, 0, 36);
		
		$qb = DAL\Factory::newQueryBuilder();
		$qb->table('yabs_entry')->where('entry_id != ? and entry_uri = ?', [[$this->entry_id, \PDO::PARAM_INT], $value]);
		if ($qb->query(['count(*)'])->fetchColumn(0) > 0) {
			throw new ValidationException(_('Entry URI already in use.'));
		}
		
		$this->entry_uri = $value;
	}
	
	public function getDate() {
		return $this->entry_date;
	}
	
	public function setDate($value) {
		$this->entry_date = $value;
	}
	
	public function isVisible() {
		return (bool) $this->entry_visible;
	}
	
	public function setVisible($value) {
		$this->entry_visible = (int) $value;
	}
	
	public function getUserId() {
		return $this->user_id;
	}
	
	public function setUserId($value) {
		$this->user_id = $value;
	}
	
	public function getUserName() {
		return $this->user_name;
	}
	
	public function getCategoryId() {
		return $this->category_id;
	}
	
	public function setCategoryId($value) {
		$this->category_id = $value;
	}
	
	public function getCategoryName() {
		return $this->category_name;
	}
}

?>