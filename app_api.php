<?php
use Xmf\Request;
use XoopsModules\Tadnews\TadNewsRest;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$TadNewsRest = new TadNewsRest();

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ncsn = Request::getInt('ncsn');
$nsn = Request::getInt('nsn');
$num = Request::getInt('num', 10);

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
}

function getToken()
{
    $claims = array(
        'uid' => $_SESSION['xoopsUserId'],
    );

    $rememberTime = 60 * 60 * 24 * 30;
    $token = \Xmf\Jwt\TokenFactory::build('tadnews', $claims, $rememberTime);
}
