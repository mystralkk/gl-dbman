<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | Geeklog Dbman Plugin for Geeklog - The Ultimate Weblog                    |
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
 * Dbman plugin DB-specific functions file for MySQL
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

//  Returns table definition used in the current database

function dbman_getTableDef($table_name) {
	$rst = mysql_query("SHOW CREATE TABLE {$table_name};");
	if ($rst !== false) {
		$r = mysql_fetch_array($rst);
		if ($r !== false) {
			$retval = rtrim($r['Create Table']);
			$retval = str_replace(array("\r\n", "\n\r", "\r"), "\n", $retval);
			return $retval . ";";
			exit;
		}
	}
	return NULL;
}

//  Returns table definition extracted from backup file

function dbman_extractTableDefFromBackup($table_name, $filename) {
	
	$retval = array();
	$table_name = dbman_quoteItem($table_name);
	$data = file_get_contents($filename);
	$data = str_replace(array("\r\n", "\r"), "\n", trim($data));
	$data = explode("\n", $data);
	for ($i = 0; $i < count($data); $i ++) {
		if (eregi("^[ \t]*CREATE[ \t]+TABLE[ \t]+" . $table_name, $data[$i], $dummy) > 11) {
			$retval[] = $data[$i];
			$lbrc = substr_count($data[$i], '(');
			$rbrc = substr_count($data[$i], ')');
			while ($lbrc != $rbrc) {
				$i ++;
				$retval[] = $data[$i];
				$lbrc += substr_count($data[$i], '(');
				$rbrc += substr_count($data[$i], ')');
			}
			return implode("\n", $retval);
			exit;
		}
	}

	return NULL;
}

//  Returns an array of table names

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

//  Returns quoted string.

function dbman_quoteString($item) {
	$item = str_replace(array("\r\n", "\n\r", "\r", "\n"), '\\r\\n', $item);
	if (! get_magic_quotes_gpc()) {
		$item = addslashes($item);
	}
	return "'" . $item . "'";
}

//  Checks if the designated table has any BLOB field
//  @parameters:
//    $table_name (string) the table name to check for
//
//  @return (boolean) true = has a BLOB field, false = none

function dbman_isHasBLOBField($table_name) {
	global $dbman_blob_types;
	
	$defs = explode("\n", dbman_getTableDef($table_name));
	foreach ($defs as $def) {
		if (eregi('^[ ]*`(.*)`[ ]+([a-zA-Z0-9_]*).*$', $def, $match) > 0) {
			$column_name = $match[1];
			$column_def  = strtoupper(trim($match[2]));
			if (in_array($column_def, $dbman_blob_types)) {
				return true;
			}
		}
	}
	
	return false;
}

// Returns tables name included in the {$filename} file
// backquote char '`' may be mysql-specific

function dbman_getTableNameFromBackup($filename) {

	$retval = array();

	if (substr($filename, -3) == '.gz') {
		$fh = gzopen($filename, "r");
		if ($fh === false) {
			return $retval;
		} else {
			$f = '';
			while (! gzeof($fh)) {
				$f .= gzread($fh, 10000);
			}
			gzclose($fh);
		}
	} else {
		$f = file_get_contents($filename);
	}
	$f = str_replace(array("\r\n", "\r"), "\n", $f);
	$f = explode("\n", trim($f));
	
	foreach ($f as $line) {
		if (eregi("CREATE[ ]+TABLE[ ]+`(.*)`[ ]*\(", $line, $match) > 0) {
			$retval[] = $match[1];
		}
	}
	
	return $retval;
}

?>