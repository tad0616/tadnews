<?php
use XoopsModules\Tadtools\TadUpFiles;

require_once __DIR__ . '/header.php';
$TadUpFiles = new TadUpFiles('tadnews');

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$files_sn = system_CleanVars($_REQUEST, 'files_sn', 0, 'int');
$nsn = system_CleanVars($_REQUEST, 'nsn', 0, 'int');

if ('get_pic' === $op) {
    echo $TadUpFiles->get_pic_file('images', 'url', $files_sn);
} else {
    if (empty($nsn)) {
        $rand = mt_rand(0, 9999);
        $TadUpFiles->set_col('tmp_news_pic', $rand, 1);
    } else {
        $TadUpFiles->set_col('news_pic', $nsn, 1);
        $TadUpFiles->del_files();
    }

    $files_sn = $TadUpFiles->upload_one_file($_FILES['upfile2']['name'], $_FILES['upfile2']['tmp_name'], $_FILES['upfile2']['type'], $_FILES['upfile2']['size'], $xoopsModuleConfig['pic_width'], $xoopsModuleConfig['thumb_width'], null, $xoopsModuleConfig['cover_pic_css'], true, false, 'jpg;gif;png;jpeg');
    echo $files_sn;
}
