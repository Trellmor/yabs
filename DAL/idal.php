<?php namespace DAL;

interface IDAL {
	function init();
	
	function Select_LatestComments();
	
	function Update_EntryCommentCount($entryId);
}

?>