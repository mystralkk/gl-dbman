<?php

###############################################################################
# english.php
# This is the english language page for the Geeklog Dbman Plug-in!
#
# Copyright (C) 2006 mystral-kk - geeklog AT mystral-kk DOT net
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
# $Id: english.php,v 1.3 2007/04/05 00:00:57 kenji Exp $

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
	'backup_blob' => 'Backup BLOB fields as well (incompatible with phpMyAdmin!)',
	'backup_failure' => 'Backup failed -- See your error log to find out why.',
	'backup_file' => 'Backuped file',
	'backup_now' => 'Backup now!',
	'backup_success' => 'Backup completed successfully.',
	'bytes' => 'bytes',
	'check_all' => 'Check all',
	'compress_data' => 'Compress the backup file in gzip (\'.gz\') format',
	'couldnt_get_table_contents' => "(Couldn't get table contents.  Please delete ",
	'couldnt_get_table_contents2' => ' and try again.)',
	'couldnt_write_backup' => "Couldn't write data into a backupfile.",
	'db_explanation_backup' => 'To backup Database, click \'Backup now!\'.',
	'db_explanation_list' => 'Click \'Backup DB\' or \'Restore DB\'',
	'db_explanation_restore' => 'To restore Database, check a backup file and then click \'Next &gt;&gt;>\'.',
	'db_explanation_restore_option' => 'Select options for %s.  If you check <strong>TABLE STRUCTURES</strong>, the checked tables\' structures will be re-created (the existing data will be deleted).  If you check <strong>DATA</strong>, the checked data will be restored without deleting the existing data.  In case there is a duplicate record, the existing record will be overwritten.<br><strong>WARNING: The restoration feature is a simple and crude one under development.  Dbman plugin doesn\'t care which table you will restore or which record will be overwritten.  In case you restore data into tables being used by the Geeeklog system or the size of records to be restored is too big, the restoration process may fail and result in broken tables.  It would be better to use phpMyAdmin in order to restore your database.</strong>',  /* %s = backup file */
	'download_as_file' => 'Download as a file',
	'enabled' => 'Disable plugin before uninstalling.',
	'install' => 'Install',
  'installdoc' => 'Install Document.',
	'installed' => 'The Plugin is Installed',
	'install_header'	=> 'Install/Uninstall Plugin',
  'install_failed'	=> 'Installation Failed -- See your error log to find out why.',
	'install_success'	=> 'Installation Successful',
	'last_ten_backups' => 'Backups stored on the server',
	'menu_backup' => 'Backup Database',
	'menu_list' => 'List backuped files',
	'menu_restore' => 'Restore selected DB',
	'next' => 'Next&gt;&gt;',
	'no_file_selected' => 'No backup file is selected.',
	'not_writable' => 'Backups directory not writable',
	'operation' => 'Operation',
	'option' => '&lt;Options&gt;',
	'other_options' => '&lt;Other options&gt;',
	'plugin' => 'Dbman Plugin',
	'readme' => 'STOP!  Before you press install please read the ',
	'restore' => 'restore',
	'restore_blob' => 'Restore BLOB fields as well',
	'restore_failure' => 'Restoration failed -- See your error log to find out why.',
	'restore_now' => 'Restore now!',
	'restore_success' => 'Restoration completed successfully.',
	'restore_header1' => 'table name',
	'restore_header2' => 'TABLE STRUCTURE',
	'restore_header3' => 'TABLE DATA',
	'size' => 'Size',
	'uncheck_all' => 'UnCheck all',
	'uninstall' => 'UnInstall',
	'uninstall_msg'	=> 'Plugin Successfully Uninstalled',
	'uninstalled' => 'The Plugin is Not Installed',
	'warning' => 'Warning!  Plugin is still Enabled',
	'dbman' => 'Dbman',
// ver 0.4.3 -->
	'invalid_filename' => 'Invalid file name.',
	'file_not_exist' => 'File not found.',
	'lbl_delete_file' => 'Delete the checked files',
	'ttl_delete_file' => 'Delete backup files',
	'menu_console' => 'SQL Console',
	'lbl_exec_sql' => 'Execute SQL',
	'desc_exec_sql' => 'Execute SQL you enter in the text box bellow.  You can use only SELECT, INSERT, UPDATE, and DELETE statements.',
	'sql_executed' => 'SQL you executed: ',
	'sql_result' => 'Results: ',
	'sql_error_siud' => 'Error!  You can either SELECT or INSERT or DELETE or UPDATE with this SQL console.'
);

?>
