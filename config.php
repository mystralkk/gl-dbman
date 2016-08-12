<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | Geeklog Dbman Plugin for Geeklog - The Ultimate Weblog                    |
// +---------------------------------------------------------------------------+
// | config.php   Dbman plugin configuration file                              |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2006  mystral_kk - mystral_kk AT ddlinks DOT net            |
// +---------------------------------------------------------------------------+
// |                                                                           |
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
 * Dbman plugin configuration file
 */

/*
 * the Dbman plugin's config array
 * 
 * @global array $_DBMAN_CONF
 */

$_DBMAN_CONF = array();

/*
 * the dbman plugin's version setting
 * 
 * @global array $_DBMAN_CONF['version']
 */

$_DBMAN_CONF['version'] = '0.4';					// Plugin Version

/*
 * the flag to decide whether to add "DROP TABLE IF EXISTS ...".
 * For the compatibility with PhpMyAdminin, this should be set false.
 */
 
$_DBMAN_CONF['add_drop_table'] = false;

/*
 * the dbman Plugin doen't backup BLOB data for the compatibility with
 * PhpMyAdminin but replaces the data with a string '(BLOB)'.  However, if this
 * flag is set true, the plugin will backup BLOB data in the base64 format.
 * Still, "INSERT INTO ..." SQL statements with BLOB are commented out.
 *
 */
 
$_DBMAN_CONF['backup_blob'] = false;

/*
 * the flag to decide whether to compress backup files.  If set true, the
 * dbman plugin tries to compress the data with Zlib.  In this case,
 * names of backup files are '*.sql.gz'.
 */

$_DBMAN_CONF['compress_data'] = false;

/*
 * the flag to indicate compression level:
 * valid values: 1 (largest size) - 9 (smallest size)
 */

$_DBMAN_CONF['compression_level'] = 9;

/*
 * the flag to decide whether to download backup files.
 */

$_DBMAN_CONF['download_as_file'] = false;

/*
 * the flag to decide whether to restore BLOB fields.
 */

$_DBMAN_CONF['restore_blob'] = false;

?>