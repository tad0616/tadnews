<?php
require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

//區塊主函式 (焦點新聞)
function tadnews_focus_news($options)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsOption;

    if (empty($options[0])) {
        return '';
    }

    require_once XOOPS_ROOT_PATH . '/modules/tadnews/class/tadnews.php';

    $tadnews = new tadnews();
    $tadnews->set_view_nsn($options[0]);
    $summary = ('summary' === $options[1]) ? 'page_preak' : 'full';
    $tadnews->set_summary($summary);
    $tadnews->set_cover(true, 'db');
    $tadnews->set_use_star_rating(false);
    $block = $tadnews->get_news('return');

    return $block;
}

//區塊編輯函式
function tadnews_focus_news_edit($options)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsOption;
    $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $sql = 'select a.nsn,a.ncsn,a.news_title,a.passwd,a.start_day,b.not_news,b.nc_title from ' . $xoopsDB->prefix('tad_news') . ' as a left join ' . $xoopsDB->prefix('tad_news_cate') . " as b on a.ncsn=b.ncsn where a.enable='1' and a.start_day < '{$today}' and (a.end_day > '{$today}' or a.end_day='0000-00-00 00:00:00')  order by a.start_day desc";

    $result = $xoopsDB->query($sql) or redirect_header(XOOPS_URL, 3, show_error($sql));
    $option = "<select name='options[0]'>";
    $myts = MyTextSanitizer::getInstance();
    while (false !== (list($nsn, $ncsn, $news_title, $passwd, $start_day, $not_news, $nc_title) = $xoopsDB->fetchRow($result))) {
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
                    <option value='full' " . chk($options[1], 'full', '1', 'selected') . '>' . _MB_TADNEWS_FOCUS_FULL . "</option>
                    <option value='summary' " . chk($options[1], 'summary', '0', 'selected') . '>' . _MB_TADNEWS_FOCUS_SUMMARY . '</option>
                </select>
            </div>
        </li>
    </ol>
    ';

    return $form;
}

//單選回復原始資料函數
if (!function_exists('chk')) {
    function chk($DBV = '', $NEED_V = '', $defaul = '', $return = 'checked')
    {
        if ($DBV == $NEED_V) {
            return $return;
        } elseif (empty($DBV) && '1' == $defaul) {
            return $return;
        }

        return '';
    }
}
