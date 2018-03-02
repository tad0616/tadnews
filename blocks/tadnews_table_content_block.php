<?php
include_once XOOPS_ROOT_PATH . "/modules/tadnews/block_function.php";

//區塊主函式 (顯示新聞內容)
function tadnews_table_content_block_show($options)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsOption, $xoTheme;

    $block['jquery_path'] = get_jquery();
    $block['randStr']     = randStr(8);
    $block['num']         = $options[0];
    $block['show_button'] = $options[1];
    $block['cell1']       = $options[2];
    $block['cell2']       = $options[3];
    $block['cell3']       = $options[4];
    $block['cell4']       = $options[5];
    $block['cell5']       = $options[6];
    $block['start_from']  = $options[7];
    $block['show_ncsn']   = isset($options[8]) ? $options[8] : "";
    $block['searchbar']   = $options[9];
    $block['HTTP_HOST']   = get_xoops_url();

    $block['ncsn'] = get_all_news_cate($options[8]);
    $block['tag']  = get_all_news_tag();
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');
    return $block;
}

//區塊編輯函式
function tadnews_table_content_block_edit($options)
{
    $chked1_0    = ($options[1] == "1") ? "checked" : "";
    $chked1_1    = ($options[1] == "0") ? "checked" : "";
    $searchbar_0 = ($options[9] == "0") ? "checked" : "";
    $searchbar_1 = ($options[9] == "1") ? "checked" : "";

    $defOptions  = array(2 => 'start_day', 'news_title', 'uid', 'ncsn', 'counter');
    $ShowColArr  = array("start_day" => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_1, "news_title" => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_2, "uid" => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_3, "ncsn" => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_4, "counter" => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_5);
    $SetColTitle = array(2 => _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM2, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM3, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM4, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM5, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM6);

    $show_col = "";

    for ($i = 2; $i <= 6; $i++) {
        $allOption = "<option value='hide'>" . _MB_TADNEWS_HIDE . "</option>\n";
        foreach ($ShowColArr as $col_name => $col_title) {
            if (empty($options[$i])) {
                $options[$i] = $defOptions[$i];
            }

            $selected = ($options[$i] == $col_name) ? "selected" : "";

            $allOption .= "<option value='$col_name' $selected>$col_title</option>\n";
        }
        $show_col .= "
        <tr><th style='width:100px;'>
        {$SetColTitle[$i]}
        </th><td>
        <select name='options[{$i}]'>
        $allOption
        </select>
        </td></tr>";
    }

    $option = block_news_cate($options[8]);

    $form = "{$option['js']}
      <table>
        <tr><th>
        " . _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM0 . "
        </th><td>
        <input type='text' name='options[0]' value='{$options[0]}'>
        </td></tr>

        <tr><th>
        " . _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM1 . "
        </th><td>
        <input type='radio' $chked1_0 name='options[1]' value='1'>" . _YES . "
        <input type='radio' $chked1_1 name='options[1]' value='0'>" . _NO . "
        </td></tr>

        $show_col

        <tr><th>
        " . _MB_TADNEWS_START_FROM . "
        </th><td>
        <input type='text' name='options[7]' value='{$options[7]}' size=6>
        </td></tr>

        <tr><th>
        " . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "
        </th><td>
        {$option['form']}
        <input type='hidden' name='options[8]' id='bb' value='{$options[8]}'>
        </td></tr>

        <tr><th>
            " . _MB_TADNEWS_SEARCHBAR . "
        </th><td>
            <input type='radio' $searchbar_1 name='options[9]' value='1'>" . _YES . "
            <input type='radio' $searchbar_0 name='options[9]' value='0'>" . _NO . "
        </td></tr>
      </table>
      ";
    return $form;
}
