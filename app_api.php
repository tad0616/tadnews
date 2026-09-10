<?php
use Xmf\Request;
use XoopsModules\Tadnews\TadNewsRest;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
if (!class_exists('XoopsModules\Tadnews\TadNewsRest')) {
    require __DIR__ . '/preloads/autoloader.php';
}

//回傳純 JSON：關掉 XOOPS 除錯模式的 logger，否則 HTML 會黏在 JSON 後面
if (isset($GLOBALS['xoopsLogger'])) {
    $GLOBALS['xoopsLogger']->activated        = false;
    $GLOBALS['xoopsLogger']->renderingEnabled = false;
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ncsn = Request::getInt('ncsn');
$nsn = Request::getInt('nsn');
$num = Request::getInt('num', 20);
$token = Request::getString('token');
// $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjEsImV4cCI6MTYxNzQ1NzE3Mn0.9Uvxd6kWLzJiZQr9K145BThC5v6OgiX8p4IihRutIW4';
// $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjIsImV4cCI6MTYxNzQ5OTcwN30.eGrbLC52czGECLfeJ5vLCCKWAML6RWEAgQDbB-PWD8g';

$TadNewsRest = new TadNewsRest($token);

switch ($op) {
    case 'get_cates':
        echo $TadNewsRest->get_cates();
        break;

    case 'list_all_news':
        echo $TadNewsRest->list_all_news($ncsn, $num);
        break;

    case 'show_news':
        echo $TadNewsRest->show_news($nsn);
        break;

    default:
        echo $TadNewsRest->user();
        break;
}
