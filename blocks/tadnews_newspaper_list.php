<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (最新回應)
function tadnews_newspaper_list($options)
{
    global $xoopsDB, $xoopsUser, $xoTheme;
    $xoTheme->addStylesheet('modules/tadtools/css/vertical_menu.css');

    //找出現有設定組
    $sql = 'SELECT a.npsn, a.number, b.title FROM `' . $xoopsDB->prefix('tad_news_paper') . '` AS a, `' . $xoopsDB->prefix('tad_news_paper_setup') . '` AS b WHERE a.nps_sn = b.nps_sn AND b.status = ? ORDER BY a.np_date DESC LIMIT 0, ?';
    $result = Utility::query($sql, 'si', ['1', $options[0]]) or Utility::web_error($sql, __FILE__, __LINE__);

    $i = 0;
    $page = [];
    while (list($npsn, $number, $title) = $xoopsDB->fetchRow($result)) {
        $page[$i]['npsn'] = $npsn;
        $page[$i]['title'] = $title . sprintf(_MB_TADNEWS_NP_TITLE, $number);
        $i++;
    }
    if (empty($page)) {
        return;
    }

    $block['page'] = $page;

    return $block;
}

//區塊編輯函式
function tadnews_newspaper_list_edit($options)
{
    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_NP_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
    </ol>";

    return $form;
}
