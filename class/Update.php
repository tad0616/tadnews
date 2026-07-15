<?php

namespace XoopsModules\Tadnews;

use XoopsModules\Tadtools\Utility;

/*
Update Class Definition

You may not change or alter any portion of this comment or credits of
supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit
authors.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

/**
 * Class Update
 */
class Update
{
    // 檢查並更新 tad_news 表格
    public static function chk_tad_news()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_news');
        $sql = "SHOW FULL COLUMNS FROM `$table`";
        $result = $xoopsDB->queryF($sql);
        $fields = [];
        while ($row = $xoopsDB->fetchArray($result)) {
            $fields[$row['Field']] = $row;
        }

        // chk9: always_top_date
        if (!isset($fields['always_top_date'])) {
            $sql = "ALTER TABLE `$table` ADD `always_top_date` DATETIME NOT NULL";
            $xoopsDB->queryF($sql);
        }

        // chk11: have_read_group
        if (!isset($fields['have_read_group'])) {
            $sql = "ALTER TABLE `$table` ADD `have_read_group` VARCHAR(255) NOT NULL DEFAULT ''";
            $xoopsDB->queryF($sql);
        }

        // chk13: page_sort
        if (!isset($fields['page_sort'])) {
            $sql = "ALTER TABLE `$table` ADD `page_sort` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0'";
            $xoopsDB->queryF($sql);
        }

        // chk20: news_content
        if (isset($fields['news_content']) && $fields['news_content']['Type'] === 'text') {
            $sql = "ALTER TABLE `$table` CHANGE `news_content` `news_content` LONGTEXT NOT NULL";
            $xoopsDB->queryF($sql);
        }

        // chk_uid: uid
        if (isset($fields['uid']) && strpos($fields['uid']['Type'], 'smallint') !== false) {
            $sql = "ALTER TABLE `$table` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
            $xoopsDB->queryF($sql);
        }

        // Add Indexes
        self::add_index($table);
    }

    // 檢查並更新 tadnews_files_center 表格 (含舊資料遷移)
    public static function chk_tadnews_files_center()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tadnews_files_center');
        $old_table = $xoopsDB->prefix('tad_news_files');

        // Check tables existence
        $tables = [];
        $result = $xoopsDB->queryF("SHOW TABLES");
        while (list($t) = $xoopsDB->fetchRow($result)) {
            $tables[] = $t;
        }

        // chk10: Rename and Migrate legacy table if needed
        if (in_array($old_table, $tables) && !in_array($table, $tables)) {
            Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews');
            Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/cate');
            Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/file');
            Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/image');
            Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/image/.thumbs');

            //建立電子報佈景
            Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/themes');
            if (is_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/themes/bluefreedom2')) {
                Utility::delete_directory(XOOPS_ROOT_PATH . '/uploads/tadnews/themes/bluefreedom2');
            }
            Utility::full_copy(XOOPS_ROOT_PATH . '/modules/tadnews/images/bluefreedom2', XOOPS_ROOT_PATH . '/uploads/tadnews/themes/bluefreedom2');

            //表格改名
            $sql = "RENAME TABLE `$old_table` TO `$table`";
            $xoopsDB->queryF($sql);

            //修改表格
            $sql = "ALTER TABLE `$table`
                CHANGE `fsn` `files_sn` SMALLINT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT,
                CHANGE `nsn` `col_sn` SMALLINT( 5 ) UNSIGNED NOT NULL,
                ADD `col_name` VARCHAR( 255 ) NOT NULL AFTER `files_sn` ,
                ADD `sort` SMALLINT UNSIGNED NOT NULL AFTER `col_sn` ,
                ADD `kind` ENUM( 'img', 'file' ) NOT NULL AFTER `sort`,
                ADD `description` TEXT NOT NULL AFTER `file_type`";
            $xoopsDB->queryF($sql);

            //套入描述以及欄位名稱
            $sql = "update `$table` set `col_name`='nsn', `description`=`file_name`";
            $xoopsDB->queryF($sql);

            $sql = "SELECT files_sn,file_name,file_type,description,col_name,col_sn FROM `$table`";
            $result = $xoopsDB->queryF($sql);
            while (list($files_sn, $file_name, $file_type, $description, $col_name, $col_sn) = $xoopsDB->fetchRow($result)) {
                $kind = ('image' === mb_substr($file_type, 0, 5)) ? 'img' : 'file';
                $new_file_name = "{$col_name}_{$col_sn}_{$files_sn}" . mb_substr($description, -4);
                if ('file' === $kind) {
                    Utility::rename_win(XOOPS_ROOT_PATH . "/uploads/tadnews/file/{$col_sn}_{$description}", XOOPS_ROOT_PATH . "/uploads/tadnews/file/$new_file_name");
                } else {
                    Utility::rename_win(XOOPS_ROOT_PATH . "/uploads/tadnews/file/{$col_sn}_{$description}", XOOPS_ROOT_PATH . "/uploads/tadnews/image/$new_file_name");
                }

                //更新檔名
                $sql1 = "update `$table` set `file_name`='{$new_file_name}',kind='{$kind}' where files_sn='{$files_sn}'";
                $xoopsDB->queryF($sql1);
            }
        }

        // If table exists (either originally or after rename)
        if ($xoopsDB->queryF("SHOW TABLES LIKE '$table'")) {
            $sql = "SHOW FULL COLUMNS FROM `$table`";
            $result = $xoopsDB->queryF($sql);
            $fields = [];
            while ($row = $xoopsDB->fetchArray($result)) {
                $fields[$row['Field']] = $row;
            }

            // chk18: original_filename, hash_filename, sub_dir
            if (!isset($fields['original_filename'])) {
                $sql = "ALTER TABLE `$table`
                    ADD `original_filename` VARCHAR(255) NOT NULL DEFAULT '',
                    ADD `hash_filename` VARCHAR(255) NOT NULL DEFAULT '',
                    ADD `sub_dir` VARCHAR(255) NOT NULL DEFAULT ''";
                $xoopsDB->queryF($sql);

                $sql = "update `$table` set `original_filename`=`description`";
                $xoopsDB->queryF($sql);
            }

            // chk21: clean hash_filename for news_pic
            $sql = "SELECT count(*) FROM `$table` WHERE `col_name`='news_pic' AND `hash_filename`!=''";
            $result = $xoopsDB->queryF($sql);
            list($count) = $xoopsDB->fetchRow($result);
            if ($count > 0) {
                $sql = "update `$table` set hash_filename='' where `col_name`='news_pic'";
                $xoopsDB->queryF($sql);
            }

            // chk_files_center: col_sn mediumint
            if (isset($fields['col_sn']) && strpos($fields['col_sn']['Type'], 'smallint') !== false) {
                $sql = "ALTER TABLE `$table` CHANGE `col_sn` `col_sn` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT 0";
                $xoopsDB->queryF($sql);
            }

            // chk_fc_tag: upload_date, uid, tag
            if (!isset($fields['tag'])) {
                $sql = "ALTER TABLE `$table`
                    ADD `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳時間',
                    ADD `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
                    ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '註記'";
                $xoopsDB->queryF($sql);
            }

            // add_files_center_index
            self::add_files_center_index($table);
        }
    }

    // 檢查並更新 tad_news_tags 表格
    public static function chk_tad_news_tags()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tad_news_tags');
        $tad_news_table = $xoopsDB->prefix('tad_news');

        // chk15: Create table
        if (!$xoopsDB->queryF("SHOW TABLES LIKE '$table'")) {
            $sql = "CREATE TABLE `$table` (
                `tag_sn` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
                `tag` VARCHAR(255) NOT NULL DEFAULT '',
                `color` VARCHAR(255) NOT NULL DEFAULT '',
                `enable` ENUM('0','1') NOT NULL,
                PRIMARY KEY  (`tag_sn`)
            )";
            $xoopsDB->queryF($sql);

            // Migrate data
            $sql = "SELECT DISTINCT prefix_tag FROM `$tad_news_table` WHERE `prefix_tag`!=''";
            $result = $xoopsDB->query($sql);
            while (list($prefix_tag) = $xoopsDB->fetchRow($result)) {
                $arr = [];
                if (\preg_match("/color[\s]*=[\s]*'([#a-zA-Z0-9]+)'[\s]*>\[(.*)\]/", $prefix_tag, $arr)) {
                    $color = $arr[1];
                    $tag = $arr[2];
                    $sql = "insert into `$table` (`tag` , `color` , `enable`) values('{$tag}' , '{$color}' , '1')";
                    $xoopsDB->queryF($sql);
                    $tag_sn = $xoopsDB->getInsertId();
                    $sql = "update `$tad_news_table` set `prefix_tag`='$tag_sn' where `prefix_tag`='{$prefix_tag}'";
                    $xoopsDB->queryF($sql);
                }
            }
        }

        // chk19: font_color
        $sql = "SHOW FULL COLUMNS FROM `$table`";
        $result = $xoopsDB->queryF($sql);
        $fields = [];
        while ($row = $xoopsDB->fetchArray($result)) {
            $fields[$row['Field']] = $row;
        }

        if (!isset($fields['font_color'])) {
            $sql = "ALTER TABLE `$table` ADD `font_color` VARCHAR(255) NOT NULL DEFAULT '' AFTER `tag`";
            $xoopsDB->queryF($sql);
            $sql = "update `$table` set `font_color`='#ffffff'";
            $xoopsDB->queryF($sql);
        }
    }

    // 檢查並更新 tadnews_data_center 表格
    public static function chk_tadnews_data_center()
    {
        global $xoopsDB;
        $table = $xoopsDB->prefix('tadnews_data_center');

        // chk22: Create table
        if (!$xoopsDB->queryF("SHOW TABLES LIKE '$table'")) {
            $sql = "CREATE TABLE `$table` (
                `mid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT ,
                `col_name` varchar(100) NOT NULL DEFAULT '',
                `col_sn` mediumint(9) unsigned NOT NULL DEFAULT '0',
                `data_name` varchar(100) NOT NULL DEFAULT '',
                `data_value` text NOT NULL ,
                `data_sort` mediumint(9) unsigned NOT NULL DEFAULT '0',
                `col_id` varchar(100) NOT NULL,
                `update_time` datetime NOT NULL,
                PRIMARY KEY (`mid`,`col_name`,`col_sn`,`data_name`,`data_sort`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";
            $xoopsDB->queryF($sql);
        } else {
            $sql = "SHOW FULL COLUMNS FROM `$table`";
            $result = $xoopsDB->queryF($sql);
            $fields = [];
            while ($row = $xoopsDB->fetchArray($result)) {
                $fields[$row['Field']] = $row;
            }

            // chk_data_center_col_id
            if (isset($fields['col_id']) && $fields['col_id']['Default'] === null) {
                $sql = "ALTER TABLE `$table` CHANGE `col_id` `col_id` varchar(100) NOT NULL DEFAULT ''";
                $xoopsDB->queryF($sql);
            }

            // chk_dc_sort
            if (!isset($fields['sort'])) {
                $sql = "ALTER TABLE `$table` ADD `sort` mediumint(9) unsigned COMMENT '顯示順序' after `col_id`";
                $xoopsDB->queryF($sql);
            }
        }
    }

    // 檢查並更新其他表格
    public static function chk_other_tables()
    {
        global $xoopsDB;

        // chk12: tad_news_sign
        $table = $xoopsDB->prefix('tad_news_sign');
        if (!$xoopsDB->queryF("SHOW TABLES LIKE '$table'")) {
            $sql = "CREATE TABLE `$table` (
                `sign_sn` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `nsn` SMALLINT UNSIGNED NOT NULL ,
                `uid` SMALLINT UNSIGNED NOT NULL ,
                `sign_time` DATETIME NOT NULL,
                PRIMARY KEY  (`sign_sn`)
            )";
            $xoopsDB->queryF($sql);
        } else {
            // chk_uid
            $sql = "SHOW FIELDS FROM `$table` WHERE `Field`='uid'";
            $result = $xoopsDB->queryF($sql);
            $row = $xoopsDB->fetchArray($result);
            if ($row && strpos($row['Type'], 'smallint') !== false) {
                $sql = "ALTER TABLE `$table` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
                $xoopsDB->queryF($sql);
            }
        }

        // chk14: tad_news_paper_send_log
        $table = $xoopsDB->prefix('tad_news_paper_send_log');
        if (!$xoopsDB->queryF("SHOW TABLES LIKE '$table'")) {
            $sql = "CREATE TABLE `$table` (
                `npsn` SMALLINT UNSIGNED NOT NULL ,
                `email` VARCHAR(255) NOT NULL DEFAULT '' ,
                `send_time` DATETIME NOT NULL,
                `log`  VARCHAR(255) NOT NULL DEFAULT '' ,
                PRIMARY KEY  (`npsn`,`email`)
            )";
            $xoopsDB->queryF($sql);
        }

        // chk16: tadnews_rank
        $table = $xoopsDB->prefix('tadnews_rank');
        if (!$xoopsDB->queryF("SHOW TABLES LIKE '$table'")) {
            $sql = "CREATE TABLE `$table` (
                `col_name` VARCHAR(255) NOT NULL,
                `col_sn` SMALLINT(5) UNSIGNED NOT NULL,
                `rank` TINYINT(3) UNSIGNED NOT NULL,
                `uid` SMALLINT(5) UNSIGNED NOT NULL,
                `rank_date` DATETIME NOT NULL,
                PRIMARY KEY (`col_name`,`col_sn`,`uid`)
            )";
            $xoopsDB->queryF($sql);
        } else {
            // chk_uid
            $sql = "SHOW FIELDS FROM `$table` WHERE `Field`='uid'";
            $result = $xoopsDB->queryF($sql);
            $row = $xoopsDB->fetchArray($result);
            if ($row && strpos($row['Type'], 'smallint') !== false) {
                $sql = "ALTER TABLE `$table` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
                $xoopsDB->queryF($sql);
            }
        }

        // chk17: tad_news_paper (np_title)
        $table = $xoopsDB->prefix('tad_news_paper');
        $sql = "SHOW FIELDS FROM `$table` WHERE `Field`='np_title'";
        $result = $xoopsDB->queryF($sql);
        if ($xoopsDB->getRowsNum($result) == 0) {
            $sql = "ALTER TABLE `$table` ADD `np_title` VARCHAR(255)  NOT NULL DEFAULT ''";
            $xoopsDB->queryF($sql);
        }
    }

    // 清理與區塊檢查
    public static function chk_cleanup()
    {
        global $xoopsDB;

        // Cleanup directories
        $old_fckeditor = XOOPS_ROOT_PATH . '/modules/tadnews/fckeditor';
        if (is_dir($old_fckeditor)) {
            Utility::delete_directory($old_fckeditor);
            Utility::delete_directory(XOOPS_ROOT_PATH . '/modules/tadnews/dhtmlgoodies_calendar');
        }

        // chk_tadnews_block
        require XOOPS_ROOT_PATH . '/modules/tadnews/xoops_version.php';

        // Convert array to map
        $tpl_file_arr = [];
        $tpl_desc_arr = [];
        foreach ($modversion['blocks'] as $i => $block) {
            $show_func = $block['show_func'];
            $tpl_file_arr[$show_func] = $block['template'];
            $tpl_desc_arr[$show_func] = $block['description'];
        }

        $sql = 'SELECT bid,name,visible,show_func,template FROM `' . $xoopsDB->prefix('newblocks') . "` WHERE `dirname` = 'tadnews' ORDER BY `func_num`";
        $result = $xoopsDB->query($sql);
        while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
            if (isset($tpl_file_arr[$show_func]) && $template != $tpl_file_arr[$show_func]) {
                $sql = 'delete from ' . $xoopsDB->prefix('newblocks') . " where bid='{$bid}'";
                $xoopsDB->queryF($sql);

                $sql = 'delete from ' . $xoopsDB->prefix('tplfile') . ' as a left join ' . $xoopsDB->prefix('tplsource') . "  as b on a.tpl_id=b.tpl_id where a.tpl_refid='$bid' and a.tpl_module='tadnews' and a.tpl_type='block'";
                $xoopsDB->queryF($sql);
            } elseif (isset($tpl_desc_arr[$show_func])) {
                $sql = 'update ' . $xoopsDB->prefix('tplfile') . " set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}' where tpl_refid='{$bid}'";
                $xoopsDB->queryF($sql);
            }
        }
    }

    // Helper: Add Index to tad_news
    private static function add_index($table)
    {
        global $xoopsDB;
        $indexes = [
            'ncsn' => 'ncsn',
            'start_day' => 'start_day',
            'end_day' => 'end_day',
            'always_top_always_top_date' => ['always_top', 'always_top_date'],
            'enable' => 'enable'
        ];

        $sql = "SHOW KEYS FROM `$table`";
        $result = $xoopsDB->queryF($sql);
        $existing_keys = [];
        while ($row = $xoopsDB->fetchArray($result)) {
            $existing_keys[$row['Key_name']] = true;
        }

        foreach ($indexes as $key_name => $columns) {
            if (!isset($existing_keys[$key_name])) {
                if (is_array($columns)) {
                    $cols = '`' . implode('`, `', $columns) . '`';
                } else {
                    $cols = "`$columns`";
                }
                $sql = "ALTER TABLE `$table` ADD INDEX `$key_name` ($cols)";
                $xoopsDB->queryF($sql);
            }
        }
    }

    // Helper: Add Index to tadnews_files_center
    private static function add_files_center_index($table)
    {
        global $xoopsDB;

        // Check column length first
        $sql = "SELECT CHARACTER_MAXIMUM_LENGTH
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND COLUMN_NAME = 'col_name'";
        $result = $xoopsDB->queryF($sql);
        list($length) = $xoopsDB->fetchRow($result);

        if ($length > 100) {
            $sql = "ALTER TABLE `{$table}`
                CHANGE `col_name` `col_name` VARCHAR(100)
                NOT NULL DEFAULT ''
                COMMENT '欄位名稱' AFTER `files_sn`";
            $xoopsDB->queryF($sql);
        }

        // Check index
        $sql = "SHOW INDEX FROM `{$table}` WHERE Key_name = 'col_name_col_sn'";
        $result = $xoopsDB->queryF($sql);
        if ($xoopsDB->getRowsNum($result) == 0) {
            $sql = "ALTER TABLE `{$table}`
                ADD INDEX `col_name_col_sn` (`col_name`, `col_sn`)";
            $xoopsDB->queryF($sql);
        }
    }
}
