<?php namespace Models;

use DAL;
use Application\Exceptions\ValidationException;

class Comment {
	private $comment_id = -1;
	private $comment_author;
	private $comment_mail;
	private $comment_url;
	private $comment_text;
	private $comment_visible;
	private $comment_spam;
	private $comment_ip;
	private $comment_hostname;
	private $comment_date;
	private $entry_id;
	private $entry_uri;
	private $entry_title;
	
	public static function getCommentsForEntry($entry_id) {
		$qb = DAL\Factory::newQueryBuilder();
		$qb->table('yabs_comment');
		$qb->where('entry_id = ? and comment_spam = ? and comment_visible = ?', [
				[$entry_id, \PDO::PARAM_INT],
				[0, \PDO::PARAM_INT],
				[1, \PDO::PARAM_INT]
			]);
		$qb->orderBy(['comment_date ASC']);
		$sth = $qb->query([
				'comment_id',
				'comment_author',
				'comment_mail',
				'comment_url',
				'comment_text',
				'comment_date'
				]);
		return $sth->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}

	private static $columns = array(
			'c.comment_id',
			'c.comment_author',
			'c.comment_mail',
			'c.comment_url',
			'c.comment_text',
			'c.comment_visible',
			'c.comment_spam',
			'c.comment_ip',
			'c.comment_hostname',
			'c.comment_date',
			'e.entry_id',
			'e.entry_uri',
			'e.entry_title'); 
	
	public static function getComments($limit, $offset = 0) {
		$qb = DAL\Factory::newQueryBuilder()->table('yabs_comment c')->limit($limit, $offset);
		$qb->leftJoin('yabs_entry e', ('e.entry_id = c.entry_id'))->orderBy(['c.comment_date DESC']);
		return $qb->query(static::$columns)->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function getComment($commentId) {
		return DAL\Factory::newQueryBuilder()->table('yabs_comment c')->leftJoin('yabs_entry e', 'e.entry_id = c.entry_id')->
			where('c.comment_id = ?', [[$commentId, \PDO::PARAM_INT]])->query(static::$columns)->fetchObject(__CLASS__);
	}
	
	public function save() {
		if ($this->comment_id < 0) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	public function delete() {
		DAL\Factory::newQueryBuilder()->table('yabs_comment')->where('comment_id = ?', [[$this->comment_id, \PDO::PARAM_INT]])->delete();
	}
	
	private function insert() {
		$qb = DAL\Factory::newQueryBuilder();
		$qb->table('yabs_comment');
		$this->comment_id = $qb->insert(array(
				'entry_id' => [$this->entry_id, \PDO::PARAM_INT],
				'comment_author' => $this->comment_author,
				'comment_mail' => $this->comment_mail,
				'comment_url' => $this->comment_url,
				'comment_text' => $this->comment_text,
				'comment_date' => [$this->comment_date, \PDO::PARAM_INT],
				'comment_ip' => [$this->comment_ip, \PDO::PARAM_INT],
				'comment_hostname' => $this->comment_hostname,
				'comment_visible' => [$this->comment_visible, \PDO::PARAM_INT],
				'comment_spam' => [$this->comment_spam, \PDO::PARAM_INT],
			));
	}
	
	private function update() {
		DAL\Factory::newQueryBuilder()->table('yabs_comment')->
			where('comment_id = :comment_id', ['comment_id' => [$this->comment_id, \PDO::PARAM_INT]])->update([
				'comment_author' => $this->comment_author,
				'comment_mail' => $this->comment_mail,
				'comment_url' => $this->comment_url,
				'comment_text' => $this->comment_text,
				'comment_date' => [$this->comment_date, \PDO::PARAM_INT],
				'comment_ip' => [$this->comment_ip, \PDO::PARAM_INT],
				'comment_hostname' => $this->comment_hostname,
				'comment_visible' => [$this->comment_visible, \PDO::PARAM_INT],
				'comment_spam' => [$this->comment_spam, \PDO::PARAM_INT],
			]);
	}
	
	public function getId() {
		return $this->comment_id;
	}
	
	public function getAuthor() {
		return $this->comment_author;
	}
	
	public function SetAuthor($value) {
		if (empty($value)) {
			throw new ValidationException(_('Name is required.'));
		}
		$this->comment_author = $value;
	}
	
	public function getMail() {
		return $this->comment_mail;
	}
	
	public function SetMail($value) {
		if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
			throw new ValidationException(_('Invalid E-Mail address.'));
		}
		$this->comment_mail = $value;
	}
	
	public function getUrl() {
		return $this->comment_url;
	}
	
	public function setUrl($value) {
		if ($value !== null && filter_var($value, FILTER_VALIDATE_URL) === false) {
			throw new ValidationException(_('Invalid URL')); 
		}
		$this->comment_url = $value;
	}
	
	public function getText() {
		return $this->comment_text;
	}
	
	public function SetText($value) {
		if (empty($value)) {
			throw new ValidationException(_('Text is required.'));
		}
		$this->comment_text = $value;
	}
	
	public function isVisible() {
		return $this->getVisible() == true;
	}
	
	public function getVisible() {
		return (bool) $this->comment_visible;
	}
	
	public function setVisible($value) {
		$this->comment_visible = (int) $value;
	}
	
	public function isSpam() {
		return $this->getSpam() == true;
	}
	
	public function getSpam() {
		return (bool) $this->comment_spam;
	}
	
	public function setSpam($value) {
		$this->comment_spam = (int) $value;
	}
	
	public function getIP() {
		return $this->comment_ip;
	}
	
	public function setIP($value) {
		$this->comment_ip = $value;
		$this->comment_hostname = gethostbyaddr($value);
	}
	
	public function getHostname() {
		return $this->comment_hostname;
	}
	
	public function getDate() {
		return $this->comment_date;
	}
	
	public function setDate($value) {
		$this->comment_date = $value;
	}
	
	public function getEntryId() {
		return $this->entry_id;
	}
	
	public function setEntryId($value) {
		$this->entry_id = $value;
	}
	
	public function getEntryUri() {
		return $this->entry_uri;
	}
	
	public function getEntryTitle() {
		return $this->entry_title;
	}
}

?>