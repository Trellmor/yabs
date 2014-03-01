<?php namespace DAL;

use Application\Registry;

abstract class DAL {
	public function init() {}
	
	public function Select_LatestComments() {
		$sql = "SELECT
					c.comment_id,
					c.comment_author,
					c.comment_mail,
					c.comment_url,
					c.comment_text,
					c.comment_visible,
					c.comment_spam,
					c.comment_ip,
					c.comment_hostname,
					c.comment_date,
					e.entry_id,
					e.entry_uri,
					e.entry_title
				FROM (
					SELECT
						entry_id,
						MAX(comment_date) as maxdate
					FROM
						yabs_comment
					WHERE
						comment_spam = 0 and
						comment_visible = 1
					GROUP BY
						entry_id
				) as x
				INNER JOIN yabs_comment c
				ON (
					c.entry_id = x.entry_id and
					c.comment_date = x.maxdate
				)
				INNER JOIN yabs_entry as e
				ON (e.entry_id = x.entry_id)
				ORDER BY
					c.comment_date DESC
				LIMIT 0, 5";
		return Registry::getInstance()->db->query($sql);
	}
}


?>