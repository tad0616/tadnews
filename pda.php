<?php
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadnews\Tadnews;

/*-----------引入檔案區--------------*/
require_once dirname(dirname(__DIR__)) . '/mainfile.php';

require_once XOOPS_ROOT_PATH . '/modules/tadnews/function.php';

/*-----------function區--------------*/

function list_tadnews($ncsn = '')
{
    global $xoopsModuleConfig, $Tadnews;

    xoops_loadLanguage('blocks', 'tadnews');
    xoops_loadLanguage('main', 'tadnews');
    xoops_loadLanguage('modinfo', 'tadnews');

    $num = (!empty($_POST['n'])) ? (int) $_POST['n'] : 10;
    $p = (!empty($_POST['p'])) ? (int) $_POST['p'] : 0;
    $start = $p * $num;

    $Tadnews->set_show_num($num);
    $Tadnews->set_skip_news($start);
    $Tadnews->set_news_kind('news');
    $Tadnews->set_summary($xoopsModuleConfig['summary_lengths']);
    if ($ncsn > 0) {
        $Tadnews->set_view_ncsn($ncsn);
        $Tadnews->set_show_mode($xoopsModuleConfig['cate_show_mode']);
        $ncsn_param = "&ncsn={$ncsn}";
    } else {
        $Tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    }
    //$Tadnews->set_title_length(20);
    $Tadnews->set_cover(true, 'db');

    $tnews = $Tadnews->get_news('return');

    $all_news = '';

    foreach ($tnews['page'] as $news) {
        $content = strip_tags($news['content']);
        $item_image = (empty($news['image_thumb'])) ? '' : "<div class='item-media'><img data-src='{$news['image_thumb']}' class='lazy lazy-fadein'></div>";
        $all_news .= "
            <li>
                <a href='pda.php?op=news&nsn={$news['nsn']}{$ncsn_param}' class='item-link item-content'>
                    {$item_image}
                    <div class='item-inner'>
                        <div class='item-title-row'>
                            <div class='item-title'>{$news['news_title']}</div>
                            <div class='item-after'><span class='badge'>{$news['counter']}</span></div>
                        </div>
                        <div class='item-subtitle'>{$news['post_date']} &middot; {$news['cate_name']}</div>
                        <div class='item-text'>{$content}</div>
                    </div>
                </a>
            </li>
      ";
    }

    return $all_news;
}

//顯示單一新聞
function show_news($nsn = '', $ncsn = '')
{
    global $xoopsUser, $xoopsModule, $xoopsModuleConfig, $Tadnews;

    $module_name = $xoopsModule->getVar('name');
    $cate = $Tadnews->get_tad_news_cate($ncsn);
    $navbar_title = (empty($ncsn)) ? (string) ($module_name) : (string) ($cate['nc_title']);

    $Tadnews->set_view_nsn($nsn);
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_summary('full');
    //if($xoopsModuleConfig['use_star_rating']=='1'){
    //  $Tadnews->set_use_star_rating(true);
    //}
    $news = $Tadnews->get_news('return');

    $facebook_comments = Utility::facebook_comments($xoopsModuleConfig['facebook_comments_width'], 'tadnews', 'index.php', 'nsn', $nsn);

    $uid_name = \XoopsUser::getUnameFromId($news['page'][0]['uid'], 1);
    $uid_name = (empty($uid_name)) ? \XoopsUser::getUnameFromId($news['page'][0]['uid'], 0) : $uid_name;

    $sign_bg = (!empty($news['page'][0]['need_sign'])) ? "style='background-image:url(" . XOOPS_URL . "/modules/tadnews/images/sign_bg.png);background-position: right top;background-repeat: no-repeat;'" : '';

    $news_image = (!empty($news['page'][0]['image_thumb'])) ? "<div class='news-img'><img src='{$news['page'][0]['image_thumb']}' width='100%'></div>" : '';

    $main = "
        <div class='navbar theme-white color-white'>
            <div class='navbar-inner' data-page='show'>
                <div class='left'>
                    <a href='pda.php' class='back link'>
                        <i class='icon icon-back'></i>
                        <span>Back</span>
                    </a>
                </div>
                <div class='center sliding'>{$navbar_title}</div>
                <div class='right'><a href='#' class='link icon-only share-picker'><i class='icon ion-ios-upload-outline'></i></a></div>
            </div>
        </div>
        <div class='pages navbar-through'>
            <div data-page='show' class='show page'>
                <div class='page-content' id='news-wrap'>
                    <div class='content-block'>
                        <div class='content-block-inner'>
                            <div id='news-title' $sign_bg data-id='{$nsn}' data-url='" . XOOPS_URL . "/modules/tadnews/pda.php?nsn={$nsn}'>
                                <h1>{$news['page'][0]['news_title']}</h1>
                                <div id='news-info'>
                                    {$news['page'][0]['prefix_tag']} {$news['page'][0]['post_date']} &middot; {$uid_name} &middot; {$news['page'][0]['cate_name']} &middot; " . _TADNEWS_HOT . "{$news['page'][0]['counter']}
                                </div>
                            </div>
                            <hr>
                            <div id='news-content'>
                                {$news_image} {$news['page'][0]['content']}
                            </div>
                            <div id='news-read-check'>{$news['page'][0]['have_read_chk']}</div>
                            <div id='news-attach'>{$news['page'][0]['files']}</div>
                            <div style='clear:both;height:10px;'></div>
                            <div id='news-toolbar'>{$news['page'][0]['fun']}</div>
                        </div>
                        {$facebook_comments}
                    </div>
                </div>
                <div style='clear:both;'></div>
            </div>
        </div>
    ";

    //$Tadnews->add_counter($nsn);

    return $main;
}

//取得分類下拉選單2
function get_tad_news_cate_list_m()
{
    global $xoopsDB;

    $list = "
        <div class='content-block-title'>" . _MD_TADNEWS_NEWS_CATE . "</div>
            <div class='list-block'>
                <ul>
        ";

    $sql = 'select `ncsn`, `nc_title`, `not_news` from ' . $xoopsDB->prefix('tad_news_cate') . " where `not_news` != '1' order by `sort`";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, $sql);

    while (list($ncsn, $nc_title, $not_news) = $xoopsDB->fetchRow($result)) {
        $list .= "
            <li>
                <a href='pda.php?op=category&ncsn={$ncsn}' class='item-link item-content'>
                    <div class='item-inner'>
                        <div class='item-title'>{$nc_title}</div>
                    </div>
                </a>
            </li>
        ";
    }
    $list .= '</ul></div>';

    return $list;
}

//列出月份
function month_list_m()
{
    global $xoopsDB;

    $sql = 'SELECT left(a.start_day,7), count(*) FROM ' . $xoopsDB->prefix('tad_news') . ' AS a LEFT JOIN ' . $xoopsDB->prefix('tad_news_cate') . " AS b ON a.ncsn=b.ncsn WHERE a.enable='1' AND b.not_news='0' GROUP BY left(a.start_day,7) ORDER BY a.start_day DESC";

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, $sql);

    $count = $xoopsDB->getRowsNum($result);

    $nodata = (empty($count)) ? "
        <li class='item-content'>
            <div class='item-inner'>
                <div class='item-title'>No data available.</div>
            </div>
        </li>" : '';

    $opt = (string) ($nodata);

    while (list($ym, $count) = $xoopsDB->fetchRow($result)) {
        $opt .= "
            <li>
                <a href='pda.php?op=archive&date={$ym}' class='item-link item-content'>
                    <div class='item-inner'>
                        <div class='item-title'>" . str_replace('-', '' . _MD_TADNEWS_YEAR, $ym) . _MD_TADNEWS_MONTH . "</div>
                        <div class='item-after'><span class='badge'>{$count}</span></div>
                    </div>
                </a>
            </li>
        ";
    }

    return $opt;
}

//分月新聞
function archive_m($date = '')
{
    global $Tadnews;

    if (empty($date)) {
        $date = date('Y-m');
    }

    $Tadnews->set_news_kind('news');
    $Tadnews->set_show_mode('list');
    $Tadnews->set_show_month($date);
    $Tadnews->set_show_enable(1);

    $tnews = $Tadnews->get_news('return');

    $nodata = (empty($tnews['page'])) ? "
        <li class='item-content'>
            <div class='item-inner'>
                <div class='item-title'>No data available.</div>
            </div>
        </li>" : '';

    $date_title = Utility::to_utf8(str_replace('-', '' . _MD_TADNEWS_YEAR . ' ', $date) . _MD_TADNEWS_MONTH . _MD_TADNEWS_NEWS_TITLE);

    $list = (string) ($nodata);

    foreach ($tnews['page'] as $news) {
        $list .= "
          <li>
              <a href='pda.php?nsn={$news['nsn']}{$ncsn_param}' class='item-link item-content'>
                  <div class='item-inner'>
                      <div class='item-title-row'>
                          <div class='item-title'>{$news['news_title']}</div>
                          <div class='item-after'><span class='badge'>{$news['counter']}</span></div>
                      </div>
                      <div class='item-subtitle'>{$news['post_date']} &middot; {$news['cate_name']}</div>
                  </div>
              </a>
          </li>
        ";
    }

    return $list;
}

//列出newspaper資料
function list_newspaper_m()
{
    global $xoopsDB;

    $sql = 'SELECT a.npsn,a.number,b.title,a.np_date FROM ' . $xoopsDB->prefix('tad_news_paper') . ' AS a ,' . $xoopsDB->prefix('tad_news_paper_setup') . " AS b WHERE a.nps_sn=b.nps_sn AND b.status='1' ORDER BY a.np_date DESC";

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, $sql);

    $count = $xoopsDB->getRowsNum($result);

    $nodata = (empty($count)) ? "
        <li class='item-content'>
            <div class='item-inner'>
                <div class='item-title'>No data available.</div>
            </div>
        </li>" : '';

    $main = (string) ($nodata);

    while (list($allnpsn, $number, $title, $np_date) = $xoopsDB->fetchRow($result)) {
        $np_title = $title . sprintf(_MD_TADNEWS_NP_TITLE, $number);
        $np_date = mb_substr($np_date, 0, 10);

        $main .= "

            <li>
                <a href='pda.php?op=preview&npsn={$allnpsn}' class='item-link item-content'>
                    <div class='item-inner'>
                        <div class='item-title'>{$np_date} {$np_title}</div>
                    </div>
                </a>
            </li>
        ";
    }

    return $main;
}

//預覽電子報
function preview_newspaper_m($npsn = '')
{
    global $xoopsDB;
    if (empty($npsn)) {
        return;
    }

    $np = get_newspaper($npsn);
    $sql = 'select title,head,foot,themes from ' . $xoopsDB->prefix('tad_news_paper_setup') . " where nps_sn='{$np['nps_sn']}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, $sql);

    list($title, $head, $foot, $themes) = $xoopsDB->fetchRow($result);

    $head = str_replace('{N}', $np['number'], $head);
    $head = str_replace('{T}', $np['np_title'], $head);
    $head = str_replace('{D}', mb_substr($np['np_date'], 0, 10), $head);

    $main = "{$head}{$np['np_content']}{$foot}";

    return $main;
}

function member_m()
{
    global $xoopsUser, $xoopsModule, $Tadnews;

    $main = '';
    if ($xoopsUser) {
        $avatar = $xoopsUser->user_avatar();
        $avatar_pic = (empty($avatar) or 'blank.gif' === $avatar) ? "<i class='ion-ios-person'></i>" : "<img src='" . XOOPS_URL . "/uploads/{$avatar}'>";
        $uid_name = $xoopsUser->name();
        if (empty($uid_name)) {
            $uid_name = $xoopsUser->uname();
        }
        if ($xoopsUser->isAdmin($xoopsModule->mid())) {
            $admin = "
                <li>
                    <a href='" . XOOPS_URL . "/admin.php' class='item-link item-content external'>
                        <div class='item-media'><i class='icon ion-wrench'></i></div>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MB_SYSTEM_ADMENU . '</div>
                        </div>
                    </a>
                </li>
            ';
        }
        $power = $Tadnews->chk_user_cate_power();
        if (count($power) > 0) {
            $author = "
                <div class='list-block'>
                    <ul>
                        <li>
                            <a href='pda.php?op=mynews' class='item-link item-content'>
                                <div class='item-inner'>
                                    <div class='item-title'>" . _MD_TADNEWS_MY . '</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            ';
        }
        $main = "
        <div class='avatar-wrap'>
            <div class='avatar'>
                <div class='avatar-pic'>{$avatar_pic}</div>
                <div class='avatar-name'>{$uid_name}</div>
            </div>
        </div>
        {$author}
        <div class='content-block-title'>" . _MAIN . "</div>
        <div class='list-block'>
            <ul>
                {$admin}
                <li>
                    <a href='" . XOOPS_URL . "/user.php' class='item-link item-content external'>
                        <div class='item-media'><i class='icon ion-person'></i></div>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MB_SYSTEM_VACNT . "</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='" . XOOPS_URL . "/edituser.php' class='item-link item-content external'>
                        <div class='item-media'><i class='icon ion-edit'></i></div>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MB_SYSTEM_EACNT . "</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='" . XOOPS_URL . "/notifications.php' class='item-link item-content external'>
                        <div class='item-media'><i class='icon ion-information-circled'></i></div>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MB_SYSTEM_NOTIF . "</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='" . XOOPS_URL . "/viewpmsg.php' class='item-link item-content external'>
                        <div class='item-media'><i class='icon ion-archive'></i></div>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MB_SYSTEM_INBOX . "</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='#' class='item-link item-content logout'>
                        <div class='item-media'><i class='icon ion-power'></i></div>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MB_SYSTEM_LOUT . '</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        ';
    } else {
        $tlogin = openid_login();
        foreach ($tlogin as $login) {
            if ('btn-fb' === $login['class'] or 'btn-gl' === $login['class']) {
                $login_list .= "<a href='{$login['link']}' class='{$login['class']} button button-big external'>{$login['fa']}</a>";
            } else {
                $login_list .= "<a href='{$login['link']}' class='{$login['class']} external'><img src='{$login['img']}' alt='{$login['text']}'></a>";
            }
        }
        $main = "
        <div class='avatar-wrap'>
            <div class='avatar'>
                <a href='#' data-popup='.popup-login-form' class='open-login-screen'>
                    <div class='avatar-pic'><i class='ion-ios-person'></i></div>
                    <div class='avatar-name'>" . _GUESTS . "</div>
                </a>
            </div>
        </div>
        <div class='content-block'>
            <div class='login-button'><a href='#' class='open-login-screen button button-big'>" . _LOGIN . "</a></div>
            <div class='openid-button'>{$login_list}</div>
        </div>
        ";
    }

    return $main;
}

function openid_login()
{
    global $xoopsConfig;
    $moduleHandler = xoops_getHandler('module');
    $configHandler = xoops_getHandler('config');

    $TadLoginXoopsModule = $moduleHandler->getByDirname('tad_login');
    if ($TadLoginXoopsModule) {
        require_once XOOPS_ROOT_PATH . '/modules/tad_login/function.php';

        xoops_loadLanguage('county', 'tad_login');

        $tad_login['facebook'] = facebook_login('return');
        $tad_login['google'] = google_login('return');

        $configHandler = xoops_getHandler('config');
        $modConfig = $configHandler->getConfigsByCat(0, $TadLoginXoopsModule->getVar('mid'));

        $auth_method = $modConfig['auth_method'];
        $i = 0;

        foreach ($auth_method as $method) {
            $method_const = '_' . mb_strtoupper($method);
            $loginTitle = sprintf(_TAD_LOGIN_BY, constant($method_const));

            if ('facebook' === $method) {
                $tlogin[$i]['link'] = $tad_login['facebook'];
                $tlogin[$i]['class'] = 'btn-fb';
                $tlogin[$i]['fa'] = '<i class="icon ion-social-facebook"></i> Facebook';
            } elseif ('google' === $method) {
                $tlogin[$i]['link'] = $tad_login['google'];
                $tlogin[$i]['class'] = 'btn-gl';
                $tlogin[$i]['fa'] = '<i class="icon ion-social-google"></i> Google';
            } else {
                $tlogin[$i]['link'] = XOOPS_URL . "/modules/tad_login/index.php?login&op={$method}";
                $tlogin[$i]['class'] = 'btn-openid';
            }
            $tlogin[$i]['img'] = XOOPS_URL . "/modules/tad_login/images/{$method}.png";
            $tlogin[$i]['text'] = $loginTitle;

            $i++;
        }
    }

    return $tlogin;
}

//列出某人所有新聞
function list_tad_my_news_m()
{
    global $xoopsModuleConfig, $xoopsUser, $Tadnews;

    $power = $Tadnews->chk_user_cate_power();

    if (empty($power)) {
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
    }

    $num = (!empty($_POST['n'])) ? (int) $_POST['n'] : 10;
    $p = (!empty($_POST['p'])) ? (int) $_POST['p'] : 0;
    $start = $p * $num;

    $uid = $xoopsUser->uid();
    $Tadnews->set_show_num($num);
    $Tadnews->set_skip_news($start);
    $Tadnews->set_show_enable(0);
    $Tadnews->set_view_uid($uid);
    $Tadnews->set_news_kind($kind);
    $Tadnews->set_summary(0);
    $Tadnews->set_show_mode('list');
    $Tadnews->set_admin_tool(true);

    if (!empty($the_ncsn)) {
        $Tadnews->set_view_ncsn($the_ncsn);
    }

    $tnews = $Tadnews->get_news('return');

    $list = '';

    foreach ($tnews['page'] as $news) {
        $list .= "
            <li>
                <a href='pda.php?nsn={$news['nsn']}' class='item-link item-content'>
                    <div class='item-inner'>
                        <div class='item-title-row'>
                            <div class='item-title'>{$news['news_title']}</div>
                            <div class='item-after'><span class='badge'>{$news['counter']}</span></div>
                        </div>
                        <div class='item-subtitle'>{$news['post_date']} &middot; {$news['cate_name']}</div>
                    </div>
                </a>
            </li>
        ";
    }

    return $list;
}

function logout_m()
{
    global $xoopsConfig, $xoopsUser;
    // Regenerate a new session id and destroy old session
    $GLOBALS['sess_handler']->regenerate_id(true);
    $_SESSION = [];
    setcookie($xoopsConfig['usercookie'], 0, -1, '/', XOOPS_COOKIE_DOMAIN, 0);
    setcookie($xoopsConfig['usercookie'], 0, -1, '/');
    // clear entry from online users table
    if (is_object($xoopsUser)) {
        $onlineHandler = xoops_getHandler('online');
        $onlineHandler->destroy($xoopsUser->getVar('uid'));
    }
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$ncsn = system_CleanVars($_REQUEST, 'ncsn', 0, 'int');
$nsn = system_CleanVars($_REQUEST, 'nsn', 0, 'int');
$npsn = system_CleanVars($_REQUEST, 'npsn', '', 'int');
$date = system_CleanVars($_REQUEST, 'date', date('Y-m'), 'string');
$files_sn = system_CleanVars($_REQUEST, 'files_sn', '', 'int');

switch ($op) {
    //下載檔案
    case 'tufdl':
        $TadUpFiles = new TadUpFiles('tadnews');
        $TadUpFiles->add_file_counter($files_sn, $hash = false);
        exit;
        break;
    case 'month_list':
        $month_list = month_list_m($date);
        $main = "
            <!-- Top Navbar-->
            <div class='navbar theme-white color-white'>
                <div class='navbar-inner' data-page='month'>
                    <div class='left'>
                        <a href='#' class='back link'>
                            <i class='icon icon-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class='center sliding'>" . _MI_TADNEWS_ARCHIVE . "</div>
                    <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='month' class='month page'>
                    <div class='page-content'>
                        <div class='list-block'>
                            <ul>{$month_list}</ul>
                        </div>
                    </div>
                </div>
            </div>
            ";
        echo $main;
        exit;
        break;
    case 'archive':
        $archive = archive_m($date);
        $date_title = Utility::to_utf8(str_replace('-', '' . _MD_TADNEWS_YEAR . ' ', $date) . _MD_TADNEWS_MONTH);
        $main = "
            <!-- Top Navbar-->
            <div class='navbar theme-white color-white'>
                <div class='navbar-inner' data-page='archive'>
                    <div class='left'>
                        <a href='#' class='back link'>
                            <i class='icon icon-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class='center sliding'>{$date_title}</div>
                    <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='archive' class='archive page'>
                    <div class='page-content'>
                        <div class='list-block media-list'>
                            <ul>{$archive}</ul>
                        </div>
                    </div>
                </div>
            </div>
            ";
        echo $main;
        exit;
        break;
    case 'newspaper':
        $newspaper = list_newspaper_m();
        $main = "
            <!-- Top Navbar-->
            <div class='navbar theme-white color-white'>
                <div class='navbar-inner' data-page='newspaper'>
                    <div class='left'>
                        <a href='#' class='back link'>
                            <i class='icon icon-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class='center sliding'>" . _MI_TADNEWS_NEWSPAPER . "</div>
                    <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='newspaper' class='newspaper page'>
                    <div class='page-content'>
                        <div class='list-block'>
                            <ul>{$newspaper}</ul>
                        </div>
                    </div>
                </div>
            </div>
            ";
        echo $main;
        exit;
        break;
    case 'preview':
        $preview = preview_newspaper_m($npsn);
        $main = "
            <!-- Top Navbar-->
            <div class='navbar theme-white color-white'>
                <div class='navbar-inner' data-page='preview'>
                    <div class='left'>
                        <a href='#' class='back link'>
                            <i class='icon icon-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class='center sliding'>" . _MI_TADNEWS_NEWSPAPER . "</div>
                    <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='preview' class='preview page'>
                    <div class='page-content'>
                        <div class='content-block'>
                            <div class='content-block-inner'>
                                {$preview}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        echo $main;
        exit;
        break;
    case 'category':
        $category = list_tadnews($ncsn);
        $cate = $Tadnews->get_tad_news_cate($ncsn);
        $main = "
            <!-- Top Navbar-->
            <div class='navbar theme-white color-white'>
                <div class='navbar-inner' data-page='category'>
                    <div class='left'>
                        <a href='#' class='back link'>
                            <i class='icon icon-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class='center sliding'>{$cate['nc_title']}</div>
                    <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='category' class='category{$ncsn} page'>
                    <div class='page-content pull-to-refresh-content infinite-scroll'>
                        <div class='pull-to-refresh-layer'>
                            <div class='preloader'></div>
                            <div class='pull-to-refresh-arrow'></div>
                        </div>
                        <div class='list-block media-list cate-list'>
                            <ul>{$category}</ul>
                        </div>
                        <div class='infinite-scroll-preloader'>
                            <div class='preloader'></div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        echo $main;
        exit;
        break;
    case 'member':
        $member = member_m();
        $main = "
            <!-- Top Navbar-->
            <div class='navbar theme-white color-white'>
                <div class='navbar-inner' data-page='member'>
                    <div class='left'>
                        <a href='#' class='back link'>
                            <i class='icon icon-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class='center sliding'>" . _MB_SYSTEM_ADMENU . "</div>
                    <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='member' class='member page'>
                    <div class='page-content'>
                        {$member}
                    </div>
                </div>
            </div>
        ";
        echo $main;
        exit;
        break;
    case 'mynews':
        $mynews = list_tad_my_news_m();
        $main = "
            <!-- Top Navbar-->
            <div class='navbar theme-white color-white'>
                <div class='navbar-inner' data-page='mynews'>
                    <div class='left'>
                        <a href='#' class='back link'>
                            <i class='icon icon-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class='center sliding'>" . _MD_TADNEWS_MY . "</div>
                    <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='mynews' class='mynews page'>
                    <div class='page-content pull-to-refresh-content infinite-scroll'>
                        <div class='pull-to-refresh-layer'>
                            <div class='preloader'></div>
                            <div class='pull-to-refresh-arrow'></div>
                        </div>
                        <div class='list-block media-list mynews-list'>
                            <ul>{$mynews}</ul>
                        </div>
                        <div class='infinite-scroll-preloader'>
                            <div class='preloader'></div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        echo $main;
        exit;
        break;
    case 'mynews_load_more':
        $main = list_tad_my_news_m();
        echo $main;
        exit;
        break;
    case 'load_more':
        $main = list_tadnews($ncsn);
        echo $main;
        exit;
        break;
    case 'news':
        $main = show_news($nsn, $ncsn);
        echo $main;
        exit;
        break;
    case 'delete_tad_news':
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;
        break;
    case 'logout':
        logout_m();
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;
        break;
    default:
        if (!empty($nsn)) {
            $main = show_news($nsn, $ncsn);
        } else {
            $module_name = $xoopsModule->getVar('name');
            $list = list_tadnews();
            $main = "
                <!-- Top Navbar-->
                <div class='navbar theme-white color-white'>
                    <div class='navbar-inner' data-page='index'>
                        <div class='left'><a href='pda.php' class='link icon-only external'><i class='icon ion-ios-home'></i></a></div>
                        <div class='center sliding'>{$module_name}</div>
                        <div class='right'><a href='#' data-panel='right' class='open-panel link icon-only'><i class='icon icon-bars'></i></a></div>
                    </div>
                </div>
                <div class='pages navbar-through'>
                    <div data-page='index' class='index page'>
                        <div class='page-content pull-to-refresh-content infinite-scroll'>
                            <div class='pull-to-refresh-layer'>
                                <div class='preloader'></div>
                                <div class='pull-to-refresh-arrow'></div>
                            </div>
                            <div class='list-block media-list index-list'>
                                <ul>{$list}</ul>
                            </div>
                            <div class='infinite-scroll-preloader'>
                                <div class='preloader'></div>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }
        break;
}

/*-----------秀出結果區--------------*/
$module_name = $xoopsModule->getVar('name');
//分類下拉選單
$cate_list = get_tad_news_cate_list_m();

echo "
<!DOCTYPE html>
<html lang='" . _LANGCODE . "'>

<head>
    <meta charset='" . _CHARSET . "'>
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui'>
    <meta name='apple-mobile-web-app-capable' content='yes'>
    <meta name='apple-mobile-web-app-status-bar-style' content='black'>
    <title>{$module_name}</title>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/framework7/css/framework7.ios.min.css'>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/framework7/css/framework7.ios.colors.min.css'>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/framework7/css/ionicons.min.css'>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/framework7/css/tadnews-app.css'>
</head>

<body>
    <div class='statusbar-overlay'></div>
    <div class='panel-overlay'></div>
    <div class='panel panel-right layout-dark panel-reveal'>
        <div class='navbar'>
            <div class='navbar-inner'>
                <div class='left'>
                    <a href='#' class='close-panel link'><i class='ion-close'></i><span>Close</span></a>
                </div>
            </div>
        </div>
        <div class='list-block'>
            <ul>
                <li>
                    <a href='pda.php?op=month_list' class='item-link item-content'>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MI_TADNEWS_ARCHIVE . "</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='pda.php?op=newspaper' class='item-link item-content'>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MI_TADNEWS_NEWSPAPER . "</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href='pda.php?op=member' class='item-link item-content'>
                        <div class='item-inner'>
                            <div class='item-title'>" . _MB_SYSTEM_ADMENU . "</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        {$cate_list}
    </div>
    <!-- Views -->
    <div class='views'>
        <div class='view view-main'>
            {$main}
        </div>
    </div>
    <div class='login-screen'>
        <div class='view'>
            <div class='login-form page'>
                <div class='page-content login-screen-content'>
                    <div class='web-logo'><img src='" . XOOPS_URL . "/images/img_errors.png'></div>
                    <div class='login-screen-title'>" . _LOGIN . "</div>
                    <form method='post' action='" . XOOPS_URL . "/user.php' id='loginForm'>
                        <div class='list-block'>
                            <ul>
                                <li class='item-content'>
                                    <div class='item-media'><i class='icon ion-ios-person'></i></div>
                                    <div class='item-inner'>
                                        <div class='item-input'>
                                            <input type='text' name='uname' placeholder='" . _USERNAME . "'>
                                        </div>
                                    </div>
                                </li>
                                <li class='item-content'>
                                    <div class='item-media'><i class='icon ion-ios-locked'></i></div>
                                    <div class='item-inner'>
                                        <div class='item-input'>
                                            <input type='password' name='pass' placeholder='" . _PASSWORD . "'>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class='content-block'>
                            <input type='hidden' value='/modules/tadnews/pda.php' name='xoops_redirect'>
                            <input type='hidden' value='login' name='op'>
                            <div class='login-button'>
                                <a href='#' onclick=\"document.getElementById('loginForm').submit();\" class='button button-big'>" . _SUBMIT . "</a>
                            </div>
                            <div class='close-login'>
                                <a href='#' class='close-login-screen'>" . _CANCEL . "</a>
                            </div>
                        </div>
                        <div class='content-block'>
                            <div class='row'>
                                <div class='col-50 lost-pass'>
                                    <a href='" . XOOPS_URL . "/user.php#lost' class='external'>" . _MB_SYSTEM_LPASS . "</a>
                                </div>
                                <div class='col-50 sign-up'>
                                    <a href='" . XOOPS_URL . "/register.php' class='external'>" . _MB_SYSTEM_RNOW . "</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Framework7 Library JS-->
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/jquery/jquery-1.11.1.min.js'></script>
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/framework7/js/framework7.min.js'></script>
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/fancyBox/source/jquery.fancybox.js?v=2.1.4'></script>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/fancyBox/source/jquery.fancybox.css?v=2.1.4'>
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/framework7/js/tadnews-app.js'></script>
</body>

</html>
";
