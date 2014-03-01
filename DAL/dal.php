<?php namespace DAL;

/**
 * yabs -  Yet another blog system
 * Copyright (C) 2014 Daniel Triendl <daniel@pew.cc>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
	
	public function Update_EntryCommentCount($entryId) {
		$sql = "UPDATE yabs_entry SET
					entry_commentcount = (
						SELECT
							count(*)
						FROM yabs_comment
						WHERE
							comment_visible = 1 and
							comment_spam = 0 and
							entry_id = :entry_id
					)
				WHERE
					entry_id = :entry_id";
		$sth = Registry::getInstance()->db->prepare($sql);
		$sth->bindValue('entry_id', $entryId, \PDO::PARAM_INT);
		$sth->execute();
	}
}


?>