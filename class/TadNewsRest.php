<?php
namespace XoopsModules\Tadnews;

use XoopsModules\Tadnews\Tadnews;
use XoopsModules\Tadtools\SimpleRest;

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

class TadNewsRest extends SimpleRest
{
    private $Tadnews;

    public function __construct()
    {
        $this->Tadnews = new Tadnews();
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

        $sql = 'SELECT ncsn,of_ncsn,nc_title,sort FROM ' . $xoopsDB->prefix('tad_news_cate') . " WHERE not_news!='1' ORDER BY sort";
        $result = $xoopsDB->query($sql);
        while (list($ncsn, $of_ncsn, $nc_title, $sort) = $xoopsDB->fetchRow($result)) {
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
