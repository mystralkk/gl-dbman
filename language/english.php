<?php

###############################################################################
# english.php
# This is the english language page for the Geeklog Dbman Plug-in!
#
# Copyright (C) 2006 mystral_kk - mystral_kk AT ddlinks DOT net
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
#
###############################################################################
# $Id$

/*
 * This is the english language page for the Geeklog Dbman Plug-in! 
 */

###############################################################################
# Array Format:
# $LANGXX[YY]:	$LANG - variable name
#		  	XX - file id number
#			YY - phrase id number
###############################################################################
/*
 * the Dbman plugin's lang array
 * 
 * @global array $LANG_DBMAN
 */

$LANG_DBMAN = array(
	'access_denied' => 'Access Denied',
	'access_denied_msg' => 'Only Root Users have Access to this Page.  Your user name and IP have been recorded.',
	'add_drop_table' => 'Add "DROP TABLE IF EXISTS"',
	'admin'		        => 'Plugin Admin',
	'backup_blob' => 'Backup BLOB fields as well',
	'backup_failure' => 'Backup failed -- See your error log to find out why.',
	'backup_file' => 'Backuped file',
	'backup_now' => 'Backup now!',
	'backup_success' => 'Backup completed successfully.',
	'bytes' => 'bytes',
	'compress_data' => 'Compress the backup file',
	'couldnt_get_table_contents' => "(Couldn't get table contents.  Please delete ",
	'couldnt_get_table_contents2' => ' and try again.)',
	'couldnt_write_backup' => "Couldn't write data into a backupfile.",
	'db_explanation_backup' => 'To backup Database, click \'Backup now!\'.',
	'db_explanation_list' => 'Click \'Backup DB\' or \'Restore DB\'',
	'db_explanation_restore' => 'To restore Database, check a backup file and then click \'Next &gt;$gt;>\'.',
	'download_as_file' => 'Download as a file',
	'enabled' => 'Disable plugin before uninstalling.',
	'install' => 'Install',
  'installdoc' => 'Install Document.',
	'installed' => 'The Plugin is Installed',
	'install_header'	=> 'Install/Uninstall Plugin',
  'install_failed'	=> 'Installation Failed -- See your error log to find out why.',
	'install_success'	=> 'Installation Successful',
	'last_ten_backups' => 'Last ten backups',
	'menu_backup' => 'Backup Database',
	'menu_list' => 'List backuped files',
	'menu_restore' => 'Restore selected DB',
	'next' => 'Next &gt;&gt;',
	'no_file_selected' => 'No backup file is selected.',
	'not_writable' => 'Backups directory not writable',
	'option' => '&lt;Options&gt;',
	'plugin' => 'Dbman Plugin',
	'readme' => 'STOP!  Before you press install please read the ',
	'backup_blob' => 'Restore BLOB fields as well',
	'restore_now' => 'Restore now!',
	'size' => 'Size',
	'uninstall' => 'UnInstall',
	'uninstall_msg'	=> 'Plugin Successfully Uninstalled',
	'uninstalled' => 'The Plugin is Not Installed',
	'warning' => 'Warning!  Plugin is still Enabled',
	'dbman' => 'Dbman'
);

?>