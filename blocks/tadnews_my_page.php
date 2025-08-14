<?php
use XoopsModules\Tadnews\Tadnews;
use XoopsModules\Tadnews\Tools;
use XoopsModules\Tadtools\Tmt;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (自選文章)
function tadnews_my_page($options)
{
    global $xoTheme;
    if (empty($options[0])) {
        return '';
    }

    $nsn_arr = explode(',', $options[0]);

    $Tadnews = new Tadnews();
    $Tadnews->set_show_mode('table');
    $Tadnews->set_view_nsn($nsn_arr);
    $Tadnews->set_use_star_rating(false);
    $block = $Tadnews->get_news('return');

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    return $block;
}

//區塊編輯函式
function tadnews_my_page_edit($options)
{
    global $xoopsDB;

    $cates = Tools::get_all_news_cate();

    $option_arr = explode(',', $options[0]);

    $sql    = 'SELECT `nsn`, `ncsn`, `news_title` FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `enable`=?  ORDER BY `nsn` DESC';
    $result = Utility::query($sql, 's', [1]) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();

    while (list($nsn, $ncsn, $news_title) = $xoopsDB->fetchRow($result)) {
        $news_title = $myts->htmlSpecialChars($news_title);
        if (in_array($nsn, $option_arr)) {
            $to_arr[$nsn] = "[{$nsn}][ {$cates[$ncsn]} ] {$news_title}";
        } else {
            $from_arr[$nsn] = "[{$nsn}][ {$cates[$ncsn]} ] {$news_title}";
        }
    }

    $new_to_arr = [];
    foreach ($option_arr as $nsn) {
        $new_to_arr[$nsn] = $to_arr[$nsn];
    }
    $hidden_arr = [];
    $tmt_box    = Tmt::render('all_my_news', $from_arr, $new_to_arr, $hidden_arr, false, true, '15rem', 'repository', 'destination', ',', '', [], 'options[0]');

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MY_PAGE . "</lable>
            <div class='my-content'>
                {$tmt_box}
            </div>
        </li>
    </ol>
	";

    return $form;
}
