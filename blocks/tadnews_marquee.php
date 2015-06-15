<?php
include_once XOOPS_ROOT_PATH . "/modules/tadnews/block_function.php";

//區塊主函式 (顯示新聞內容)
function tadnews_marquee($options)
{
    global $xoTheme;
    $ncsn_arr = array();
    if (isset($options[1])) {
        $ncsn_arr = explode(',', $options[1]);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadnews/class/tadnews.php";

    $tadnews = new tadnews();

    $tadnews->set_show_num($options[0]);
    $tadnews->set_view_ncsn($ncsn_arr);
    $tadnews->set_show_mode('list');
    $tadnews->set_news_kind("news");
    $block = $tadnews->get_news('return');
    if (empty($block['page'])) {
        return;
    }

    $block['direction'] = empty($options[2]) ? 'down' : $options[2];
    $block['duration']  = empty($options[3]) ? '5000' : $options[3];
    $block['css']       = empty($options[4]) ? '' : $options[4];
    $block['item_css']  = empty($options[5]) ? '' : $options[5];
    $block['randStr']   = randStr();
    $block['jquery']    = get_jquery();
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addScript('modules/tadnews/class/jQuery.Marquee/jquery.marquee.min.js');
    return $block;
}

//區塊編輯函式
function tadnews_marquee_edit($options)
{

    $option = block_news_cate($options[1]);

    $left  = $options[2] == 'left' ? "checked" : "";
    $right = $options[2] == 'right' ? "checked" : "";
    $up    = $options[2] == 'up' ? "checked" : "";
    $down  = $options[2] == 'down' ? "checked" : "";

    $form = "{$option['js']}
  <table>
    <tr>
      <th>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM0 . "</th>
      <td>
        <input type='text' name='options[0]' value='{$options[0]}' size=6>
      </td>
    </tr>

    <tr>
      <th>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</th>
      <td>
        {$option['form']}
        <input type='hidden' name='options[1]' id='bb' value='{$options[1]}'>
      </td>
    </tr>

    <tr>
      <th>" . _MB_TADNEWS_MARQUEE_DIRECTION . "</th>
      <td>
        <input type='radio' name='options[2]' value='left' $left>" . _MB_TADNEWS_MARQUEE_DIRECTION_LEFT . "
        <input type='radio' name='options[2]' value='right' $right>" . _MB_TADNEWS_MARQUEE_DIRECTION_RIGHT . "
        <input type='radio' name='options[2]' value='up' $up>" . _MB_TADNEWS_MARQUEE_DIRECTION_UP . "
        <input type='radio' name='options[2]' value='down' $down>" . _MB_TADNEWS_MARQUEE_DIRECTION_DOWN . "
      </td>
    </tr>

    <tr>
      <th>" . _MB_TADNEWS_MARQUEE_DURATION . "</th>
      <td>
        <input type='text' name='options[3]' value='{$options[3]}' size=6>
        <div style='line-height:150%;'>" . _MB_TADNEWS_MARQUEE_DIRECTION_DESC . "</div>
      </td>
    </tr>

    <tr>
      <th>" . _MB_TADNEWS_MARQUEE_CSS . "</th>
      <td>
        <textarea name='options[4]' style='width:400px;height:40px;font-family:Arial;font-size:13px;'>{$options[4]}</textarea>
        <div style='line-height:150%;'>
          " . _MB_TADNEWS_MARQUEE_CSS_DEFAULT . "<br>
          <span style='color:#0066CC;font-size:11px;'>
            width: 100%; /* 跑馬燈寬度 */<br>
            height:4em; /* 跑馬燈高度 */<br>
            line-height:1.8; /* 跑馬燈行高 */<br>
            border:1px solid #cfcfcf; /* 跑馬燈邊框 */<br>
            background-color:#FCFCFC; /* 跑馬燈底色 */<br>
            box-shadow: 0px 1px 2px 1px #cfcfcf inset; /* 跑馬燈陰影 */
          </span>
        </div>
      </td>
    </tr>

    <tr>
      <th>" . _MB_TADNEWS_MARQUEE_ITEM_CSS . "</th>
      <td>
        <textarea name='options[5]' style='width:400px;height:40px;font-family:Arial;font-size:13px;'>{$options[5]}</textarea>
        <div style='line-height:150%;'>
          " . _MB_TADNEWS_MARQUEE_CSS_DEFAULT . "<br>
          <span style='color:#0066CC;font-size:11px;'>
          line-height:1.4;<br>
          margin:5px;
          </span>
        </div>
      </td>
    </tr>
  </table>
  ";
    return $form;
}
