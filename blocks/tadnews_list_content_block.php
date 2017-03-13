<?php
include_once XOOPS_ROOT_PATH . "/modules/tadnews/block_function.php";

//區塊主函式 (條列式新聞)
function tadnews_list_content_block_show($options)
{
    global $xoTheme;

    $block['jquery_path']    = get_jquery();
    $block['randStr']        = randStr(8);
    $block['num']            = $options[0];
    $block['summary_length'] = $options[1];
    $block['summary_css']    = $options[2];
    $block['title_length']   = $options[3];
    $block['show_cover']     = $options[4];
    $block['cover_css']      = $options[5];
    $block['start_from']     = $options[6];

    if (!is_numeric($options[7]) and strpos($options[7], ',') === false) {
        $options[7] = '';
    }

    $block['show_ncsn']    = isset($options[7]) ? $options[7] : "";
    $block['display_mode'] = empty($options[8]) ? "list" : $options[8];
    $block['show_button']  = $options[9];
    $block['HTTP_HOST']    = get_xoops_url();

    $block['ncsn'] = get_all_news_cate($options[7]);
    $block['tag']  = get_all_news_tag();
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');
    return $block;
}

//區塊編輯函式
function tadnews_list_content_block_edit($options)
{

    $options4_1 = ($options[4] == "1") ? "checked" : "";
    $options4_0 = ($options[4] == "0") ? "checked" : "";
    $options8_1 = ($options[8] == "list") ? "checked" : "";
    $options8_0 = ($options[8] == "table") ? "checked" : "";
    $chked1_0   = ($options[9] == "1") ? "checked" : "";
    $chked1_1   = ($options[9] == "0") ? "checked" : "";

    $option = block_news_cate($options[7]);

    $form = "{$option['js']}
    <table>
    <tr><th>
    " . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM0 . "
    </th><td>
    <INPUT type='text' name='options[0]' value='{$options[0]}' size=6>
    </td></tr>
    <tr><th>
    " . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1 . "
    </th><td>
    <INPUT type='text' name='options[1]' value='{$options[1]}' size=6>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1_DESC . "
    </td></tr>
    <tr><th>
    " . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2 . "
    </th><td>
    <textarea name='options[2]' style='width:400px;height:40px;font-family:Arial;font-size:13px;'>{$options[2]}</textarea>
    <div>ex: <span style='color:#0066CC;font-size:11px;'>color:gray;font-size:11px;margin-top:3px;line-height:150%;</span></div>
    </td></tr>
    <tr><th>
    " . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3 . "" . _MB_TADNEWS_LIST_TEMPLATE_NOTE . "
    </th><td>
    <INPUT type='text' name='options[3]' value='{$options[3]}' size=6>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3_DESC . "
    </td></tr>
    <tr><th>
    " . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM4 . "" . _MB_TADNEWS_LIST_TEMPLATE_NOTE . "
    </th><td>
    <INPUT type='radio' name='options[4]' value='1' $options4_1>" . _YES . "
    <INPUT type='radio' name='options[4]' value='0' $options4_0>" . _NO . "
    </td></tr>
    <tr><th>
    " . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM5 . "" . _MB_TADNEWS_LIST_TEMPLATE_NOTE . "
    </th><td>
    <textarea name='options[5]' style='width:400px;height:40px;font-family:Arial;font-size:13px;'>{$options[5]}</textarea>
    <div>ex: <span style='color:#0066CC;font-size:11px;'>width:60px;height:30px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;</span></div>
    </td></tr>
    <tr><th>
    " . _MB_TADNEWS_START_FROM . "
    </th><td>
    <INPUT type='text' name='options[6]' value='{$options[6]}' size=6>
    </td></tr>
    <tr><th>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</th><td>{$option['form']}
    <INPUT type='hidden' name='options[7]' id='bb' value='{$options[7]}'></td></tr>

    <tr><th>
    " . _MB_TADNEWS_LIST_TEMPLATE . "
    </th><td>
    <INPUT type='radio' name='options[8]' value='list' $options8_1>" . _MB_TADNEWS_LIST_TEMPLATE_LIST . "
    <INPUT type='radio' name='options[8]' value='table' $options8_0>" . _MB_TADNEWS_LIST_TEMPLATE_TABLE . "
    </td></tr>
    <tr><th>
    " . _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM1 . "
    </th><td>
    <INPUT type='radio' $chked1_0 name='options[9]' value='1'>" . _YES . "
    <INPUT type='radio' $chked1_1 name='options[9]' value='0'>" . _NO . "
    </td></tr>
    </table>
    ";
    return $form;
}
