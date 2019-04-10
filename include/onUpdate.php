<?php

use XoopsModules\Tadnews\Utility;

function xoops_module_update_tadnews(&$module, $old_version)
{
    global $xoopsDB;

    if (!Utility::chk_chk9()) {
        Utility::go_update9();
    }

    if (!Utility::chk_chk10()) {
        Utility::go_update10();
    }

    if (!Utility::chk_chk11()) {
        Utility::go_update11();
    }

    if (!Utility::chk_chk12()) {
        Utility::go_update12();
    }

    if (!Utility::chk_chk13()) {
        Utility::go_update13();
    }

    if (!Utility::chk_chk14()) {
        Utility::go_update14();
    }

    if (!Utility::chk_chk15()) {
        Utility::go_update15();
    }

    if (!Utility::chk_chk16()) {
        Utility::go_update16();
    }

    if (!Utility::chk_chk17()) {
        Utility::go_update17();
    }

    if (!Utility::chk_chk18()) {
        Utility::go_update18();
    }

    if (!Utility::chk_chk19()) {
        Utility::go_update19();
    }

    if (Utility::chk_chk20()) {
        Utility::go_update20();
    }
    if (Utility::chk_chk21()) {
        Utility::go_update21();
    }

    if (Utility::chk_chk22()) {
        Utility::go_update22();
    }

    //調整檔案上傳欄位col_sn為mediumint(9)格式
    if (Utility::chk_files_center()) {
        Utility::go_update_files_center();
    }

    if (Utility::chk_uid()) {
        Utility::go_update_uid();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . "/modules/tadnews/fckeditor";
    if (is_dir($old_fckeditor)) {
        Utility::delete_directory($old_fckeditor);
        Utility::delete_directory(XOOPS_ROOT_PATH . "/modules/tadnews/dhtmlgoodies_calendar");
    }
    Utility::chk_tadnews_block();

    //新增檔案欄位
    if (Utility::chk_fc_tag()) {
        Utility::go_fc_tag();
    }

    //調整檔案上傳欄位col_id的預設值
    if (Utility::chk_data_center_col_id()) {
        Utility::go_update_data_center_col_id();
    }

    return true;
}
