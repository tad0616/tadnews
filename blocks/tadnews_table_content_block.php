<?php
use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadnews\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}

//區塊主函式 (表格式新聞)
function tadnews_table_content_block_show($options)
{
    global $xoopsModule, $xoTheme;

    $block['randStr'] = Utility::randStr(8);
    $block['num'] = $options[0];
    $block['show_button'] = $options[1];
    $block['cell1'] = $options[2];
    $block['cell2'] = $options[3];
    $block['cell3'] = $options[4];
    $block['cell4'] = $options[5];
    $block['cell5'] = $options[6];
    $block['start_from'] = $options[7];
    $block['show_ncsn'] = isset($options[8]) ? $options[8] : '';
    $block['searchbar'] = $options[9];

    $block['ncsn'] = Tools::get_all_news_cate($options[8]);
    $block['tag'] = Tools::get_all_news_tag();
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addStylesheet('modules/tadnews/css/module.css');
    $modhandler = xoops_gethandler('module');
    $xoopsModule = $modhandler->getByDirname("tadnews");
    $config_handler = xoops_gethandler('config');
    $xoopsModuleConfig = $config_handler->getConfigsByCat(0, $xoopsModule->mid());
    if ($xoopsModuleConfig['use_table_shadow']) {
        $xoTheme->addStylesheet('modules/tadnews/css/module2.css');
    }
    $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');

    return $block;
}

//區塊編輯函式
function tadnews_table_content_block_edit($options)
{
    $chked1_0 = ('1' == $options[1]) ? 'checked' : '';
    $chked1_1 = ('0' == $options[1]) ? 'checked' : '';
    $searchbar_0 = ('0' == $options[9]) ? 'checked' : '';
    $searchbar_1 = ('1' == $options[9]) ? 'checked' : '';

    $defOptions = [2 => 'start_day', 'news_title', 'uid', 'ncsn', 'counter'];
    $ShowColArr = ['start_day' => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_1, 'news_title' => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_2, 'uid' => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_3, 'ncsn' => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_4, 'counter' => _MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_5];
    $SetColTitle = [2 => _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM2, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM3, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM4, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM5, _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM6];

    $show_col = '';

    for ($i = 2; $i <= 6; $i++) {
        $allOption = "<option value='hide'>" . _MB_TADNEWS_HIDE . "</option>\n";
        foreach ($ShowColArr as $col_name => $col_title) {
            if (empty($options[$i])) {
                $options[$i] = $defOptions[$i];
            }

            $selected = ($options[$i] == $col_name) ? 'selected' : '';

            $allOption .= "<option value='$col_name' $selected>$col_title</option>\n";
        }
        $show_col .= "
        <li class='my-row'>
            <lable class='my-label'>{$SetColTitle[$i]}</lable>
            <div class='my-content'>
                <select name='options[{$i}]' class='my-input'>
                $allOption
                </select>
            </div>
        </li>";
    }

    $option = Tools::block_news_cate($options[8]);

    $form = "{$option['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='radio' $chked1_0 name='options[1]' value='1'>" . _YES . "
                <input type='radio' $chked1_1 name='options[1]' value='0'>" . _NO . "
            </div>
        </li>
        $show_col
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_START_FROM . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[7]' value='{$options[7]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$option['form']}
                <input type='hidden' name='options[8]' id='bb' value='{$options[8]}'>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SEARCHBAR . "</lable>
            <div class='my-content'>
                <input type='radio' $searchbar_1 name='options[9]' value='1'>" . _YES . "
                <input type='radio' $searchbar_0 name='options[9]' value='0'>" . _NO . '
            </div>
        </li>
    </ol>';

    return $form;
}
