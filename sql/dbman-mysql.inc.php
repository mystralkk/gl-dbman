<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | Geeklog Dbman Plugin 0.3 for Geeklog - The Ultimate Weblog                |
// +---------------------------------------------------------------------------+
// | dbman-mysql.inc   Dbman plugin DB-specific functions file for MySQL       |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2006  mystral_kk - mystral_kk AT ddlinks DOT net            |
// +---------------------------------------------------------------------------+
// | This program is licensed under the terms of the GNU General Public License|
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                      |
// | See the GNU General Public License for more details.                      |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// +---------------------------------------------------------------------------+
//
// $Id$
/* 
 * Dbman plugin DB-specific  functions file for MySQL
 */

//  Data types to be quoted with dbman_quoteString()

$dbman_string_types = array(
	'CHAR', 'VARCHAR', 'DATE', 'TIME', 'DATETIME', 'TINYTEXT', 'TEXT', 'MEDIUMTEXT',
	'LONGTEXT'
);

//  Data types to be identified with BLOB

$dbman_blob_types = array(
	'BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB'
);

//  Returns DB server version

	function dbman_getDBVersion() {
		$rst = mysql_query("SHOW VARIABLES;");
		if ($rst !== false) {
			while (($r = mysql_fetch_array($rst)) !== false) {
				if ($r['Variable_name'] == 'version') {
					return $r['Value'];
					exit;
				}
			}
		}
		
		return 'unavailable';
	}

//  Returns table definition

	function dbman_getTableDef($table_name) {
		$rst = mysql_query("SHOW CREATE TABLE {$table_name};");
		if ($rst !== false) {
			$r = mysql_fetch_array($rst);
			if ($r !== false) {
				$retval = rtrim($r['Create Table']);
				$retval = str_replace(array("\r\n", "\r"), "\n", $retval);
				return $retval . ";";
				exit;
			}
		}
		return NULL;
	}

//  Returns a list of tables

	function dbman_getTableList() {
		global $_DB_name;
		
		$retval = array();
		$rst = mysql_query("SHOW TABLES;");
		if ($rst !== false) {
			while (($r = mysql_fetch_array($rst)) !== false) {
				$table_name = $r['Tables_in_' . $_DB_name];
				$retval[$table_name]['name'] = $table_name;
			}
		}
		return $retval;
	}
	
//  Returns quoted name of database, table and column

	function dbman_quoteItem($item) {
		return '`' . $item . '`';
	}

//  Returns quoted string.  If you use PHP 4 < 4.3.0, replace 'mysql_real_escape_string'
//  with 'mysql_escape_string'.

	function dbman_quoteString($item) {
		$item = str_replace(array("\r\n", "\r", "\n"), '\\r\\n', $item);
		if (!get_magic_quotes_gpc()) {
			$item = addslashes($item);
		}
		return "'" . $item . "'";
	}

?>