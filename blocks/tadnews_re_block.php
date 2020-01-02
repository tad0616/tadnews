<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (最新回應)
function tadnews_b_show_3($options)
{
    global $xoopsDB;
    require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';
    $moduleHandler = xoops_getHandler('module');
    $xoopsModule = $moduleHandler->getByDirname('tadnews');
    $com_modid = $xoopsModule->getVar('mid');
    $sql = 'select com_id,com_text,com_itemid,com_uid from ' . $xoopsDB->prefix('xoopscomments') . " where com_modid='$com_modid' order by com_modified desc limit 0,{$options[0]}";
    //die($sql);
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $block['width'] = $options[1];
    $myts = \MyTextSanitizer::getInstance();
    $i = 0;
    while (list($com_id, $txt, $nsn, $uid) = $xoopsDB->fetchRow($result)) {
        $txt = strip_tags($txt);
        //支援xlanguage
        if (function_exists('xlanguage_ml')) {
            $txt = xlanguage_ml($txt);
        }
        //$txt=xoops_substr($txt , 0 , $options[1] , "...");
        $txt = mb_substr($txt, 0, $options[1], _CHARSET);
        $txt .= '...';
        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        $uid_name = (empty($uid_name)) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;
        $re['uid'] = $uid;
        $re['uid_name'] = $uid_name;
        $re['nsn'] = $nsn;
        $re['com_id'] = $com_id;
        $re['txt'] = $txt;
        $block['re'][$com_id] = $re;
        $i++;
    }
    if (empty($i)) {
        return;
    }

    return $block;
}

//區塊編輯函式
function tadnews_re_edit($options)
{
    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_RE_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_RE_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>
            </div>
        </li>
    </ol>";

    return $form;
}
