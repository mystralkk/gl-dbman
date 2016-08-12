<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | Dbman Plugin for Geeklog - The Ultimate Weblog                            |
// +---------------------------------------------------------------------------+
// | functions.inc   Dbman plugin general functions file                       |
// +---------------------------------------------------------------------------+
// | Constructed with the Universal Plugin                                     |
// | Copyright (C) 2006  mystral-kk - geeklog AT mystral-kk DOT net            |
// +---------------------------------------------------------------------------+
// | This program is licensed under the terms of the GNU General Public License|
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
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
// $Id: functions.inc,v 1.2 2006/07/03 04:43:07 kenji Exp $
/* 
 * Dbman plugin general functions file
 */

global $_DB_dbms;				// needs to be declared while UnInstallation

if ( !defined( 'LB' ) ) {
	define( 'LB', "\n" );
}

$langfile = $_CONF['path'] . 'plugins/dbman/language/' . $_CONF['language'] . '.php';
if (file_exists ($langfile)) {
    include_once ($langfile);
} else {
	include_once ($_CONF['path'] . 'plugins/dbman/language/english.php');
}

require_once ($_CONF['path'] . 'plugins/dbman/config.php');

//	Include DB-specific functions

if (strcasecmp($_DB_dbms, 'mysql') == 0) {
	include_once('sql/dbman-mysql.inc.php');
} else {
	COM_errorLog("Database type '{$_DB_dbms}' is not supported in Dbman.", 1);
	exit;
}

$baseurl = $_CONF['site_admin_url'] . '/plugins/dbman';
$dbman_menu = array(
	'admin' => array(
				'url'  => $_CONF['site_admin_url'],
				'text' => $LANG_ADMIN['admin_home']
	),
	'backup' => array(
				'url'  => $baseurl . '/index.php?cmd=backup_option',
				'text' => $LANG_DBMAN['menu_backup']
	),
	'list' => array(
				'url'  => $baseurl . '/index.php',
				'text' => $LANG_DBMAN['menu_list']
	),
	'restore' => array(
				'url'  => $baseurl . '/index.php?cmd=restore_select',
				'text' => $LANG_DBMAN['menu_restore']
	),
	'console' => array(
				'url' => $baseurl . '/index.php?cmd=console',
				'text' => $LANG_DBMAN['menu_console']
	)
);

/*
 *  DBMAN_timer class -- prevents the script from running out of time
 */

class DBMAN_timer {

	var $last_time;		// Last time when this timer was reset
	
	function __construct() {
		$this->DBMAN_timer();
	}
	
	function __destruct() {
		// Do nothing
	}
	
	function DBMAN_timer() {
		$this->last_time = time();
	}
	
	function update() {
		$current_time = time();
		if ($current_time > $this->last_time + 25) {
			header('X-dbmanPing: Pong');	// Borrowed from PHPMyAdmin/export.php
			$this->last_time = $current_time;
		}
	}
}

// +---------------------------------------------------------------------------+
// | Geeklog Plugin API Implementations                                        |
// +---------------------------------------------------------------------------+

// ============================================================================
//		MAIN
// ============================================================================

/*
 * Returns the items for this plugin that should appear on the main menu
 *
 * NOTE: this MUST return the url/value pairs in the following format
 * $<arrayname>[<label>] = <url>
 */

/*
function plugin_getmenuitems_dbman() {
    global $_CONF, $_DBMAN_CONF, $LANG_DBMAN, $baseurl;

    $menulabel = 'Dbman';
    $menuurl   = $baseurl . '/index.php';

    $menuitems = array();
	if (SEC_hasRights('dbman.edit')) {
    	$menuitems["$menulabel"] = $menuurl;
		return $menuitems;
	} else {
		return false;
	}
}
*/

/*
 * Geeklog is checking to see if this plugin supports comments, tell it no.
 *
 * NOTE: to support comments you must use the same date/time based ID for your
 * widget.  In other words, to make primary keys for your plugin you should call
 * makesid().  Comments are keyed off of that...it is a limitation on how Geeklog
 * does comments.
 */

function plugin_commentsupport_dbman() {
	// dbman will not use comments
	return false;
}

/*
 * This will put an option for the plugin in the command and control block on
 * moderation.php
 *
 * Add the plugin name, icon and link to the command and control block in moderation.php
 *
 * @return   array   Array containing (plugin name, admin url, url of plugin icon)
 *
 */

function plugin_cclabel_dbman() {
	global $_CONF, $baseurl;

	return array('Dbman', $baseurl . '/index.php', $baseurl . '/images/dbman.png');
}

/*
 * Returns the administrative option for this plugin
 *
 * Adds the plugin to the Admin menu
 *
 * Universal Plugin:  Modify/Uncomment use it
 *
 * @return array Array containing (plugin name, plugin admin url,
 *	 # of items in plugin or '')
 *
 */
 
function plugin_getadminoption_dbman() {
	global $_CONF, $baseurl;

	if (SEC_hasRights('dbman.edit')) {
		return array('Dbman', $baseurl . '/index.php', '');
	}
}

function plugin_upgrade_dbman() {
	global $_CONF, $_TABLES, $_DBMAN_CONF;
	
	$result = DB_query("SELECT pi_version FROM {$_TABLES['plugins']} WHERE (pi_name = 'dbman')");
	$A = DB_fetchArray($result);
	$pi_version = $A['pi_version'];
	
// update plugin info
	$result = DB_query("UPDATE {$_TABLES['plugins']} SET pi_version = \"{$_DBMAN_CONF['version']}\" WHERE (pi_name = 'dbman')");
	COM_errorLog("Dbman plugin was successfully updated to version {$_DBMAN_CONF['version']}.");
	
	return true;
}

/*
 * Removes the datastructures for this plugin from the Geeklog database.
 * This routine will get called from the Plugin install program if user select
 * De-Install or if Delete is used in the Plugin Editor.
 * The Plugin Installer will also call this routine upon and install error to remove
 * anything it has created.
 * The Plugin installer will pass the optional parameter which will then double check
 * that plugin has first been disabled. 
 * 
 * For this plugin, this routine will also remove the Block definition.
 * 
 * Returns True if all Plugin related data is removed without error
 *
 * @param  string  $installCheck  Default is blank but if set, check if plugin
 * is disabled first
 * 
 * @return   boolean True if successful false otherwise
 */  

function plugin_uninstall_dbman($installCheck = '') {
    global $_TABLES, $LANG_DBMAN;

    $pi_name  = 'dbman';
    $NEW_FEATURES = array('dbman.edit');
    
    // Check and see if plugin is still enabled - if so display warning and exit
    if ($installCheck != '' && DB_getItem($_TABLES['plugins'], 'pi_enabled', 'pi_name = "' .$pi_name . '"')) {
	    COM_errorLog("Plugin is installed and enabled.  Disable first if you want to de-install it", 1);
			$display .= COM_startBlock($LANG_DBMAN['warning']);
			$display .= $LANG_DBMAN['enabled'];
			$display .= COM_endBlock();
			echo $display;
			return false;
			exit;
		}
    	
		// Ok to proceed and delete plugin

    // Unregister the plugin with Geeklog
    COM_errorLog('Attempting to unregister the Dbman plugin from Geeklog',1);
    DB_query("DELETE FROM {$_TABLES['plugins']} WHERE pi_name = '{$pi_name}'",1);
    
    // Remove all the associated features -- access rights
    foreach ($NEW_FEATURES as $feature) {
		COM_errorLog("Removing '$feature' feature and rights to it", 1);
		$feat_id = DB_getItem($_TABLES['features'], 'ft_id', "ft_name = '$feature'");
		DB_query("DELETE FROM {$_TABLES['access']} WHERE acc_ft_id = $feat_id",1);
		DB_query("DELETE FROM {$_TABLES['features']} WHERE ft_id = $feat_id",1);
    }
		
	// Get group_id, if any
	$rst = DB_query("SELECT value FROM {$_TABLES['vars']} WHERE name = '{$pi_name}_gid'", 1);
	if ($rst !== false) {
		$ar = DB_fetcharray($rst);
		$group_id = $ar[0];
		DB_query("DELETE FROM {$_TABLES['vars']} WHERE name = '{$pi_name}_gid'", 1);
		DB_query("DELETE FROM {$_TABLES['group_assignments']} WHERE (ug_main_grp_id = $group_id) AND (ug_grp_id = 1)", 1);
		DB_query("DELETE FROM {$_TABLES['groups']} WHERE grp_id = $group_id", 1);
	}
    COM_errorLog('Succesfully uninstalled the Dbman Plugin!', 1);
    return true;
}

/*
 * Returns the version for this plugin
 *
 * @return string VersionNo
 */

function plugin_chkVersion_dbman() {
	global $_DBMAN_CONF;
	
	return $_DBMAN_CONF['version'];
}

/*
 *  Helper Functions
 */

// Check if the {$filename} file is actually created

function DBMAN_isCreated($filename) {
	
	clearstatcache();
	$retval = file_exists($filename);
	if ($retval) {
		clearstatcache();
		$retval = (@filesize($filename) > 0);
	}
	
	return $retval;
}

// Returns if a table should be excepted in backupping

function DBMAN_isBackupExcept($table_name) {
	global $_DBMAN_CONF;
	
	foreach ($_DBMAN_CONF['backup_except'] as $pattern) {
		if (preg_match($pattern, $table_name) == 1) {
			return true;
		}
	}
	
	return false;
}


function DBMAN_compareBackupFiles ($pFileA, $pFileB) {
	global $_CONF;

	$lFiletimeA = filemtime($_CONF['backup_path'] . $pFileA);
	$lFiletimeB = filemtime($_CONF['backup_path'] . $pFileB);
	if ($lFiletimeA == $lFiletimeB) {
		return 0;
	}

	return ($lFiletimeA > $lFiletimeB) ? -1 : 1;
}

/*
 * adopted from database.php.  To avoid collision, renamed as DBMAN_listBackupFiles
 */

function DBMAN_listBackupFiles() {
	global $_CONF;
	
	$backups = array();
	$fd = opendir($_CONF['backup_path']);
	while ((false !== ($file = @readdir($fd)))) {
		if (preg_match ('/\.sql$/i', $file) || preg_match ('/\.sql\.gz$/i', $file)) {
			$backups[] = $file;
		}
	}
	
	// AS, 2004-03-29 - Sort backup files by date, newest first.
	// Order given by 'readdir' might not be correct.
	usort($backups, 'DBMAN_compareBackupFiles');
//	$backups = array_slice ($backups, 0, 10);
	return $backups;
}

/*
 * Show backup options
 *
 * @return (string) backup options in HTML format
 */

function DBMAN_backupOptions($add_drop_table, $backup_blob, $compress_data,
	$download_as_file) {

	global $_CONF, $LANG_DBMAN, $dbman_menu, $baseurl;
	require_once($_CONF['path_system'] . 'lib-admin.php');

// checks if DB has any BLOB field

	$has_blob = false;
	$table_names = dbman_getTableList();
	foreach ($table_names as $table_name) {
		$has_blob = $has_blob || dbman_isHasBLOBField($table_name);
	}
	$retval = '<form action="./index.php" method="post">';
	$menu_arr = array (
		$dbman_menu['admin'],
		$dbman_menu['list'],
		$dbman_menu['restore'],
		$dbman_menu['console']
	);

	$header_arr = array(      # dislay 'text' and use table field 'option'
		array('text' => $LANG_DBMAN['option'], 'field' => 'option')
	);

	$str_add_drop_table   = $add_drop_table ? 'checked="checked"' : '';
	$str_backup_blob      = $backup_blob ? 'checked="checked"' : '';
	$str_compress_data    = $compress_data ? 'checked="checked"' : '';
	$str_download_as_file = $download_as_file ? 'checked="checked"' : '';

	$data_arr = array();
	$data_arr[] = array(
		'option' => "<input name=\"add_drop_table\" type=\"checkbox\" {$str_add_drop_table}>"
				. "{$LANG_DBMAN['add_drop_table']}"
	);
	
	if ($has_blob) {
		$data_arr[] = array(
			'option' => "<input name=\"backup_blob\" type=\"checkbox\" {$str_backup_blob}>" . "{$LANG_DBMAN['backup_blob']}"
		);
	}
	
	if (function_exists('gzopen')) {
		$data_arr[] = array(
			'option' => "<input name=\"compress_data\" type=\"checkbox\" {$str_compress_data}>"
				. "{$LANG_DBMAN['compress_data']}"
		);
	}
	
	$data_arr[] = array(
		'option' => "<input name=\"download_as_file\" type=\"checkbox\" {$str_download_as_file}>" . "{$LANG_DBMAN['download_as_file']}"
	);
	
	$text_arr = array(
		'has_menu' => true,
		'instructions' => $LANG_DBMAN['db_explanation_backup'],
		'icon' => $baseurl . '/images/dbman.png',
		'title' => $LANG_DBMAN['menu_backup']
	);

	$retval .= ADMIN_simpleList("", $header_arr, $text_arr, $data_arr, $menu_arr);
	$retval .= '<input name="cmd" type="hidden" value="backup">';
	$retval .= "<input name='submit' type='submit' value='{$LANG_DBMAN['backup_now']}'>";
	$retval .= '</form>';
	return $retval;
}

/*
 * Show restore options
 *
 * @return (string) restore options in HTML format
 */

function DBMAN_restoreSelectFile() {

	global $_CONF, $LANG_DBMAN, $dbman_menu, $baseurl;
	require_once($_CONF['path_system'] . 'lib-admin.php');
	
	$retval = '<form action="./index.php" method="post">';
	$menu_arr = array (
		$dbman_menu['admin'],
		$dbman_menu['list'],
		$dbman_menu['backup'],
		$dbman_menu['console']
	);

	$header_arr = array(      # dislay 'text' and use table field 'file' & 'size'
		array('text' => $LANG_DBMAN['backup_file'], 'field' => 'file'),
		array('text' => $LANG_DBMAN['size'], 'field' => 'size')
	);

	$backups = DBMAN_listBackupFiles();
	$data_arr = array();
	foreach ($backups as $backup) {
		$backupfilename = $backup;
		$backupfilesize = COM_numberFormat(filesize($_CONF['backup_path'] . $backupfilename));
		$data_arr[] = array(
			'file' => "<input name=\"filename\" type=\"radio\" value=\"$backupfilename\">" . $backup,
			'size' => $backupfilesize . " <b>" . $LANG_DBMAN['bytes'] . "</b>"
		);
	}
	
	$text_arr = array(
		'has_menu' => true,
		'instructions' => $LANG_DBMAN['db_explanation_restore'],
		'icon' => $baseurl . '/images/dbman.png',
		'title' => $LANG_DBMAN['menu_restore']
	);
	$retval .= ADMIN_simpleList("", $header_arr, $text_arr, $data_arr, $menu_arr);
	$retval .= '<input name="cmd" type="hidden" value="restore_option">';
	$retval .= "<input name='submit' type='submit' value='{$LANG_DBMAN['next']}'>";
	$retval .= '</form>';
	return $retval;
}

/*
 *  Returns restore options in HTML format.  The options are:
 *    1. which table to restore (table strcuture and/or data)
 *    2. whther to restore BLOB fields if any
 */

function DBMAN_restoreOption($filename, $restore_blob = false) {
	global $_CONF, $LANG_DBMAN, $dbman_menu, $baseurl;
	require_once($_CONF['path_system'] . 'lib-admin.php');
	
	$retval    = '<form name="form_restore_option" action="./index.php" method="post">';
	$tables    = dbman_getTableNameFromBackup($_CONF['backup_path'] . $filename);
	$num_table = count($tables);
	$menu_arr = array (
		$dbman_menu['admin'],
		$dbman_menu['list'],
		$dbman_menu['backup'],
		$dbman_menu['console']
	);

	$link_to_table = "<br><input type='button' value='" . $LANG_DBMAN["check_all"]
	               . "' onClick='checkTable({$num_table}, true)'><br>"
				   . "<input type='button' value='" . $LANG_DBMAN["uncheck_all"]
				   . "' onClick='checkTable({$num_table}, false)'>";
	$link_to_data  = "<br><input type='button' value='" . $LANG_DBMAN["check_all"]
	               . "' onClick='checkData({$num_table}, true)'><br>"
				   . "<input type='button' value='" . $LANG_DBMAN["uncheck_all"]
				   . "' onClick='checkData({$num_table}, false)'>";
	
	$header_arr = array(      # dislay 'text' and use table field 'option'
		array(
			'text' => $LANG_DBMAN['restore_header1'],
			'field' => 'table_name'
		),
		array(
			'text' => $LANG_DBMAN['restore_header2'] . $link_to_table,
			'field' => 'table_structure'
		),
		array(
			'text' => $LANG_DBMAN['restore_header3'] . $link_to_data,
			'field' => 'table_data'
		)
	);

	$hasBlob  = false;
	$data_arr = array();
	$i = 0;
	foreach ($tables as $t) {
		$hasBlob = ($hasBlob or dbman_isHasBLOBField($t));
		$data_arr[] = array(
			'table_name' => $t,
			'table_structure' => "<input id ='restore_structure{$i}' name='restore_structure[]' type='checkbox' value='$t'>" . $LANG_DBMAN['restore'],
			'table_data' => "<input id='restore_data{$i}' name='restore_data[]' type='checkbox' value='$t'>" . $LANG_DBMAN['restore']
		);
		$i ++;
	};
	
	// Add links to Javascript
	$data_arr[] = array(
		'table_name'      => $LANG_DBMAN["operation"],
		'table_structure' => $link_to_table,
		'table_data'      => $link_to_data
	);
	
	$fname = pathinfo($_CONF['backup_path'] . $filename);
	$text_arr = array(
		'has_menu' => true,
		'instructions' => sprintf($LANG_DBMAN['db_explanation_restore_option'], "<b>{$fname['basename']}</b>"),
		'icon' => $baseurl . '/images/dbman.png',
		'title' => $LANG_DBMAN['menu_restore']
	);

	$retval .= ADMIN_simpleList("", $header_arr, $text_arr, $data_arr, $menu_arr) . '<hr>';
	
//  other options

	if ($hasBlob) {
		$retval .= "<table class=\"plugin\"><tr><th>{$LANG_DBMAN['other_options']}</th></tr>";
		$retval .= "<tr><td><input name=\"restore_blob\" type=\"checkbox\" {$str_restore_blob}>" . $LANG_DBMAN['restore_blob'] . ($restore_blob ? 'checked="checked"' : '') . '</td></tr>';
		$retval .= '</table>';
	}

	$retval .= '<input name="cmd" type="hidden" value="restore">';
	$retval .= "<input name=\"filename\" type=\"hidden\" value=\"{$filename}\">";
	$retval .= "<input name='submit' type='submit' value='{$LANG_DBMAN['restore_now']}'>";
	$retval .= '</form>';
	
	return $retval;
}

/*
 * List backup files ('databasename_db_backup_YYYY_MM_DD_hh:mm:ss.sql[.gz]')
 * in the /backups directory.
 *
 * @return (string) a list of backup files in HTML format
 */

function DBMAN_listBackups() {
	global $_CONF, $_TABLES, $_IMAGE_TYPE, $LANG_ADMIN, $LANG_DBMAN;
	global $dbman_menu, $baseurl;
	
	require_once($_CONF['path_system'] . 'lib-admin.php');
	$retval = '';

	if (is_writable($_CONF['backup_path'])) {
		$retval .= "<form action='" . $baseurl . '/index.php' . "' method='post'>";
		$backups = DBMAN_listBackupFiles();
		$data_arr = array();
		foreach ($backups as $backup) {
			$backupfile = $_CONF['backup_path'] . $backup;
			$filesize   = COM_numberFormat(filesize($backupfile));
			$url        = "<input name='deletefiles[]' type='checkbox' value='$backup'>  ";
			$url       .= "<a href='" . $_CONF['site_url'] . "/admin/plugins/dbman/download.php?filename={$backup}'>{$backup}</a>";
			$data_arr[] = array(
				'file' => $url,
				'size' => $filesize . " <b>" . $LANG_DBMAN['bytes'] . "</b>"
			);
		}

		$menu_arr = array (
			$dbman_menu['admin'],
			$dbman_menu['backup'],
			$dbman_menu['restore'],
			$dbman_menu['console']
		);

		$header_arr = array(      # dislay 'text' and use table field 'field'
			array('text' => $LANG_DBMAN['backup_file'], 'field' => 'file'),
			array('text' => $LANG_DBMAN['size'], 'field' => 'size')
		);

		$text_arr = array(
			'has_menu' => true,
			'instructions' => $LANG_DBMAN['db_explanation_list'],
			'icon' => $baseurl . '/images/dbman.png',
			'title' => $LANG_DBMAN['last_ten_backups']
		);
		$retval .= ADMIN_simpleList("", $header_arr, $text_arr, $data_arr, $menu_arr);
		
		if (count($backups) > 0) {
			$retval .= "<input name='cmd' type='hidden' value='delete'>";
			$retval .= "<input name='submit' type='submit' value=\"{$LANG_DBMAN['lbl_delete_file']}\">";
		}
		$retval .= '</form>';
	} else {
		$retval .= COM_startBlock ($LANG08[06], '',
		COM_getBlockTemplate ('_msg_block', 'header'));
		$retval .= $LANG_DBMAN['not_writable'];
		COM_errorLog ($_CONF['backup_path'] . ' is not writable.', 1);
		$retval .= COM_endBlock (COM_getBlockTemplate ('_msg_block', 'footer'));
	}
	return $retval;
}

/*
 * Create a backup file ('geeklog_db_backup_YYYY_MM_DD_hh:mm:ss.sql[.gz]')
 * in the /backups directory.
 *
 * @parameters:
 *   $add_drop_table (boolean)  : if true, adds "DROP TABLE IF EXISTS ..." before
 *                                  "CREATE TABLE ..."
 *   $backup_blob (boolean)     : if true, backups blob fields as well in the form of
 *                                  comment("-- ") for the compatibility with phpMyAdmin
 *   $compress_data (boolean)   : if true, tries to compress SQL statements in the
 *                                  '.gz' format.  In case of failure, this function
 *                                  tries to backup DB again without compression
 *   $download_as_file (boolean): if true, tries to download the resulting SQL file
 *
 * @return (int) 0 = success, 1 = success(file downloaded), 2 = failure
 */

function DBMAN_backup($add_drop_table = false, $backup_blob = false,
	$compress_data = false, $download_as_file = false) {
	
	global $_CONF, $_DB_host, $_DB_name, $_DB_dbms, $_TABLES, $_DBMAN_CONF;
	global $dbman_string_types, $dbman_blob_types;
		
	COM_errorLog("Dbman: Started backuping.", 1);
	@set_time_limit(0);		// has no effect in case the safe_mode i on
	$timer   = new DBMAN_timer();	// Create timer
	$db_name = dbman_quoteItem($_DB_name);
	$ver     = dbman_getDBversion();
	$bkdate  = date("Y-m-d H:i:s O");
	$sql     = <<<EOD1
-- ------------------------------------------------------
-- Geeklog Dbman Plugin {$_DBMAN_CONF['version']}
--
-- Host: {$_DB_host}    Database Name: {$db_name}
-- ------------------------------------------------------
-- Database: {$_DB_dbms}    Version: {$ver}
--
-- Backup made: {$bkdate}
-- ------------------------------------------------------


EOD1;
	$foreign_keys = array();
	
	//	Get a list of table names
	$tables = dbman_getTableList();

	//	Get table definitions
	foreach ($tables as $table) {
		$is_to_be_quoted = array();
		$is_blob         = array();
		$def = dbman_getTableDef($table['name']);
		$lines = explode(LB, $def);
		$new_lines = array();
		for ($i = 0; $i < count($lines); $i ++) {
			$line = $lines[$i];
			// PHP 4.x.x doesn't support stripos
			if (eregi('FOREIGN KEY', $line) === false
			 && eregi('CONSTRAINT', $line) === false) {
				// Column definition found
				if (eregi('^[ ]*`(.*)`[ ]+([a-zA-Z0-9_]*).*$', $line, $match) > 0) {
					$column_name = $match[1];
					$column_def  = strtoupper(trim($match[2]));
					$is_to_be_quoted[$column_name] = in_array($column_def, $dbman_string_types);
					$is_blob[$column_name]         = in_array($column_def, $dbman_blob_types);
				}
				$new_lines[] = $line;
			} else {
				//  Foreign key/Constraint found
				$line = rtrim($line);
				//  If this line ends with a ',', delete it
				if (strpos($line, ',') == strlen($line) - 1) {
					$line = substr($line, 0, strlen($line) - 1);
				} else {  // This line is the last row of the definition.  So delete the ','
									// at the end of the previous line.
					$new_lines[$i - 1] = substr($new_lines[$i - 1], 0, strlen($new_lines[$i - 1]) - 1);
				}
				$foreign_keys[] = "ALTER TABLE " . dbman_quoteItem($table['name']) . LB 
					. "  ADD " . $line . ";";
			}
		}
		$tables[$table['name']]['def']             = implode(LB, $new_lines);
		$tables[$table['name']]['is_to_be_quoted'] = $is_to_be_quoted;
		$tables[$table['name']]['is_blob']         = $is_blob;
		$timer->update();
	}
//	$timer->update();

	//	Get table contents
	foreach ($tables as $table) {
	
		//	Append table definition to $sql
		$sql .= '--' . LB;
		$sql .= '-- Table structure for table ' . dbman_quoteItem($table['name']) . LB;
		$sql .= '--' . LB . LB;
		if ($add_drop_table) {
			$sql .= 'DROP TABLE IF EXISTS ' . dbman_quoteItem($table['name']) . ';' . LB . LB;
		}
		$sql .= $tables[$table['name']]['def'] . LB . LB;
		$sql .= '--' . LB;
		$sql .= '-- Dumping data for table ' . dbman_quoteItem($table['name']) . LB;
		$sql .= '--' . LB . LB;

		if (DBMAN_isBackupExcept($table['name'])) {
			$sql .= "-- (skipped backuping {$table['name']})" . LB . LB;
			continue;
		}

		//  Count records in advance to be used in "SELECT * LIMIT ..."
		$rs  = DB_query("SELECT COUNT(*) AS cnt FROM {$table['name']};");
		$A = DB_fetchArray($rs);
		$num_rows = $A['cnt'];
		
		for ($offset = 0; $offset < $num_rows; $offset += $_DBMAN_CONF['chunk_size']) {
			//	Build an SQL to pump out data
			$rst = DB_query("SELECT * FROM {$table['name']} LIMIT {$offset}, {$_DBMAN_CONF['chunk_size']};");
			if ($rst !== false) {
				//  Prefetch $table['is_to_be_quoted'], $table['is_blob']
				$is_to_be_quoted = $table['is_to_be_quoted'];
				$is_blob         = $table['is_blob'];
				$has_blob_field  = in_array(true, $is_blob);
				while (($r = DB_fetchArray($rst, false)) !== false) {		//  Fetch an associative array alone
					$column_values = array();
					foreach ($r as $column_name => $column_value) {
						if (is_null($column_value)) {
							$column_value = 'NULL';
						} else if ($is_to_be_quoted[$column_name]) {
							$column_value = dbman_quoteString($column_value);
						} else if ($is_blob[$column_name]) {
							if ($backup_blob) {
								$column_value = "\"\n-- B:" 
									. chunk_split(base64_encode($column_value), 75, "\n-- B:");
								$column_value = substr($column_value, 0, strlen($column_value) - 2) . 'Q:"';
							} else {
								$column_value = "'(BLOB)'";
							}
						}
						$column_values[] = $column_value;
					}
					if ($has_blob_field && $backup_blob) {
						$sql .= '-- Q:';
					}
					$sql .= "INSERT INTO " . dbman_quoteItem($table['name']) . " VALUES (" . implode(',' , $column_values) . ');' . LB;
				}
			}

			$timer->update();
		} /* End of for-loop */
		$sql .= LB;
		$timer->update();
	} /* End of foreach-loop */
	
	//  Append foreign key definitions if any
	if (count($foreign_keys) > 0) {
		$sql .= '--' . LB . '-- FOREIGN KEY and CONSTRAINT definitions' . LB . '--' . LB . LB;
		$sql .= implode(LB, $foreign_keys) . LB;
	}
	
	//	Save data into a file in the 'backups' dir
	if (! is_writable($_CONF['backup_path'])) {
		COM_errorLog("Dbman: the '/backups' directory is not writable.  Please chmod it to 775 or 777.");
		return 2;
		exit;
	}
	
	$isSuccess  = false;			//  flag to show a success in saving
	$curdatetime = date("Y_m_d_H_i_s");
	$backupfile  = "{$_CONF['backup_path']}{$_DB_name}_db_backup_{$curdatetime}.sql";
	if (file_exists($backupfile) && ! is_writable($backupfile)) {
		COM_errorLog("Dbman: Couldn't create a backup file.");
		return 2;
		exit;
	}
	
	//  If $compress_data is true, try to compress the data
	if ($compress_data && function_exists('gzopen')) {
		$backupfile .= '.gz';
		$fh = @gzopen($backupfile, "wb" . $_DBMAN_CONF['compression_level']);
		if ($fh === false) {
			COM_errorLog("Dbman: Couldn't open a backup-file for writing.", 1);
		} else {
			$rst = gzwrite($fh, $sql);
			if ($rst === false) {
				COM_errorLog("Dbman: Couldn't write data into a backup-file.", 1);
				@gzclose($fh);
			} else {
				$isSuccess = @gzclose($fh);
				if ($isSuccess) {
					$isSuccess = DBMAN_isCreated($backupfile);
					if ($isSuccess) {
						COM_errorLog("Dbman: Backup completed successfully.", 1);
					} else {
						COM_errorLog("Dbman: Couldn't write data into a backup-file.", 1);
					}
				}
			}
		}
	}
	$timer->update();
	
	//  If $compress_data is false, or when there is a failure to compress, save 
	//  backup files without compression
	if (! $isSuccess) {
		$fh = @fopen($backupfile, "wb");
		if ($fh === false) {
			COM_errorLog("Dbman: Couldn't open a backup-file for writing.", 1);
		} else {
			$rst = fwrite($fh, $sql);
			if ($rst === false) {
				COM_errorLog("Dbman: Couldn't write data into a backup-file.", 1);
			} else {
				$isSuccess = @fclose($fh);
				if ($isSuccess) {
					$isSuccess = DBMAN_isCreated($backupfile);
					if ($isSuccess) {
						COM_errorLog("Dbman: Backup completed successfully.", 1);
						@chmod($backupfile, 0644);
					} else {
						COM_errorLog("Dbman: Couldn't write data into a backup-file.", 1);
					}
				}
			}
		}
	}

	if (! $isSuccess) {		// couldn't backup at all
		return 2;
		exit;
	} else {
		if ($download_as_file) {
			$info = pathinfo($backupfile);
			if ($info['extension'] == 'gz') {
				header("Content-type: application/x-gzip");
			} else {
				header("Content-type: text/x-sql");
//			header("Content-type: application/octetstream");
			}
			header("Content-Disposition: attachment; filename={$info['basename']}");
			readfile($backupfile);
			return 1;
			exit;
		}
	}
	return 0;
}

/*
 * Restore from a designated backup file
 *
 *   ('geeklog_db_backup_YYYY_MM_DD_hh:mm:ss.sql[.gz]') in the /backups directory.
 *
 * @parameters:
 *   $filename (string)         : the full path to a backup file
 *   $structures_arr (array of string): an array of table names to restore their
 *                                structures
 *   $data_arr (array of string): an array of table names to restore their data
 *
 *   $restore_blob (boolean)    : if true, tries to restore blob fields as well by
 *                                analyzing comment fields written by Dbman.  If
 *                                there is no commented-out data, this functions
 *                                writes '(BLOB)' into the field
 *
 * @return (int) true = success, false = failure
 *
 * :TODO:  implement the feature of restoring BLOB fields
 *
 */

function DBMAN_restore($filename, $structures_arr, $data_arr, $restore_blob = false) {
	global $_CONF;
	
	$filename = $_CONF['backup_path'] . $filename;
	$num_structure = count($structures_arr);
	$num_data      = count($data_arr);
	$num_structure_success = $num_data_success = 0;
	$timer   = new DBMAN_timer();	// Create timer
	$msg = 'Dbman: restoration started.';
	COM_errorLog($msg);
	echo $msg . '<br>';

	// Recreate table structures
	for ($i = 0; $i < $num_structure; $i ++) {
		// Drop tables first
		$table    = $structures_arr[$i];
		$drop_sql = 'DROP TABLE ' . dbman_quoteItem($table) . ';';
		echo $drop_sql . "<br>";
		DB_query($drop_sql, 1);
		if (DB_error()) {
			$msg = "Dbman: couldn't drop table {$table}.";
			COM_errorLog($msg);
			echo $msg . '<br>';
		}

		// Then, recreate tables
		$create_sql = dbman_extractTableDefFromBackup($table, $filename);
		DB_query($create_sql, 1);
		if (DB_error()) {
			$msg = "Dbman: couldn't re-create table {$table}.";
			COM_errorLog($msg);
			echo $msg . '<br>';
		} else {
			$msg = "Dbman: re-created table {$table} successfully.";
			COM_errorLog($msg);
			echo $msg . '<br>';
			$num_structure_success ++;
		}

		$timer->update();
	}
	
	echo "<hr>";
	
	// Restore table contents
	$data = file_get_contents($filename);
	$data = str_replace(array("\r\n", "\r"), LF, trim($data));
	$data = explode(LB, $data);
	for ($i = 0; $i < $num_data; $i ++) {
		$table   = $data_arr[$i];
//		$hasBlob = dbman_isHasBLOBField($table);
		$msg = "Dbman: started restoring data into {$table}.";
		COM_errorLog($msg);
		
		echo $msg . '<br>';
		reset($data);
		$num_error = 0;
		for ($j = 0; $j < count($data); $j ++) {
			if (eregi("^[ \t]*INSERT[ \t]+INTO[ \t]+" . dbman_quoteItem($table), $data[$j], $match) > 10) {
				$d = $data[$j];
				$d = str_replace("INSERT INTO", "REPLACE", $d);	//  MySQL-specific SQL
				DB_query($d, 1);
				if (DB_error()) {
					$msg = "Dbman: couldn't insert data into table {$table}.  SQL in question is $d.";
					COM_errorLog($msg);
					echo $msg . '<br>';
				} else {
					echo '.';
				}
			}
			
			$timer->update();
		}
		$msg = "Dbman: finished restoring data into table {$table}.";
		COM_errorLog($msg);
		echo '<br>' . $msg . '<br>';
		if ($num_error == 0) {
			$num_data_success ++;
		}

		$timer->update();
	}
	
	$msg ='Dbman: restoration completed.';
	COM_errorLog($msg);
	echo $msg . '<br>';
	echo "<hr><p>To return to the top page, <a href=\"{$_CONF['site_url']}\"><ab>CLICK HERE</b>.</a></p>";
	
	exit;
	return false;
}

// Delete the specified backup file
// @return: true (success) false (failute)

function DBMAN_delete($filename) {
	global $_CONF, $_USER;
	
	if ($filename != basename($filename) || (! preg_match ('/\.sql$/i', $filename) && ! preg_match ('/\.sql\.gz$/i', $filename))) {
		COM_errorLog("Dbman: Error!  Path Traversal attack deteced.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: {$_SERVER['REMOTE_ADDR']}");
		return false;
	}
	
	$filename = $_CONF['backup_path'] . $filename;
	if (file_exists($filename)) {
		$result = unlink($filename);
	}
	
	return $result;
}

// Show SQL console

function DBMAN_showSQLConsole() {
	global $_CONF, $LANG_DBMAN, $dbman_menu, $baseurl;
	
	$retval = "<form action='" . $baseurl . '/index.php' . "' method='post'>";
	$menu_arr = array (
		$dbman_menu['admin'],
		$dbman_menu['backup'],
		$dbman_menu['restore']
	);

    $T = new Template($_CONF['path_layout'] . 'admin/lists');
    $T->set_file (
		array (
			'topmenu' => 'topmenu_nosearch.thtml',
            'menufields' => 'menufields.thtml'
		)
	);
    $T->set_var('icon', $baseurl . '/images/dbman.png');
    $T->set_var('lang_instructions', $LANG_DBMAN['desc_exec_sql']);
	$menu_items = '';
	for ($i = 0; $i < count($menu_arr); $i ++) {
		$T->set_var('menu_url', $menu_arr[$i]['url']);
		$T->set_var('menu_text', $menu_arr[$i]['text']);
		if ($i != count($menu_arr) - 1) {
			$T->set_var('line', '|');
		}
		$T->parse('menu_fields', 'menufields', true);
		$T->clear_var('line');
	}
	$T->parse('output', 'topmenu');
	$retval .= $T->finish($T->get_var('output'));
	$retval .= "<textarea name='SQL' rows='10' cols='80'></textarea><br>";
	$retval .= "<input name='cmd' type='hidden' value='console_exec'>";
	$retval .= "<input name='submit' type='submit' value=\"{$LANG_DBMAN['lbl_exec_sql']}\">";
	$retval .= '</form>';

	return $retval;
}

// Execute SQL and show results

function DBMAN_execSQL() {
	global $_CONF, $LANG_DBMAN, $dbman_menu, $baseurl;

	$result = '';
	$has_error = false;
	$retval = COM_startBlock('Dbman: ' . $LANG_DBMAN['lbl_exec_sql']);
	if (isset($_POST['SQL'])) {
		$SQL = trim($_POST['SQL']);
		$SQL = str_replace(array("\r\n", "\n\r", "\r", "\n"), ' ', $SQL);
		$retval .= "<br>{$LANG_DBMAN['sql_executed']}<br><p style='color: black; background-color: white; padding: 5px; border: solid 1px black;'>";
		$retval .= htmlspecialchars($SQL, ENT_NOQUOTES) . "</p>{$LANG_DBMAN['sql_result']}<br>";
//		if (! preg_match('/^(SELECT|DELETE|UPDATE|INSERT)/i', $SQL)) {
		if ( 1 == 0 ) {
			$has_error = true;
			$result = htmlspecialchars($LANG_DBMAN['sql_error_siud'], ENT_NOQUOTES);
		} else {
			DB_displayError(true);
			$r = DB_query($SQL, 1);	//	Ignore errors
			if ($r === false) {
				$has_error = true;
				$result = htmlspecialchars(DB_error(), ENT_NOQUOTES);
			} else {
				$result = "<table cellpadding='3' cellspacing='0' border='1'>";
				$row_names = array();
				$A = DB_fetchArray($r);
				$keys = array_keys($A);
				$result .= '<tr>';
				foreach ($keys as $key) {
					if (! is_numeric($key)) {
						$row_names[] = $key;
						$result .= '<th>' . COM_applyFilter($key) . '</th>';
					}
				}
				$result .= '</tr>';
				
				$even = true;
				while ($A = DB_fetchArray($r)) {
					if ($even) {
						$result .= "<tr bgcolor='#ccffff'>";
					} else {
						$result .= "<tr bgcolor='#6699cc'>";
					}
					$even = ! $even;
					foreach ($row_names as $row_name) {
						$result .= '<td>';
						if ($A[$row_name] == '') {
							$result .= '&nbsp;';
						} else {
							$result .= htmlspecialchars($A[$row_name], ENT_NOQUOTES);
						}
						$result .= '</td>';
					}
					$result .= '</tr>';
				}

				$result .= '</table>';
			}
			DB_displayError(false);
		}
	}
	
	if (isset($result)) {
		if ($has_error) {
			$retval .= "<p style='color: red; background-color: white; padding: 5px; border: solid 1px black;'>";
		} else {
			$retval .= "<p style='color: black; background-color: white; padding: 10px; border: solid 1px black;'>";
		}
		$retval .= $result . '</p>';
	}
	
	$retval .= "<a href='" . $_CONF['site_admin_url'] . '/plugins/dbman/index.php' . "'>Dbman HOME</a>";
	$retval .= COM_endBlock();
	return $retval;
}

?>
