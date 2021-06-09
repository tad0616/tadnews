<?php
use XoopsModules\Tadnews\Update;
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
if (!class_exists('XoopsModules\Tadnews\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}

function xoops_module_update_tadnews(&$module, $old_version)
{
    global $xoopsDB;

    if (!Update::chk_chk9()) {
        Update::go_update9();
    }

    if (!Update::chk_chk10()) {
        Update::go_update10();
    }

    if (!Update::chk_chk11()) {
        Update::go_update11();
    }

    if (!Update::chk_chk12()) {
        Update::go_update12();
    }

    if (!Update::chk_chk13()) {
        Update::go_update13();
    }

    if (!Update::chk_chk14()) {
        Update::go_update14();
    }

    if (!Update::chk_chk15()) {
        Update::go_update15();
    }

    if (!Update::chk_chk16()) {
        Update::go_update16();
    }

    if (!Update::chk_chk17()) {
        Update::go_update17();
    }

    if (!Update::chk_chk18()) {
        Update::go_update18();
    }

    if (!Update::chk_chk19()) {
        Update::go_update19();
    }

    if (Update::chk_chk20()) {
        Update::go_update20();
    }
    if (Update::chk_chk21()) {
        Update::go_update21();
    }

    if (Update::chk_chk22()) {
        Update::go_update22();
    }

    //調整檔案上傳欄位col_sn為mediumint(9)格式
    if (Update::chk_files_center()) {
        Update::go_update_files_center();
    }

    if (Update::chk_uid()) {
        Update::go_update_uid();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . '/modules/tadnews/fckeditor';
    if (is_dir($old_fckeditor)) {
        Utility::delete_directory($old_fckeditor);
        Utility::delete_directory(XOOPS_ROOT_PATH . '/modules/tadnews/dhtmlgoodies_calendar');
    }
    Update::chk_tadnews_block();

    //新增檔案欄位
    if (Update::chk_fc_tag()) {
        Update::go_fc_tag();
    }

    //調整檔案上傳欄位col_id的預設值
    if (Update::chk_data_center_col_id()) {
        Update::go_update_data_center_col_id();
    }

    // data_center 加入 sort
    if (Update::chk_dc_sort()) {
        Update::go_dc_sort();
    }
    return true;
}
