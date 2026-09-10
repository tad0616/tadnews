<?php
use Xmf\Request;
use XoopsModules\Tadnews\Tools;
use XoopsModules\Tadtools\TadUpFiles;

require_once __DIR__ . '/header.php';

//發文表單的封面圖上傳端點：需登入且在任一分類有發文權（管理員的 chk_user_cate_power 會回傳 [0]）
if (empty($xoopsUser) || !Tools::chk_user_cate_power('post')) {
    exit;
}

$TadUpFiles = new TadUpFiles('tadnews');

$op = Request::getString('op');
$files_sn = Request::getInt('files_sn');
$nsn = Request::getInt('nsn');

if ('get_pic' === $op) {
    echo $TadUpFiles->get_pic_file('images', 'url', $files_sn);
} else {
    if (empty($nsn)) {
        $rand = mt_rand(0, 9999);
        $TadUpFiles->set_col('tmp_news_pic', $rand, 1);
    } else {
        //既有文章換封面前先確認是本人或管理員，否則帶任意 nsn 就能刪掉別人的封面圖
        $Tadnews->get_tad_news($nsn, true);
        $TadUpFiles->set_col('news_pic', $nsn, 1);
        $TadUpFiles->del_files();
    }

    $files_sn = $TadUpFiles->upload_one_file($_FILES['upfile2']['name'], $_FILES['upfile2']['tmp_name'], $_FILES['upfile2']['type'], $_FILES['upfile2']['size'], $xoopsModuleConfig['pic_width'], $xoopsModuleConfig['thumb_width'], null, $xoopsModuleConfig['cover_pic_css'], true, false, 'jpg;gif;png;jpeg'); // xoops-lint-ignore  $_FILES 僅作為參數傳給 TadUpFiles->upload_one_file()
    echo $files_sn;
}
