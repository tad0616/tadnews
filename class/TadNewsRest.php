<?php
namespace XoopsModules\Tadnews;

use Xmf\Jwt\TokenReader;
use XoopsModules\Tadnews\Tadnews;
use XoopsModules\Tadtools\SimpleRest;

// use XoopsModules\Tadtools\Utility;

require dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

if (!class_exists('XoopsModules\Tadtools\SimpleRest')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

class TadNewsRest extends SimpleRest
{
    private $Tadnews;
    private $uid;
    private $user;
    private $groups;

    public function __construct($token = '')
    {
        if ($token) {
            $rememberClaims = TokenReader::fromString('rememberme', $token);
            // Utility::dd($rememberClaims->uid);
            if (false !== $rememberClaims && !empty($rememberClaims->uid)) {
                $this->uid = $rememberClaims->uid;
                $member_handler = xoops_gethandler('member');
                $this->user = $member_handler->getUser($rememberClaims->uid);
                $this->groups = $this->user->getGroups();
            }
        }
        $this->Tadnews = new Tadnews($this->uid, $this->groups);
    }

    // 取得所有文章的 json
    public function list_all_news($ncsn = '', $num = 10)
    {
        $this->Tadnews->set_show_enable(0);
        $this->Tadnews->set_show_num($num);
        $this->Tadnews->set_news_kind('news');
        if ($ncsn > 0) {
            $this->Tadnews->set_view_ncsn($ncsn);
        }
        $this->Tadnews->set_show_mode('list');
        $data = $this->Tadnews->get_news('app');
        return $this->encodeJson($data);
    }

    // 取得單一文章的 json
    public function show_news($nsn = '')
    {

        $this->Tadnews->set_show_enable(0);
        $this->Tadnews->set_view_nsn($nsn);
        $this->Tadnews->set_cover(true, 'db');
        $this->Tadnews->set_summary('full');
        $data = $this->Tadnews->get_news('app');
        return $this->encodeJson($data);
    }

    // 取得年度文章數
    public function get_cates()
    {
        global $xoopsDB;
        $data = [];

        $sql = 'SELECT `ncsn`, `of_ncsn`, `nc_title`, `sort`, `enable_group` FROM ' . $xoopsDB->prefix('tad_news_cate') . " WHERE not_news!='1' ORDER BY `sort`";
        $result = $xoopsDB->query($sql);
        while (list($ncsn, $of_ncsn, $nc_title, $sort, $enable_group) = $xoopsDB->fetchRow($result)) {
            if ($enable_group) {
                $enable_group_arr = \explode(',', $enable_group);
                // Utility::dd($enable_group_arr);
                if (!array_intersect($enable_group_arr, $this->groups)) {
                    continue;
                }
            }
            $cate['ncsn'] = $ncsn;
            $cate['title'] = $nc_title;
            $cate['of_ncsn'] = $of_ncsn;
            $cate['sort'] = $sort;
            $cate['url'] = XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}";
            $data[] = $cate;
        }
        return $this->encodeJson($data);
    }

    public function encodeJson($responseData)
    {
        if (empty($responseData)) {
            $statusCode = 404;
            $responseData = array('error' => '無資料');
        } else {
            $statusCode = 200;
        }
        $this->setHttpHeaders($statusCode);

        $jsonResponse = json_encode($responseData, 256);
        return $jsonResponse;
    }

}
