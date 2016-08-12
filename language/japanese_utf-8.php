<?php

###############################################################################
# japanese_utf-8.php
# This is the japanese_utf-8 language page for the Geeklog Dbman Plug-in!
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
 * This is the japanese_utf-8 language page for the Geeklog Dbman Plug-in! 
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
	'access_denied' => 'アクセスは拒否されました。',
	'access_denied_msg' => 'このプラグインの管理権限がないのに管理画面にアクセスしようとしました。この行為は記録されます。',
	'add_drop_table' => '"DROP TABLE IF EXISTS"を追加する',
	'admin'	=> 'プラグイン管理',
	'backup_blob' => 'BLOBもバックアップする(phpMyAdminと互換性はありません！)',
	'backup_failure' => 'バックアップできませんでした。詳しくはエラーログをご覧ください。',
	'backup_file' => 'バックアップされているファイル',
	'backup_now' => 'バックアップ開始',
	'backup_success' => 'バックアップしました。',
	'bytes' => 'バイト',
	'check_all' => 'すべてチェックする',
	'compress_data' => 'gzip(\'.gz\') で圧縮する',
	'couldnt_get_table_contents' => "(テーブルの内容を取得できませんでした。",
	'couldnt_get_table_contents2' => 'を削除してから，やり直してください。)',
	'couldnt_write_backup' => "データをバックアップファイルに書き込めませんでした。",
	'db_explanation_backup' => 'データベースのバックアップを取るには「バックアップ作成」をクリックしてください。',
	'db_explanation_list' => 'バックアップ/リストアを選択してください。',
	'db_explanation_restore' => 'リストアするデータベースにチェックマークを入れから，「次へ」をクリックしてください。',
	'db_explanation_restore_option' => '%s からリストアする際のオプションを選択してください。<strong>テーブル構造</strong>をチェックすると，テーブルが作り直されます（既存のデータは削除されます）。<strong>データ</strong>をチェックすると，既存のデータを削除せずにデータがリストアされます。重複するレコードがある場合は上書きされます。<br><strong>警告: リストアの機能は開発中の簡易的なものです。Dbman プラグインはどのテーブルをリストアするかどのレコードが上書きされるかは関知しません。データを使用中のテーブルにリストアしたり，リストアするレコードのサイズが大きすぎる場合は，リストアは失敗し，テーブルが破損する可能性があります。</strong>',  /* %s = backup file */
	'download_as_file' => 'ファイルとしてダウンロードする',
	'enabled' => 'アンインストールする前に，プラグインを無効にしてください。',
	'install' => 'インストール',
	'installdoc' => 'インストール文書',
	'installed' => 'プラグインはインストールされています。',
  'install_failed' => 'インストールに失敗しました。エラーログをご覧ください。',
	'install_header' => 'プラグインのインストール/アンインストール',
	'install_success'	=> 'インストールに成功しました。',
	'last_ten_backups' => '最近のバックアップ(10世代分)',
	'menu_backup' => 'バックアップ作成',
	'menu_list' => 'ファイル一覧',
	'menu_restore' => 'データベースのリストア',
	'next' => '次へ &gt;&gt;',
	'no_file_selected' => 'バックアップファイルが選択されていません。',
	'not_writable' => 'Backups ディレクトリが書き込み不可になっています。',
	'operation' => '操作',
	'option' => '＜オプション＞',
	'other_options' => '＜他のオプション＞',
  'plugin' => 'Dbmanプラグイン',
	'readme' => 'ちょっと待ってください。「インストール」をクリックする前に，お読みください：',
	'restore' => 'リストアする',
	'restore_blob' => 'BLOBもリストアする',
	'restore_failure' => 'リストアできませんでした。詳しくはエラーログをご覧ください。',
	'restore_now' => 'リストア開始',
	'restore_success' => 'リストアしました。',
	'restore_header1' => 'テーブル名',
	'restore_header2' => 'テーブル構造',
	'restore_header3' => 'データ',
	'size' => 'サイズ',
	'uncheck_all' => 'すべてチェックを外す',
	'uninstall' => 'アンインストール',
	'uninstalled' => 'プラグインはインストールされていません。',
	'uninstall_msg'	=> 'アンインストールに成功しました。',
	'warning'  => '警告！　プラグインが有効なままです',
	'dbman' => 'Dbman'
);

?>