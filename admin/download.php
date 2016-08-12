<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | Geeklog Dbman Plugin for Geeklog - The Ultimate Weblog                    |
// +---------------------------------------------------------------------------+
// | download.php   Dbman plugin download controller file                      |
// +---------------------------------------------------------------------------+
// | Constructed with the Universal Plugin                                     |
// | Copyright (C) 2000-2006 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs         - tony AT tonybibbs DOT com                   |
// |          Blaine Lang        - langmail AT sympatico DOT ca                |
// |          Dirk Haun          - dirk AT haun-online DOT de                  |
// |          Alexander Schmacks - Alexander.Schmacks AT gmx DOT de            |
// |          mystral-kk         - geeklog AT mystral-kk DOT net               |
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
// $Id: download.php,v 1.1 2007/04/05 00:02:12 kenji Exp $
/* 
 * Dbman plugin download controller file
 */

require_once('../../../lib-common.php');
require_once($_CONF['path'] . '/plugins/dbman/config.php');

// Check if user has rights to access this page
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

// Check if filename contains directory, or if filename ends with '.sql' or '.sql.gz'
$filename = COM_applyFilter($_GET['filename']);
if ($filename != basename($filename) || (! preg_match('/\.sql$/i', $filename) && ! preg_match('/\.sql\.gz$/i', $filename))) {
    // Invalid file name was designated.
    COM_errorLog("Invalid file name was designated for download.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: $REMOTE_ADDR",1);
    $display  = COM_siteHeader();
    $display .= COM_startBlock($LANG_DBMAN['access_denied']);
    $display .= $LANG_DBMAN['invalid_filename'];
    $display .= COM_endBlock();
    $display .= COM_siteFooter(true);
    echo $display;
    exit;
}

// Check if the file really exists
$filename = $_CONF['backup_path'] . $filename;
clearstatcache();
if (! file_exists($filename)) {
    // The designated file doesn't exist
    COM_errorLog("The file you designated doesn't exist.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: $REMOTE_ADDR",1);
    $display  = COM_siteHeader();
    $display .= COM_startBlock($LANG_DBMAN['access_denied']);
    $display .= $LANG_DBMAN['file_not_found'];
    $display .= COM_endBlock();
    $display .= COM_siteFooter(true);
    echo $display;
    exit;
}

// Download it!
clearstatcache();
$info = pathinfo($filename);
if ($info['extension'] == 'gz') {
	header("Content-type: application/x-gzip");
} else {
	header("Content-type: text/x-sql");
//	header("Content-type: application/octetstream");
}
header("Content-Disposition: attachment; filename={$info['basename']}");
readfile($filename);

?>
