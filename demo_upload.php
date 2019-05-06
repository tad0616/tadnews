<?php
use XoopsModules\Tadtools\TadUpFiles;

require_once __DIR__ . '/header.php';
$TadUpFiles = new TadUpFiles('tadnews');


$op = isset($_POST['op']) ? $_POST['op'] : '';

if ('get_pic' === $op) {
    $files_sn = isset($_POST['files_sn']) ? (int)$_POST['files_sn'] : 0;
    echo $TadUpFiles->get_pic_file('images', 'url', $files_sn);
} else {
    $nsn = (int)$_REQUEST['nsn'];

    if (empty($nsn)) {
        $rand = mt_rand(0, 9999);
        $TadUpFiles->set_col('tmp_news_pic', $rand, 1);
    } else {
        $TadUpFiles->set_col('news_pic', $nsn, 1);
        $TadUpFiles->del_files();
    }

    $files_sn = $TadUpFiles->upload_one_file($_FILES['upfile2']['name'], $_FILES['upfile2']['tmp_name'], $_FILES['upfile2']['type'], $_FILES['upfile2']['size'], $xoopsModuleConfig['pic_width'], $xoopsModuleConfig['thumb_width'], null, $xoopsModuleConfig['cover_pic_css'], true);

    echo $files_sn;
}
