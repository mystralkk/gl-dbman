<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | Geeklog Dbman Plugin 0.3 for Geeklog - The Ultimate Weblog                |
// +---------------------------------------------------------------------------+
// | index.php   Dbman plugin admin index file                                 |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2000-2006 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs         - tony AT tonybibbs DOT com                   |
// |          Blaine Lang        - langmail AT sympatico DOT ca                |
// |          Dirk Haun          - dirk AT haun-online DOT de                  |
// |          Alexander Schmacks - Alexander.Schmacks AT gmx DOT de            |
// |          mystral_kk         - info AT ddlinks DOT net                     |
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
 * Dbman plugin admin index file based on Geeklog 'databse.php' file
 */

require_once('../../../lib-common.php');
require_once($_CONF['path'] . '/plugins/dbman/config.php');

$display = '';

// Check user has rights to access this page
if (!SEC_hasRights('dbman.edit')) {
    // Someone is trying to illegally access this page
    COM_errorLog("Someone has tried to illegally access the Dbman page.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: $REMOTE_ADDR",1);
    $display  = COM_siteHeader();
    $display .= COM_startBlock($LANG_DBMAN['access_denied']);
    $display .= $LANG_DBMAN['access_denied_msg'];
    $display .= COM_endBlock();
    $display .= COM_siteFooter(true);
    echo $display;
    exit;
}

// ==================================================================
// 		Main function
// ==================================================================

// the three values set below are meaningful only the first time this file is called

$add_drop_table   = $_DBMAN_CONF['add_drop_table'];
$backup_blob      = $_DBMAN_CONF['backup_blob'];
$compress_data    = $_DBMAN_CONF['compress_data'];
$download_as_file = $_DBMAN_CONF['download_as_file'];
$restore_blob     = $_DBMAN_CONF['restore_blob'];

// decides whether to list or backup or restore

$mode = 'list';
$vars = array_merge($_GET, $_POST);
if (isset($vars['mode'])) {
	if ($vars['mode'] == 'backup') {
		$mode = 'backup';
	} else if ($vars['mode'] == 'restore') {
		$mode = 'restore';
	}
}

$display = COM_siteHeader();;

switch ($mode) {
case 'list':
	$display .= DBMAN_listbackups();
	break;
case 'backup':
	if (isset($_POST['submit']) && isset($_POST['cmd']) && $_POST['cmd'] == 'do_backup') {
		$add_drop_table   = isset($_POST['add_drop_table']);
		$backup_blob      = isset($_POST['backup_blob']);
		$compress_data    = isset($_POST['compress_data']);
		$download_as_file = isset($_POST['download_as_file']);
		$rst = DBMAN_backup($add_drop_table, $backup_blob, $compress_data,
			$download_as_file);
		if ($rst == 2) {		//  failed
			$display .= '<p style="font-size: 20px; font-weight: bold; color: red;">' . $LANG_DBMAN['backup_failure'] . '</p>';
			break;
		} else {		//  success
			if ($rst == 1) {	// in case of download, just exit
				exit;
			} else {	//  redirect to dbman main page
				$display  = COM_refresh($_CONF['site_admin_url'] . '/plugins/dbman/index.php') . $display;
				$display .= '<p style="font-size: 20px; font-weight: bold; color: green;">' . $LANG_DBMAN['backup_success'] . '</p>';
				$display .= COM_siteFooter(1);
				echo $display;
				exit;
			}
		}
	}
	
	// show backup options
	
	$display .= DBMAN_backupoptions($add_drop_table, $backup_blob, $compress_data,
		$download_as_file);
	break;
case 'restore':
	if (isset($_POST['submit']) && isset($_POST['cmd']) 
	 && $_POST['cmd'] == 'do_restore') {
	 	if (isset($_POST['filename'])) {
			$filename = COM_applyFilter($_POST['filename']);	//  maybe not very good
			$restore_blob = isset($_POST['restore_blob']);
			if (DBMAN_restore($filename, $restore_blob)) {
				$display  = COM_refresh($_CONF['site_admin_url'] . '/plugins/dbman/index.php') . $display;
				$display .= '<p style="font-size: 20px; font-weight: bold; color: green;">' . $LANG_DBMAN['resore_success'] . '</p>';
				$display .= COM_siteFooter(1);
				echo $display;
				exit;
			} else {
				$display .= '<p style="font-size: 20px; font-weight: bold; color: red;">'
				  . $LANG_DBMAN['restore_failure'] . '</p>';
			}
		} else {
			$display .= '<p style="font-size: 20px; font-weight: bold; color: red;">' . $LANG_DBMAN['no_file_selected'] . '</p>';
		}
	}
	
	// show restore options
	
	$display .= DBMAN_restoreoptions($restore_blob);
	break;
}

// to hide right blocks, use COM_siteFooter() instead
$display .= COM_siteFooter(1);	
echo $display;

?>