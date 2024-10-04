<?php
use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (焦點新聞)
function tadnews_focus_news($options)
{

    if (empty($options[0])) {
        return '';
    }

    $Tadnews = new Tadnews();
    $Tadnews->set_view_nsn($options[0]);
    $summary = ('summary' === $options[1]) ? 'page_break' : 'full';
    $Tadnews->set_summary($summary);
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_use_star_rating(false);
    $block = $Tadnews->get_news('return');
    return $block;
}

//區塊編輯函式
function tadnews_focus_news_edit($options)
{
    global $xoopsDB;
    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $sql = 'SELECT a.nsn, a.ncsn, a.news_title, a.passwd, a.start_day, b.not_news, b.nc_title FROM `' . $xoopsDB->prefix('tad_news') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_news_cate') . '` AS b ON a.ncsn = b.ncsn WHERE a.enable = 1 AND a.start_day < ? AND (a.end_day > ? OR a.end_day = ?) ORDER BY a.start_day DESC';
    $result = Utility::query($sql, 'sss', [$today, $today, '0000-00-00 00:00:00']) or Utility::web_error($sql, __FILE__, __LINE__);

    $option = "<select name='options[0]'>";
    $myts = \MyTextSanitizer::getInstance();
    while (list($nsn, $ncsn, $news_title, $passwd, $start_day, $not_news, $nc_title) = $xoopsDB->fetchRow($result)) {
        $selected = ($options[0] == $nsn) ? 'selected' : '';
        $start_day = mb_substr($start_day, 0, 10);
        $news_title = $myts->htmlSpecialChars($news_title);
        $option .= "<option value='$nsn' $selected>$start_day [{$nc_title}] $news_title</option>";
    }
    $option .= '</select>';

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_FOCUS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                $option
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_FOCUS_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <select name='options[1]' class='my-input'>
                    <option value='full' " . Utility::chk($options[1], 'full', '1', 'selected') . '>' . _MB_TADNEWS_FOCUS_FULL . "</option>
                    <option value='summary' " . Utility::chk($options[1], 'summary', '0', 'selected') . '>' . _MB_TADNEWS_FOCUS_SUMMARY . '</option>
                </select>
            </div>
        </li>
    </ol>
    ';

    return $form;
}
