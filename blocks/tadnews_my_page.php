<?php
use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

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

    $cates = get_all_news_cate();

    $options_arr = explode(',', $options[0]);

    $order = empty($options[0]) ? '' : "field( `nsn` , {$options[0]}) ,";

    $sql = 'select * from ' . $xoopsDB->prefix('tad_news') . " where enable='1' order by  $order start_day desc";
    //die($sql);
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();
    $opt = '';
    while (list($nsn, $ncsn, $news_title, $news_content, $start_day, $end_day, $enable, $uid, $passwd, $enable_group) = $xoopsDB->fetchRow($result)) {
        $news_title = $myts->htmlSpecialChars($news_title);
        if (in_array($nsn, $options_arr)) {
            $opt2 .= "<option value=\"$nsn\">[{$nsn}][ {$cates[$ncsn]} ] {$news_title}</option>";
        } else {
            $opt .= "<option value=\"$nsn\">[{$nsn}][ {$cates[$ncsn]} ] {$news_title}</option>";
        }
    }

    $form = '
    <script type="text/javascript" src="' . XOOPS_URL . '/modules/tadnews/class/tmt_core.js"></script>
	<script type="text/javascript" src="' . XOOPS_URL . "/modules/tadnews/class/tmt_spry_linkedselect.js\"></script>
	<script type=\"text/javascript\">
	function getOptions()
	{

    var values = [];
    var sel = document.getElementById('destination');
    for (var i=0, n=sel.options.length;i<n;i++) {
        if (sel.options[i].value) values.push(sel.options[i].value);
    }
	    document.getElementById('all_my_news').value=values.join(',');
	}
    </script>

    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MY_PAGE . "</lable>
            <div class='my-content'>
            <table class='form_tbl' style='width:auto'>
                <tr>
                    <td style='vertical-align:top;'>
                        <select name=\"repository\" id=\"repository\" size=\"12\" multiple=\"multiple\"	tmt:linkedselect=\"true\" style='width: 300px;'>
                        $opt
                        </select>
                    </td>
                    <td style='vertical-align:middle'>
                    <button type=\"button\" onclick=\"tmt.spry.linkedselect.util.moveOptions('repository', 'destination');getOptions();\"><img src=\"" . XOOPS_URL . "/modules/tadnews/images/right.png\"></button><br>
                    <button type=\"button\" onclick=\"tmt.spry.linkedselect.util.moveOptions('destination' , 'repository');getOptions();\"><img src=\"" . XOOPS_URL . "/modules/tadnews/images/left.png\"></button><br><br>

                    <button type=\"button\" onclick=\"tmt.spry.linkedselect.util.moveOptionUp('destination');getOptions();\"><img src=\"" . XOOPS_URL . "/modules/tadnews/images/up.png\"></button><br>
                    <button type=\"button\" onclick=\"tmt.spry.linkedselect.util.moveOptionDown('destination');getOptions();\"><img src=\"" . XOOPS_URL . "/modules/tadnews/images/down.png\"></button>
                    </td>
                    <td style='vertical-align:top;'>
                        <select id=\"destination\" size=\"12\" multiple=\"multiple\" tmt:linkedselect=\"true\" style='width: 300px;'>
                        $opt2
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan=4>
                        <input type='hidden' name='options[0]' id='all_my_news' value='{$options[0]}'>
                    </td>
                </tr>
                </table>
            </div>
        </li>
    </ol>
	";

    return $form;
}
