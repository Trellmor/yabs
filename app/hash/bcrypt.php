<?php namespace Application\hash;

class BCrypt extends Hash {
	private $rounds = 12;
		
	public function __construct($rounds = 12) {
		$this->rounds = 12;
	}
	
	public function getRounds() {
		return $this->rounds;
	}
	
	public function setRounds($rounds) {
		$this->rounds = $rounds;
	}
	
	protected function getSaltLength() {
		return 16;
	}
	
	protected function getResultLength() {
		return 60;
	}
	
	protected function getIdentifier() {
		return sprintf("$2a$%02d$", $this->rounds);
	}
}

?>