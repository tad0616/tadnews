<?php
use XoopsModules\Tadnews\Tadnews;
use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (跑馬燈區塊)
function tadnews_marquee($options)
{
    global $xoTheme;
    $ncsn_arr = [];
    if (isset($options[1])) {
        $ncsn_arr = explode(',', $options[1]);
    }

    $Tadnews = new Tadnews();

    $Tadnews->set_show_num($options[0]);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $Tadnews->set_show_mode('list');
    $Tadnews->set_news_kind('news');
    $Tadnews->set_use_star_rating(false);
    $block = $Tadnews->get_news('return');
    if (empty($block['page'])) {
        return;
    }

    $direction = in_array($options[2] ?? '', ['left', 'right', 'up', 'down'], true) ? $options[2] : 'down';
    $raw_speed = (int) ($options[3] ?? 5000);
    if ($raw_speed <= 0) {
        $speed = 50;
    } elseif ($raw_speed >= 500) {
        // 相容舊版 jQuery.Marquee 的 duration（毫秒）設定，換算為 TadMarquee 的 px/s
        $speed = max(20, min(200, (int) round(250000 / $raw_speed)));
    } else {
        $speed = $raw_speed;
    }

    $marquee_items = [];
    foreach ($block['page'] as $page) {
        // TadMarquee 設了 link 後外層就是 <a>，prefix_tag 原本是 <a> 需轉為 <span> 避免巢狀連結
        $prefix_tag = preg_replace('/<a\b([^>]*)>(.*?)<\/a>/is', '<span$1>$2</span>', (string) ($page['prefix_tag'] ?? ''));
        $prefix_tag = preg_replace('/\s+href=(["\']).*?\1/i', '', (string) $prefix_tag);

        $item_html = trim(
            (string) ($page['post_date'] ?? '') . ' '
            . (string) ($page['pic'] ?? '') . ' '
            . $prefix_tag . ' '
            . (!empty($page['need_sign']) ? '<img src="' . htmlspecialchars((string) $page['need_sign'], ENT_QUOTES, _CHARSET) . '" alt="" style="margin:3px;"> ' : '')
            . (string) ($page['enable_txt'] ?? '') . ' '
            . (string) ($page['today_pic'] ?? '') . ' '
            . (string) ($page['news_title'] ?? '')
        );

        $marquee_items[] = [
            'type'      => 'html',
            'content'   => $item_html,
            'link'      => XOOPS_URL . '/modules/tadnews/' . $page['link_page'] . '?nsn=' . (int) $page['nsn'],
            'target'    => '_self',
            'ariaLabel' => strip_tags((string) ($page['news_title'] ?? '')),
        ];
    }

    $block['direction']          = $direction;
    $block['speed']              = $speed;
    $block['css']                = empty($options[4]) ? '' : (string) $options[4];
    $block['item_css']           = empty($options[5]) ? '' : (string) $options[5];
    $block['randStr']            = Utility::randStr();
    $block['marquee_items_json'] = json_encode($marquee_items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP);

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addStylesheet('modules/tadtools/tad_marquee/tad_marquee.css');
    $xoTheme->addScript('modules/tadtools/tad_marquee/tad_marquee.js');

    return $block;
}

//區塊編輯函式
function tadnews_marquee_edit($options)
{
    $option = Tools::block_news_cate($options[1]);

    $left = 'left' === $options[2] ? 'checked' : '';
    $right = 'right' === $options[2] ? 'checked' : '';
    $up = 'up' === $options[2] ? 'checked' : '';
    $down = 'down' === $options[2] ? 'checked' : '';

    $form = "{$option['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$option['form']}
                <input type='hidden' name='options[1]' id='bb' value='{$options[1]}'>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_DIRECTION . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[2]' value='left' $left>" . _MB_TADNEWS_MARQUEE_DIRECTION_LEFT . "
                <input type='radio' name='options[2]' value='right' $right>" . _MB_TADNEWS_MARQUEE_DIRECTION_RIGHT . "
                <input type='radio' name='options[2]' value='up' $up>" . _MB_TADNEWS_MARQUEE_DIRECTION_UP . "
                <input type='radio' name='options[2]' value='down' $down>" . _MB_TADNEWS_MARQUEE_DIRECTION_DOWN . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_DURATION . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[3]' value='{$options[3]}' size=6>
                <span class='my-help'>" . _MB_TADNEWS_MARQUEE_DIRECTION_DESC . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_CSS . "</lable>
            <div class='my-content'>
            <textarea name='options[4]' class='my-input'>{$options[4]}</textarea>
                <span class='my-example'><br>" . _MB_TADNEWS_MARQUEE_CSS_EXAMPLE . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_ITEM_CSS . "</lable>
            <div class='my-content'>
            <textarea name='options[5]' class='my-input'>{$options[5]}</textarea>
                <span class='my-example'><br>
                line-height:1.4;<br>
                margin:5px;
                </span>
            </div>
        </li>
    </ol>";

    return $form;
}
