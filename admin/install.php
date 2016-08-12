<?php

// Reminder: always indent with 4 spaces (no tabs). 
// +---------------------------------------------------------------------------+
// | Geeklog Dbman Plugin for Geeklog - The Ultimate Weblog                    |
// +---------------------------------------------------------------------------+
// | install.php   Dbman plugin installation file                              |
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
 * Dbman plugin installation file
 */

require_once('../../../lib-common.php');
require_once($_CONF['path'] . 'plugins/dbman/config.php');
require_once($_CONF['path'] . 'plugins/dbman/functions.inc');

$_FORM = array_merge($_GET, $_POST);
$action = COM_applyFilter($_FORM['action']);

// Dbman plugin install variables

$pi_name         = 'dbman';							// Plugin name
$pi_display_name = 'dbman';							// Plugin display name
$pi_version      = $_DBMAN_CONF['version'];			// Plugin Version
$gl_version      = '1.4.0';							// GL Version plugin for
$pi_url          = 'http://www.ddlinks.net/blog/';	// Plugin Homepage

// Security Feature to add

$NEW_FEATURES = array();
$NEW_FEATURES['dbman.edit'] = 'Dbman Admin';

$display = '';

// Only let Root users access this page
if (!SEC_inGroup('Root')) {
	// Someone is trying to illegally access this page
	COM_errorLog("Someone has tried to illegally access the Dbman install/uninstall page.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: $REMOTE_ADDR",1);
	$display  = COM_siteHeader();
	$display .= COM_startBlock($LANG_DBMAN['access_denied']);
	$display .= $LANG_DBMAN['access_denied_msg'];
	$display .= COM_endBlock();
	$display .= COM_siteFooter(true);
 	echo $display;
  exit;
}


/*
 * Install Dbman Plugin
 * @return	boolean	True if successful False otherwise
 */

function plugin_install_dbman()
{
	global $_CONF, $_TABLES, $NEW_FEATURES, $pi_name, $pi_display_name, $pi_version, $gl_version, $pi_url;

	// Register the plugin with Geeklog
	COM_errorLog("Attempting to install the $pi_display_name Plugin",1);

	// Create the plugin admin security group
	COM_errorLog("Attempting to create $pi_name admin group", 1);
	DB_query("INSERT INTO {$_TABLES['groups']} (grp_name, grp_descr) "
		. "VALUES ('$pi_name Admin', 'Users in this group can administer the $pi_name plugin')",1);
	if (DB_error()) {
 		plugin_uninstall_dbman();
		return false;
		exit;
	}
	COM_errorLog('... success', 1);
	$group_id = DB_insertId();
    
	// Save the grp id for later uninstall
	COM_errorLog('About to save group_id to vars table for use during uninstall',1);
	DB_query("INSERT INTO {$_TABLES['vars']} VALUES ('{$pi_name}_gid', $group_id)",1);
	if (DB_error()) {
		plugin_uninstall_dbman();
		return false;
		exit;
	}
	COM_errorLog('... success',1);
    
	// Add plugin Features
	foreach ($NEW_FEATURES as $feature => $desc) {
		COM_errorLog("Adding $feature feature",1);
		DB_query("INSERT INTO {$_TABLES['features']} (ft_name, ft_descr) "
			. "VALUES ('$feature','$desc')",1);
		if (DB_error()) {
			COM_errorLog("Failure adding $feature feature",1);
			plugin_uninstall_dbman();
			return false;
			exit;
		}
		$feat_id = DB_insertId();
		COM_errorLog("Success", 1);
		COM_errorLog("Adding $feature feature to admin group", 1);
		DB_query("INSERT INTO {$_TABLES['access']} (acc_ft_id, acc_grp_id) VALUES ($feat_id, $group_id)");
		if (DB_error()) {
			COM_errorLog("Failure adding $feature feature to admin group", 1);
			plugin_uninstall_dbman();
			return false;
			exit;
		}
		COM_errorLog("... success",1);
	}        
	
	// OK, now give Root users access to this plugin now! NOTE: Root group should always be 1
	COM_errorLog("Attempting to give all users in Root group access to $pi_name admin group", 1);
	DB_query("INSERT INTO {$_TABLES['group_assignments']} VALUES ($group_id, NULL, 1)");
	if (DB_error()) {
		plugin_uninstall_dbman();
		return false;
		exit;
	}

	// silently delete an existing entry
	DB_delete($_TABLES['plugins'], 'pi_name', $pi_name);

	DB_query("INSERT INTO {$_TABLES['plugins']} (pi_name, pi_version, pi_gl_version, pi_homepage, pi_enabled) VALUES "
        . "('$pi_name', '$pi_version', '$gl_version', '$pi_url', 1)");

	if (DB_error ()) {
		$uninstall_plugin_dbman();
		return false;
		exit;
	} else {
		COM_errorLog("Succesfully installed the $pi_display_name Plugin!", 1);
		return true;
	}
}

/* 
 * Main Function
 */

$display = COM_siteHeader();
$T = new Template($_CONF['path'] . 'plugins/' . $pi_name . '/templates');
$T->set_file('install', 'install.thtml');
$T->set_var('install_header', $LANG_DBMAN['install_header']);
$T->set_var('img', $_CONF['site_admin_url'] . '/plugins/' . $pi_name . '/images/' . $pi_name . '.png');
$T->set_var('cgiurl', $_CONF['site_admin_url'] . '/plugins/' . $pi_name . '/install.php');
$T->set_var('admin_url', $_CONF['site_admin_url'] . '/plugins/' . $pi_name . '/index.php');

if ($action == 'install') {
	if (plugin_install_dbman()) {
		$install_msg = $LANG_DBMAN['install_success'];
		$T->set_var('installmsg1', $install_msg);
	} else {
		$T->set_var('installmsg1', $LANG_DBMAN['install_failed']);
	}
} else if ($action == "uninstall") {
	if (plugin_uninstall_dbman()) {
	  	$T->set_var('installmsg1', $LANG_DBMAN['uninstall_msg']);
	} else {
  		$T->set_var('installmsg1', $LANG_DBMAN['uninstall_failed']);
	}
}

if (DB_count($_TABLES['plugins'], 'pi_name', 'dbman') == 0) {
	$T->set_var('installmsg2', $LANG_DBMAN['uninstalled']);
	$T->set_var('btnmsg', $LANG_DBMAN['install']);
	$T->set_var('action', 'install');
} else {
	$T->set_var('installmsg2', $LANG_DBMAN['installed']);
	$T->set_var('btnmsg', $LANG_DBMAN['uninstall']);
	$T->set_var('action','uninstall');
}
$T->parse('output', 'install');
$display .= $T->finish($T->get_var('output'));
$display .= COM_siteFooter(true);

echo $display;

?>