<?php
//TadNews物件
/*

//設定種類（news , page ,mixed）
tadnews::set_news_kind($new_kind="news");

//設定是否開啟選擇模式（應該只用在 list 模式）
tadnews::set_news_check_mode($mode="0");

//設定編輯器( elrte , ck)
tadnews::set_news_editor($editor="ck");

//設定是否使用拉動排序工具（應該只用在 list 模式）
tadnews::set_sort_tool($mode="0");

//設定是否秀出草稿
tadnews::set_show_enable($enable="1");

//設定欲觀看分類（指定編號、陣列或不指定）
tadnews::set_view_ncsn($ncsn="");

//設定欲觀看標籤（指定編號、陣列或不指定）
tadnews::set_view_tag($tag_sn="");

//設定欲觀看文章（指定編號、陣列或不指定）
tadnews::set_view_nsn($nsn="");

//取得欲觀看文章（指定編號、陣列或不指定）
tadnews::get_view_nsn();

//設定欲觀看文章
tadnews::set_show_month($ym="");

//設定欲觀看作者
tadnews::set_view_uid($uid="");

//設定顯示方式（summary,list,one）
tadnews::set_show_mode($show_mode="summary");

//設定顯示資料數(int , none)
tadnews::set_show_num($show_num="10");

//設定管理工具
tadnews::set_admin_tool($admin_tool=false);

//設定是否列出分類篩選工具
tadnews::set_news_cate_select($show='0');

//設定是否列出作者篩選工具
tadnews::set_news_author_select($show='0');

//裁切標題
tadnews::set_title_length($length=0);

//封面圖片設定
tadnews::set_cover($show=false,$css="");

//顯示摘要文字（$num=int or "page_break" 根據摘要符號來裁切 or "full"全文 or ''不要）
tadnews::set_summary($num="",$css="");

//略過文章設定
tadnews::set_skip_news($num=0);

//設定是否使用評分機制
tadnews::set_use_star_rating($val=false);

//取得新聞（assign=直接套入樣板，array=傳回陣列）
tadnews::get_news($mode='assign');

//取得分類新聞（assign=直接套入樣板，array=傳回陣列）
tadnews::get_cate_news($mode='assign');

//把字串換成群組
tadnews::txt_to_group_name($enable_group="",$default_txt="",$syb="<br />");

//取得所有群組
tadnews::get_all_groups();

//取得分類下拉選單
tadnews::get_tad_news_cate_option($of_ncsn=0,$level=0,$v="",$this_ncsn="",$no_self="0",$not_news="null");

//判斷目前登入者在哪些類別中有發表的權利 post,pass,read
tadnews::chk_user_cate_power($kind="post");

//以流水號取得某筆tad_news資料 $mode=full （不經過xlanguage）
tadnews::get_tad_news($nsn="",$uid_chk=false,$pass_xlanguage='');

//前置字串 ($mode='all' 不管啟動或關閉，一律顯示，適用於管理界面)
tadnews::mk_prefix_tag($tag_sn,$mode='');

//計數器
tadnews::add_counter($nsn);

//解析分類的設定檔
tadnews::get_setup($setup="");

//tad_news編輯表單
tadnews::tad_news_form($nsn="",$ncsn="");

//新增資料到tad_news中
tadnews::insert_tad_news();

//自動取得新排序
tadnews::get_max_sort($of_ncsn="",$not_news=0);

//以流水號取得某筆tad_news_cate資料
tadnews::get_tad_news_cate($ncsn="");

//更新tad_news某一筆資料
tadnews::update_tad_news($nsn="");

//刪除tad_news某筆資料資料
$tadnews=new tadnews();
$tadnews->delete_tad_news($nsn);


*/
class tadnews{
  var $kind="news"; //news,page,mixed
  var $now;
  var $today;
  var $view_ncsn;
  var $view_tag;
  var $view_nsn;
  var $view_uid;
  var $show_mode="summary"; //summary,list,cate,one
  var $show_num="10";
  var $admin_tool=false;
  var $show_enable=1;
  var $show_cate_select=0;
  var $show_author_select=0;
  var $news_check_mode=0;
  var $batch_tool="";
  var $sort_tool=0;
  var $summary_num=0;
  var $summary_css="";
  var $title_length="";
  var $cover_use=false;
  var $cover_css="";
  var $skip_news=0;
  var $use_star_rating=false;
  var $view_month="";
  var $editor="ck";
  var $TadUpFiles;

  //建構函數
  function __construct(){
    global $xoopsConfig,$xoopsModuleConfig;
    include_once XOOPS_ROOT_PATH."/modules/tadtools/TadUpFiles.php" ;
    //include_once XOOPS_ROOT_PATH."/modules/tadnews/up_file.php";
    include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";
    include_once XOOPS_ROOT_PATH."/modules/tadnews/language/{$xoopsConfig['language']}/main.php";
    $this->now =date("Y-m-d",xoops_getUserTimestamp(time()));
    $this->today=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));

    if(empty($xoopsModuleConfig)){
      $modhandler = &xoops_gethandler('module');
      $xoopsModule = &$modhandler->getByDirname("tadnews");
      $config_handler =& xoops_gethandler('config');
      $xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));
    }

    if($xoopsModuleConfig['use_star_rating']=='1'){
      $this->set_use_star_rating(true);
    }
    $this->TadUpFiles=new TadUpFiles("tadnews");

  }

  //設定種類
  public function set_news_kind($new_kind="news"){
    $this->kind=$new_kind;
  }

  //設定是否開啟選擇模式
  public function set_news_check_mode($mode="0"){
    $this->news_check_mode=$mode;
    $this->batch_tool=$this->batch_tool();
  }

  //設定編輯器
  public function set_news_editor($editor="ck"){
    $this->editor=$editor;
  }

  //設定是否使用拉動排序工具
  public function set_sort_tool($mode="0"){
    $this->sort_tool=$mode;
  }

  //設定是否秀出草稿
  public function set_show_enable($enable="1"){
    $this->show_enable=$enable;
  }

  //設定欲觀看分類
  public function set_view_ncsn($ncsn=""){
    global $xoopsDB;

    $this->view_ncsn=$ncsn;
    if(!is_array($ncsn) and !empty($ncsn)){
      $sql="select not_news from ".$xoopsDB->prefix("tad_news_cate")." where ncsn='{$ncsn}'";
      $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
      list($not_news)=$xoopsDB->fetchRow($result);
      if($not_news==1){
        $this->set_news_kind("page");
      }
    }
  }

  //設定欲觀看標籤
  public function set_view_tag($tag_sn=""){
    $this->view_tag=$tag_sn;
  }


  //設定欲觀看文章
  public function set_view_nsn($nsn=""){
    $this->view_nsn=$nsn;
  }

  //取得欲觀看文章
  public function get_view_nsn(){
    return $this->view_nsn;
  }

  //設定欲觀看文章
  public function set_show_month($ym=""){
    $this->view_month=$ym;
  }

  //設定欲觀看作者
  public function set_view_uid($uid=""){
    $this->view_uid = $uid;
  }

  //設定顯示方式，summary,list,cate
  public function set_show_mode($show_mode="summary"){
    $this->show_mode=$show_mode;
  }

  //設定顯示資料數
  public function set_show_num($show_num="10"){
    $this->show_num=$show_num;
  }

  //設定管理工具
  public function set_admin_tool($admin_tool=false){
    $this->admin_tool=$admin_tool;
  }

  //設定是否列出分類篩選工具
  public function set_news_cate_select($show='0'){
    $this->show_cate_select=$show;
  }

  //設定是否列出作者篩選工具
  public function set_news_author_select($show='0'){
    $this->show_author_select=$show;
  }

  //裁切標題
  public function set_title_length($length=0){
    $this->title_length=intval($length);
  }

  //封面圖片設定
  public function set_cover($show=false,$css=""){
    $this->cover_use=$show;
    $this->cover_css=$css;
  }


  //顯示摘要文字
  public function set_summary($num="",$css=""){
    $this->summary_num = $num;
    $this->summary_css = $css;
  }

  //略過文章設定
  public function set_skip_news($num=0){
    $this->skip_news=intval($num);
  }


  //設定是否使用評分機制
  public function set_use_star_rating($val=false){
    $this->use_star_rating=$val;
  }

  //取得圖片
  private function get_news_cover($col_name="",$col_sn="",$mode="big",$style="db",$only_url=false,$id='cover_pic'){
    global $xoopsDB,$xoopsUser,$xoopsModule;

    $sql = "select * from ".$xoopsDB->prefix("tadnews_files_center")." where `col_name`='{$col_name}' and `col_sn`='{$col_sn}' order by sort";

    $result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
    while($all=$xoopsDB->fetchArray($result)){
      //以下會產生這些變數： $files_sn, $col_name, $col_sn, $sort, $kind, $file_name, $file_type, $file_size, $description
      foreach($all as $k=>$v){
        $$k=$v;
      }

      $style_set=($style=='db')?$description:$style;
      //die($style_set);

      if(empty($style) and !$only_url)return;

      if($mode!="big"){
        if($only_url){
          return XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name}";
        }else{
          $img=($style=='db')?"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn'><div id='$id' style='background-image:url(".XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name});{$style_set}'></div></a>":"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='".XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name}'></a>";
          return $img;
        }
      }else{
        if($only_url){
          return XOOPS_URL."/uploads/tadnews/image/{$file_name}";
        }else{
          $img=($style=='db')?"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn'><div id='$id' style='background-image:url(".XOOPS_URL."/uploads/tadnews/image/{$file_name});{$style_set}'></div></a>":"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='".XOOPS_URL."/uploads/tadnews/image/{$file_name}' ></a>";
          return $img;
        }
      }
    }
    return ;
  }

  //取得高亮度語法
  private function get_syntaxhighlighter(){
    $syntaxhighlighter_code="";
    if(file_exists(TADTOOLS_PATH."/syntaxhighlighter.php")){
      include_once TADTOOLS_PATH."/syntaxhighlighter.php";
      $syntaxhighlighter= new syntaxhighlighter();
      $syntaxhighlighter_code=$syntaxhighlighter->render();
    }
    return $syntaxhighlighter_code;
  }

  //置頂圖示
  private function get_news_pic($always_top="",$post_date=""){
    $always_top_pic=($always_top=='1')?"<img src='".XOOPS_URL."/modules/tadnews/images/top.gif' alt='"._TADNEWS_ALWAYS_TOP."' title='"._TADNEWS_ALWAYS_TOP."' hspace=3 align='absmiddle'>":$this->get_today_pic($post_date);
    return $always_top_pic;
  }


  //今日文章
  private function get_today_pic($post_date=""){
    $today_pic=($this->now==$post_date)?"<img src='".XOOPS_URL."/modules/tadnews/images/today.gif' alt='". _TADNEWS_TODAY_NEWS."' title='"._TADNEWS_TODAY_NEWS."' hspace=3 align='absmiddle'>":"";
    return $today_pic;
  }


  //取得新聞
  public function get_news($mode='assign'){
    global $xoopsDB,$xoopsUser,$xoopsModuleConfig,$isAdmin,$xoopsTpl,$xoTheme;
    //die(var_export($this->get_view_nsn()));
    $rating_js="";

    //設定是否需要高亮度語法
    $syntaxhighlighter_code=$this->get_syntaxhighlighter();


    //取得目前使用者的所屬群組
    if($xoopsUser){
      $User_Groups=$xoopsUser->getGroups();
      $now_uid=$xoopsUser->uid();
    }else{
      $User_Groups=array();
      $now_uid=0;
    }

    $where_news="";

    //看目前是列出所有文章？還是指定目錄文章？還是單獨一頁？還是一堆指定文章

    //秀出單一篇文章
    if(!empty($this->view_nsn) and is_numeric($this->view_nsn)){
      //完整內容
      //$this->set_summary('full');
      $this->set_show_num(1);
      $this->set_show_mode("one");
      $this->add_counter($this->view_nsn);

      //找出相關資訊
      $sql2 = "select ncsn from ".$xoopsDB->prefix("tad_news")." where nsn='".$this->view_nsn."'";
      $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql2));
      list($ncsn)=$xoopsDB->fetchRow($result2);
      $this->set_view_ncsn($ncsn);

      $sql2 = "select not_news,nc_title from ".$xoopsDB->prefix("tad_news_cate")." where ncsn='".$this->view_ncsn."'";
      $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql2));
      list($not_news,$show_cate_title)=$xoopsDB->fetchRow($result2);
      $kind=($not_news)?"page":"news";
      $this->set_news_kind($kind);

      //分析目前觀看得是新聞還是自訂頁面
      if($this->kind==="news"){
        $kind_chk="and not_news!='1'";
      }elseif($this->kind==="page"){
        $kind_chk="and not_news='1'";
      }else{
        $kind_chk='';
      }


      $where_cate=" and ncsn='{$this->view_ncsn}'";
      $where_news=" and nsn='{$this->view_nsn}'";


    //秀出一堆指定文章
    }elseif(!empty($this->view_nsn) and is_array($this->view_nsn)){
      //die(var_export($this->view_nsn));
      //完整內容
      $this->set_summary(0);
      //不限篇數
      $this->set_show_num(0);
      $all_nsn=implode(',',$this->view_nsn);
      //找出相關資訊
      $sql2 = "select ncsn from ".$xoopsDB->prefix("tad_news")." where nsn in($all_nsn)";
      $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql2));
      while(list($ncsn)=$xoopsDB->fetchRow($result2)){
        $all_ncsn[$ncsn]=$ncsn;
      }
      $this->set_view_ncsn($all_ncsn);
      $all_ncsn=implode(',',$this->view_ncsn);
      //分析目前觀看得是新聞還是自訂頁面
      $kind_chk='';
      $where_cate=empty($all_ncsn)?"":" and ncsn in($all_ncsn)";
      $where_news=" and nsn in($all_nsn)";

    //秀出分類或不指定
    }else{
      //die(var_export($this->view_nsn));

      //分析目前觀看得是新聞還是自訂頁面
      if($this->kind==="news"){
        $kind_chk="and not_news!='1'";
      }elseif($this->kind==="page"){
        $kind_chk="and not_news='1'";
      }else{
        $kind_chk='';
      }

      //假如沒有指定觀看分類
      if(is_null($this->view_ncsn)){
        $where_cate="";
      //若指定觀看的分類是陣列（限定觀看的分類）
      }elseif(is_array($this->view_ncsn)){
        $all_ncsn=implode(',',$this->view_ncsn);
        $where_cate=empty($all_ncsn)?"":"and ncsn in($all_ncsn)";

      //指定觀看某一個分類
      }else{
        //找出底下的子分類
        $sql2 = "select ncsn from ".$xoopsDB->prefix("tad_news_cate")." where of_ncsn='".$this->view_ncsn."'";
        $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql2));
        $ncsn_arr[]=$this->view_ncsn;
        while(list($sub_ncsn)=$xoopsDB->fetchRow($result2)){
          $ncsn_arr[]=$sub_ncsn;
        }

        $where_cate="and ncsn in(".implode(',',$ncsn_arr).")";
      }
    }


    //設定是否需要評分工具
    if($this->use_star_rating){
      include_once XOOPS_ROOT_PATH."/modules/tadtools/star_rating.php";
      if($this->show_mode=="one"){
        $rating=new rating("tadnews","10",'','simple');
      }else{
        $rating=new rating("tadnews","10",'show','simple');
      }
    }


    //找指定的分類
    $sql="select * from ".$xoopsDB->prefix("tad_news_cate")." where 1 $kind_chk $where_cate order by sort";
    $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],10, show_error($sql));

    //$ncsn , $of_ncsn , $nc_title , $enable_group , $enable_post_group , $sort , $cate_pic , $not_news , $setup
    $ncsn_ok=$cates=$cate_setup="";
    while($all_cate=$xoopsDB->fetchArray($result)){
      foreach($all_cate as $k=>$v){
        $$k=$v;
      }

      //只有可讀的分類才納入
      $cate_read_poewr=$this->chk_cate_power($ncsn,$User_Groups,$enable_group,"read");
      if($cate_read_poewr){
        //該使用者可觀看的分類編號陣列
        $ncsn_ok[]=$ncsn;
        //可觀看分類名稱
        $cates[$ncsn]=$nc_title;
        $cate_setup[$ncsn]=$setup;
      }
    }


    if(empty($ncsn_ok)){
      $where_cate="and ncsn=''";
    }else{
      $ok_cate=implode(",",$ncsn_ok);

      //假如沒有指定觀看分類
      if(is_null($this->view_ncsn)){
        if($this->kind=="page"){
          $where_cate=(empty($ok_cate))?"and 0":"and ncsn in($ok_cate)";
        }else{
          $where_cate=(empty($ok_cate))?"and ncsn='0'":"and (ncsn in($ok_cate) or ncsn='0')";
        }
      //若指定觀看的分類是陣列（限定觀看的分類）
      }elseif(is_array($this->view_ncsn)){
        $where_cate=empty($ok_cate)?"":"and ncsn in($ok_cate)";
      //指定觀看某一個分類
      }else{
        //找出底下的子分類
        $sql2 = "select ncsn from ".$xoopsDB->prefix("tad_news_cate")." where of_ncsn='".$this->view_ncsn."'";
        $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql2));
        $ncsn_arr[]=$this->view_ncsn;
        while(list($sub_ncsn)=$xoopsDB->fetchRow($result2)){
          $ncsn_arr[]=$sub_ncsn;
        }
        $all_ncsn=implode(',',$ncsn_arr);
        $where_cate=empty($all_ncsn)?"":"and ncsn in($all_ncsn)";
      }
    }

    //假如沒有指定觀看作者
    if(!empty($this->view_uid)){
      $where_uid="and uid='{$this->view_uid}'";
    }else{
      $where_uid="";
    }

    //假如有指定標籤
    if(!empty($this->view_tag)){
      $where_tag="and prefix_tag='{$this->view_tag}'";
    }else{
      $where_tag="";
    }

    if($this->kind=="page"){
      $desc="order by ncsn , page_sort";
    }elseif(!empty($this->view_nsn) and is_array($this->view_nsn)){
      $nsn_order=implode(',',$this->view_nsn);
      $desc="order by field(`nsn` , $nsn_order)";
    }elseif($this->kind=="news"){
      $desc="order by always_top desc , start_day desc";
    }else{
      $desc="order by always_top desc , start_day desc";
    }

    //判斷是否要檢查日期
    if(!empty($this->view_month)){
      $date_chk="and start_day like '{$this->view_month}%'";
    }elseif($this->admin_tool){
      $date_chk="";
    }else{
      $date_chk="and start_day < '".$this->today."' and (end_day > '".$this->today."' or end_day='0000-00-00 00:00:00') ";
    }

    $and_enable=($this->show_enable==1)?"and enable='1'":"";


    if(!empty($this->skip_news)){
      $limit=(empty($this->show_num) or $this->show_num==='none')?"":"limit {$this->skip_news} , {$this->show_num}";
      $sql = "select * from ".$xoopsDB->prefix("tad_news")." where 1 $where_news $and_enable $where_uid $where_tag $where_cate  $date_chk  $desc $limit";
    }else{
      $limit=empty($this->show_num)?"10":$this->show_num;
      $sql = "select * from ".$xoopsDB->prefix("tad_news")." where 1 $where_news $and_enable $where_uid $where_tag $where_cate  $date_chk  $desc";
      if(empty($this->view_month) or $this->show_num!='none'){
        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar=getPageBar($sql,$limit);
        $bar=$PageBar['bar'];
        $sql=$PageBar['sql'];
      }
    }

    //die($sql);

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));


    //分類篩選工具
    if($this->show_cate_select){
      $not_news=($this->kind=="news")?0:1;
      $cate_select=$this->news_cate_select($not_news);
    }else{
      $cate_select="";
    }

    //作者篩選工具
    if($this->show_author_select){
      $author_select=$this->news_author_select();
    }else{
      $author_select="";
    }
    $all_news="";
    $i=0;

    $myts =MyTextSanitizer::getInstance();
    while($news=$xoopsDB->fetchArray($result)){

      foreach($news as $k=>$v){
        $$k=$v;
      }

      if(!$isAdmin and $uid!=$now_uid and $enable=='0'){
        continue;
      }

      //判斷本文及所屬分類是否允許該用戶之所屬群組觀看
      $news_read_poewr=$this->chk_news_power($enable_group,$User_Groups);
      if(!$news_read_poewr){
        continue;
      }

      if($this->use_star_rating){
        $rating->add_rating("nsn",$nsn);
      }

      //新聞資訊列
      $fun=$this->admin_tool($uid,$nsn,$counter,$ncsn,$have_read_group);
      $end_day=($end_day=="0000-00-00 00:00:00")?"":"~ ".$end_day;
      $enable_txt=($enable==1)?"":"["._TADNEWS_NEWS_UNABLE."]";

      //製作新聞標題內容，及密碼判斷
      $have_pass=(isset($_SESSION['have_pass']))?$_SESSION['have_pass']:array();

      if(!empty($passwd) and !empty($this->summary_num)){
        $tadnews_passw=(isset($_POST['tadnews_passwd']))?$_POST['tadnews_passwd']:"";
        if($tadnews_passw != $passwd and !in_array($nsn,$have_pass)){
          $news_content="<div class='hero-unit'>
          <p>"._TADNEWS_NEWS_NEED_PASSWD."</p>
          <form action='".XOOPS_URL."/modules/tadnews/index.php' method='post'>
            <fieldset>
            <input type='hidden' name='nsn' value='{$nsn}'>
            <input type='password' name='tadnews_passwd'>
            <button type='submit' class='btn btn-primary'>"._TADNEWS_SUBMIT."</button>
            </fieldset>
          </form>
          </div>";
        }else{
          $_SESSION['have_pass'][]=$nsn;
        }
      }elseif(!empty($this->summary_num) and is_numeric($this->summary_num)){
        $news_content=strip_tags($news_content);
        $news_content=str_replace("--summary--","",$news_content);

        //支援xlanguage
        $news_content=$this->xlang($news_content);

        $style=(empty($this->summary_css))?"":"style='{$this->summary_css}'";
        $news_content="<div $style>".mb_substr ($news_content , 0 , $this->summary_num , _CHARSET)."...</div>";
      }elseif($this->summary_num === "page_break"){
        if(preg_match("/--summary--/",$news_content)){
          //支援xlanguage
          $news_content=$this->xlang($news_content);

          $news_content=str_replace("<p>--summary--</p>","--summary--",$news_content);
          $content_arr=explode("--summary--",$news_content);
        }else{
          $content_arr=explode("<div style=\"page-break-after: always;\"><span style=\"display: none;\">&nbsp;</span></div>",$news_content);
        }
        $more=(empty($content_arr[1]))?"":"<p><a href='".XOOPS_URL."/modules/tadnews/index.php?nsn={$nsn}'>"._TADNEWS_MORE."...</a></p>";
        $news_content=$content_arr[0].$more;
      }elseif($this->summary_num==="full"){
        $news_content=str_replace("<p>--summary--</p>","",$news_content);
        $news_content=str_replace("--summary--","",$news_content);
        //支援xlanguage
        $news_content=$this->xlang($news_content);
      }elseif(empty($this->summary_num)){
        $news_content="";
      }else{

        if(preg_match("/"._SEPARTE2."/",$news_content)){
          //支援xlanguage
          $news_content=$this->xlang($news_content);
          $news_content=str_replace("<p>"._SEPARTE2."</p>",_SEPARTE2,$news_content);
          $content=explode(_SEPARTE2,$news_content);
        }else{
          $content=explode(_SEPARTE,$news_content);
        }

        $more=(empty($content[1]))?"":"<p><a href='".XOOPS_URL."/modules/tadnews/index.php?nsn={$nsn}'>"._TADNEWS_MORE."...</a></p>";
        $news_content=$content[0].$more;
      }

      $have_read_chk=$this->have_read_chk($have_read_group,$nsn);

      $uid_name=XoopsUser::getUnameFromId($uid,1);
      $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;

      $news_title=(empty($news_title))?_TADNEWS_NO_TITLE:$news_title;
      $cate_name=(empty($cates[$ncsn]))?_TADNEWS_NO_CATE:$cates[$ncsn];

      $post_date=substr($start_day,0,10);
      $today_pic=$this->get_news_pic($always_top,$start_day);
      if($this->show_mode=="summary" or $this->show_mode=="one"){
        $need_sign=(!empty($have_read_group))?XOOPS_URL."/modules/tadnews/images/sign_bg.png":"";
      }else{
        $need_sign=(!empty($have_read_group))?XOOPS_URL."/modules/tadnews/images/sign_s.png":"";
      }
      $g_txt=$this->txt_to_group_name($enable_group,_TADNEWS_ALL_OK);

      $show_admin_tool=($this->admin_tool)?1:0;

      $link_page=($this->kind=='page')?"page.php":"index.php";

      $chkbox=($this->news_check_mode)?"<input type='checkbox' name='nsn_arr[]' value='$nsn'> ":"";

      if(!empty($this->title_length)){
        $news_title=mb_substr($news_title , 0 , $this->title_length , _CHARSET);
        $news_title.="...";
      }

      if($this->cover_use){
        $pic=$this->get_news_cover("news_pic",$nsn,"big",$this->cover_css,null,'demo_cover_pic');
      }else{
        $pic="";
      }
      $image_big=$this->get_news_cover("news_pic",$nsn,"big",null,true);
      $image_thumb=$this->get_news_cover("news_pic",$nsn,"thumb",null,true);


      if($this->use_star_rating){
        $rating_js=$rating->render();
      }

      $file_mode=$this->show_mode=='one'?"":"small";
      $tadnews_files=$this->get_news_files($nsn,$file_mode);

      $prefix_tag=$this->mk_prefix_tag($prefix_tag);

      $nsnsort=$this->news_sort($nsn);

      $back_news=$back_news_link=$back_news_title=$next_news_link=$next_news_title="";
      if(isset($nsnsort['back']) and !empty($nsnsort['back']['nsn'])){
       //支援xlanguage
        $nsnsort['back']['title']=$this->xlang($nsnsort['back']['title']);
        //$title=xoops_substr($nsnsort['back']['title'], 0, 30);
        $title=mb_substr ($nsnsort['back']['title'], 0, 20 , _CHARSET)."...";
        $date=substr($nsnsort['back']['date'],5);
        $back_news_link=XOOPS_URL."/modules/tadnews/index.php?nsn={$nsnsort['back']['nsn']}";
        $back_news_title=($this->kind==="page")?$title:"{$date} {$title}";
      }

      $next_news="";
      if(isset($nsnsort['next']) and !empty($nsnsort['next']['nsn'])){
        //支援xlanguage
        $nsnsort['next']['title']=$this->xlang($nsnsort['next']['title']);

        //$title=xoops_substr($nsnsort['next']['title'], 0, 30);
        $title=mb_substr ($nsnsort['next']['title'], 0, 20 , _CHARSET)."...";
        $date=substr($nsnsort['next']['date'],5);

        $next_news_link=XOOPS_URL."/modules/tadnews/index.php?nsn={$nsnsort['next']['nsn']}";
        $next_news_title=($this->kind==="page")?$title:"{$date} {$title}";
      }

      $facebook_comments=facebook_comments($xoopsModuleConfig['facebook_comments_width'],'tadnews','index.php','nsn',$nsn);
      $push=push_url($xoopsModuleConfig['use_social_tools']);
      $pluralink=$this->get_pluralink_path($ncsn);

      $all_news[$i]['nsn']=$nsn;
      $all_news[$i]['facebook_comments']=$facebook_comments;
      $all_news[$i]['push']=$push;
      $all_news[$i]['pic']=$pic;
      $all_news[$i]['chkbox']=$chkbox;
      $all_news[$i]['ncsn']=$ncsn;
      $all_news[$i]['pluralink']=$pluralink;
      $all_news[$i]['cate_name']=$cate_name;
      $all_news[$i]['post_date']=$post_date;
      $all_news[$i]['prefix_tag']=$prefix_tag;
      $all_news[$i]['need_sign']=$need_sign;
      $all_news[$i]['today_pic']=$today_pic;
      $all_news[$i]['link_page']=$link_page;
      $all_news[$i]['news_title']=$myts->htmlSpecialChars($news_title);
      $all_news[$i]['uid']=$uid;
      $all_news[$i]['uid_name']=$uid_name;
      $all_news[$i]['counter']=$counter;
      $all_news[$i]['content']=$myts->displayTarea($news_content,1,1,1,1,0);
      $all_news[$i]['show_admin_tool']=$show_admin_tool;
      $all_news[$i]['passwd']=$passwd;
      $all_news[$i]['g_txt']=$g_txt;
      $all_news[$i]['files']=$tadnews_files;
      $all_news[$i]['fun']=$fun;
      $all_news[$i]['enable']=$enable;
      $all_news[$i]['enable_txt']=$enable_txt;
      $all_news[$i]['have_read_chk']=$have_read_chk;
      $all_news[$i]['back_news_link']=$back_news_link;
      $all_news[$i]['back_news_title']=$back_news_title;
      $all_news[$i]['next_news_link']=$next_news_link;
      $all_news[$i]['next_news_title']=$next_news_title;
      $all_news[$i]['image_big']=$image_big;
      $all_news[$i]['image_thumb']=$image_thumb;

      if($this->kind==="page"){
      //die(var_dump($cate_setup[$ncsn]));
        $set=$this->get_setup($cate_setup[$ncsn]);
        //die(var_dump($set));
        //title=0;tool=0;comm=0
        if($mode=='return'){
          $main['cate_set_title']=$set['title'];
          $main['cate_set_tool']=$set['tool'];
          $main['cate_set_comm']=$set['comm'];
          $main['cate_set_nav']=$set['nav'];
        }else{
          $xoopsTpl->assign("cate_set_title" , $set['title']);
          $xoopsTpl->assign("cate_set_tool" , $set['tool']);
          $xoopsTpl->assign("cate_set_comm" , $set['comm']);
          $xoopsTpl->assign("cate_set_nav" , $set['nav']);
        }
      }

      if($this->use_star_rating){
        $all_news[$i]['star']="<div id='rating_nsn_{$nsn}'></div>";
      }else{
        $all_news[$i]['star']="";
      }

      $i++;
    }

    $jquery=get_jquery();

    if($mode=='return'){
      $main['jquery']=$jquery;
      $main['page']=$all_news;
      $main['show_admin_tool_title']=$this->admin_tool;
      $main['show_sort_tool']=$this->sort_tool;
      $main['action']=$_SERVER['PHP_SELF'];
      $main['batch_tool']=$this->batch_tool;
      $main['del_js']=$this->del_js();
      $main['cate_select']=$cate_select;
      $main['author_select']=$author_select;
      $main['bar']=$bar;
      $main['syntaxhighlighter_code']=$syntaxhighlighter_code;
      if($this->use_star_rating){
        $main['rating_js']=$rating_js;
      }
      if(is_numeric($this->view_ncsn))$main['show_cate_title']=$show_cate_title;
      return $main;
    }else{
      $xoopsTpl->assign( "jquery" , $jquery) ;
      $xoopsTpl->assign( "page" , $all_news) ;
      $xoopsTpl->assign( "show_admin_tool_title" , $this->admin_tool) ;
      $xoopsTpl->assign( "show_sort_tool" , $this->sort_tool) ;
      $xoopsTpl->assign( "action" , $_SERVER['PHP_SELF']) ;
      $xoopsTpl->assign( "batch_tool" , $this->batch_tool) ;
      $xoopsTpl->assign( "del_js" , $this->del_js()) ;
      $xoopsTpl->assign( "cate_select" , $cate_select) ;
      $xoopsTpl->assign( "author_select" , $author_select) ;
      $xoopsTpl->assign( "bar" , $bar) ;
      $xoopsTpl->assign( "syntaxhighlighter_code" , $syntaxhighlighter_code) ;
      if($this->use_star_rating){
        $xoopsTpl->assign( "rating_js" , $rating_js) ;
      }
      if(is_numeric($this->view_ncsn))$xoopsTpl->assign( "show_cate_title" , $show_cate_title) ;

      if(isset($all_news[0]['news_title'])){
        if (is_object($xoTheme)) {
          $xoTheme->addMeta( 'meta', 'keywords', $all_news[0]['news_title']);
          $xoTheme->addMeta( 'meta', 'description', strip_tags($all_news[0]['content'])) ;
        } else {
          $xoopsTpl->assign('xoops_meta_keywords','keywords',$all_news[0]['news_title']);
          $xoopsTpl->assign('xoops_meta_description',  strip_tags($all_news[0]['content']));
        }
        $xoopsTpl->assign('xoops_module_header', "<meta property='og:image' content='{$all_news[0]['image_big']}'/>  <meta property='og:title' content='{$all_news[0]['news_title']}'/>");
      }


    }
  }


  //取得分類路徑
  function get_cate_path($ncsn="",$sub=false){
    global $xoopsDB;

    if(!$sub){
      $home[_TAD_TO_MOD]=XOOPS_URL."/modules/tadnews/index.php";
    }else{
      $home=array();
    }

    $sql = "select nc_title,of_ncsn from ".$xoopsDB->prefix("tad_news_cate")." where ncsn='{$ncsn}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    list($nc_title,$of_ncsn)=$xoopsDB->fetchRow($result);

    $opt_sub=(!empty($of_ncsn))?$this->get_cate_path($of_ncsn,true):"";

    $opt=$path="";

    if(!empty($nc_title)){
      $opt[$nc_title]=XOOPS_URL."/modules/tadnews/index.php?ncsn=$ncsn";
    }
    if(is_array($opt_sub)){
      $path=array_merge($home,$opt_sub,$opt);
    }elseif(is_array($opt)){
      $path=array_merge($home,$opt);
    }else{
      $path=$home;
    }
    return $path;
  }

  //將路徑轉換為多網址路徑
  function get_pluralink_path($ncsn=""){
    $path=$this->get_cate_path($ncsn);
    $pluralink['title']=implode("||",array_keys($path));
    $pluralink['url']=implode("||",$path);
    return $pluralink;
  }

  //取得分類新聞
  public function get_cate_news($mode='assign'){
    global $xoopsDB,$xoopsTpl,$xoopsUser,$xoopsModuleConfig;

    $syntaxhighlighter_code=$this->get_syntaxhighlighter();

    //取得目前使用者的所屬群組
    if($xoopsUser){
      $User_Groups=$xoopsUser->getGroups();
      $now_uid=$xoopsUser->uid();
    }else{
      $User_Groups=array();
      $now_uid=0;
    }

    $where_news="";

    //分析目前觀看得是新聞還是自訂頁面
    if($this->kind==="news"){
      $kind_chk="and not_news!='1'";
    }elseif($this->kind==="page"){
      $kind_chk="and not_news='1'";
    }else{
      $kind_chk='';
    }

    if(is_array($this->view_ncsn)){
      $show_ncsn=implode(',',$this->view_ncsn);
      $and_cate = empty($show_ncsn)?"" : "and ncsn in({$show_ncsn})";
    }elseif($this->view_ncsn==''){
      $and_cate = "";
    }else{
      $and_cate= "and ncsn={$this->view_ncsn}";
    }

    $sql="select ncsn,nc_title,enable_group,enable_post_group,cate_pic from ".$xoopsDB->prefix("tad_news_cate")." where 1  $and_cate $kind_chk order by sort";
//die($sql);
    $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));

    $pic_w=$xoopsModuleConfig['cate_pic_width']+10;
    $i=0;

    while(list($ncsn,$nc_title,$enable_group,$enable_post_group,$cate_pic)=$xoopsDB->fetchRow($result)){
      //只有可讀的分類才納入
      $cate_read_poewr=$this->chk_cate_power($ncsn,$User_Groups,$enable_group,"read");


      if(!$cate_read_poewr){
        continue;
      }

      $pic=(empty($cate_pic))?XOOPS_URL."/modules/tadnews/images/no_cover.png":_TADNEWS_CATE_URL."/{$cate_pic}";

      $and_enable=($this->show_enable==1)?"and enable='1'":"";

      $sql2 = ($this->kind==="page")? "select * from ".$xoopsDB->prefix("tad_news")." where ncsn='{$ncsn}' $and_enable order by page_sort": "select * from ".$xoopsDB->prefix("tad_news")." where ncsn='{$ncsn}' $and_enable and start_day < '".$this->today."' and (end_day > '".$this->today."' or end_day='0000-00-00 00:00:00') order by always_top desc , start_day desc limit 0,".$this->show_num;


      $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql2));

      $j=0;
      $subnews="";

      $myts =MyTextSanitizer::getInstance();
      while($news=$xoopsDB->fetchArray($result2)){

        foreach($news as $k=>$v){
          $$k=$v;
        }

        $news_read_poewr=$this->chk_news_power($enable_group,$User_Groups);
        if(!$news_read_poewr){
          continue;
        }

        if(is_numeric($this->summary_num) and !empty($this->summary_num)){
          $news_content=strip_tags($news_content);
          $style=(empty($this->summary_css))?"":"style='{$this->summary_css}'";
          //支援xlanguage
          $news_content=$this->xlang($news_content);

          $content="<div $style>".mb_substr ($news_content , 0 , $this->summary_num , _CHARSET)."...</div>";

        }else{
          $content="";
        }


        $news_title=(empty($news_title))?_TADNEWS_NO_TITLE:$news_title;


        $subnews[$j]['content']=$myts->displayTarea($content,1,1,1,1,0);
        $subnews[$j]['post_date']=substr($start_day,0,10);
        $subnews[$j]['always_top_pic']=$this->get_news_pic($always_top,$start_day);
        $subnews[$j]['prefix_tag']=$this->mk_prefix_tag($prefix_tag);
        $subnews[$j]['nsn']=$nsn;
        $subnews[$j]['news_title']=$myts->htmlSpecialChars($news_title);;
        $subnews[$j]['counter']=$counter;
        $j++;

      }
      $all_news[$i]['pic_w']=$pic_w;
      $all_news[$i]['show_pic']=$this->cover_use;
      $all_news[$i]['pic']=$pic;
      $all_news[$i]['nc_title']=$nc_title;
      $all_news[$i]['ncsn']=$ncsn;
      $all_news[$i]['news']=$subnews;
      $all_news[$i]['rowspan']=$j+1;

      $i++;
    }

    if($mode=='return'){
      $main['all_news']=$all_news;
      $main['syntaxhighlighter_code']=$syntaxhighlighter_code;
      return $main;
    }else{
      $xoopsTpl->assign( "all_news" , $all_news) ;
      $xoopsTpl->assign( "syntaxhighlighter_code" , $syntaxhighlighter_code) ;
    }

  }

  //判斷本文之所屬分類是否允許該用戶之所屬群組觀看或發佈
  private function chk_cate_power($ncsn="",$User_Groups="",$enable_group="",$kind="read"){
    global $xoopsDB,$xoopsUser;

    if(!empty($enable_group)){
      $ok=false;
      $cate_enable_group=explode(",",$enable_group);
      if(in_array("",$cate_enable_group)){
        return true;
      }

      foreach($User_Groups as $gid){
        if(in_array($gid,$cate_enable_group) or $gid==1){
          return true;
        }
      }
    }else{
      return true;
    }
    return false;
  }

  //判斷本文是否允許該用戶之所屬群組觀看或發佈
  private function chk_news_power($enable_group="",$User_Groups=""){
    global $xoopsDB,$xoopsUser;

    if(empty($enable_group))return true;

    $news_enable_group=explode(",",$enable_group);
    foreach($User_Groups as $gid){
      if(in_array($gid,$news_enable_group)){
        return true;
      }
    }

    return false;
  }


  //新聞編輯工具
  private function admin_tool($uid,$nsn,$counter="",$ncsn="",$have_read_group=""){
    global $xoopsUser,$xoopsModuleConfig,$xoopsModule;
    if(!$xoopsModule){
      $modhandler = &xoops_gethandler('module');
      $xoopsModule = &$modhandler->getByDirname("tadnews");
    }

    if($xoopsUser){
      $uuid=$xoopsUser->getVar('uid');
      $module_id = $xoopsModule->getVar('mid');
      $isAdmin=$xoopsUser->isAdmin($module_id);
    }else{
      $uuid=$isAdmin="";
    }

    $edit_cate="";
    if(!empty($ncsn)){
      $edit_cate=($this->kind==="page")?"<a href='".XOOPS_URL."/modules/tadnews/admin/page_cate.php?ncsn=$ncsn' class='btn btn-mini' style='font-weight:normal;'><i class='icon-pencil'></i> "._TADNEWS_EDIT_CATE."</a>":"<a href='".XOOPS_URL."/modules/tadnews/admin/cate.php?ncsn=$ncsn' class='btn btn-mini' style='font-weight:normal;'><i class='icon-pencil'></i> "._TADNEWS_EDIT_CATE."</a>";
    }

    $signbtn="";
    if(!empty($have_read_group)){
      $signbtn="<a href='".XOOPS_URL."/modules/tadnews/index.php?op=list_sign&nsn=$nsn' class='btn btn-mini' style='font-weight:normal;'><i class='icon-pencil'></i> "._TADNEWS_DIGN_LIST."</a>";
    }

    $admin_fun=($uid==$uuid or $isAdmin)?"
    $signbtn
    <a href='".XOOPS_URL."/modules/tadnews/post.php' class='btn btn-mini' style='font-weight:normal;'><i class='icon-plus-sign'></i> "._TADNEWS_ADD."</a>
    <a href=\"javascript:delete_tad_news_func($nsn);\" class='btn btn-mini' style='font-weight:normal;'><i class='icon-remove'></i> "._TADNEWS_DEL."</a>
    $edit_cate
    <a href='".XOOPS_URL."/modules/tadnews/post.php?op=tad_news_form&nsn=$nsn' class='btn btn-mini' style='font-weight:normal;'><i class='icon-pencil'></i> "._TADNEWS_EDIT."</a>":"";


    $bbcode=($xoopsModuleConfig['show_bbcode']=='1')?"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn={$nsn}&bb=1' class='btn btn-mini' style='font-weight:normal;'>BBCode</a>":"";


    $fun="
    <div class='btn-group'>
    $admin_fun
    $bbcode
    "._TADNEWS_HOT."{$counter}
    </div>";

    return $fun;
  }



  //刪除的js
  private function del_js(){
    $js="<script>
    function delete_tad_news_func(nsn){
      var sure = window.confirm('"._TADNEWS_SURE_DEL."');
      if (!sure)  return;
      location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_news&nsn=\" + nsn;
    }
    </script>";
    return $js;
  }

  //把字串換成群組
  public function txt_to_group_name($enable_group="",$default_txt="",$syb="<br />"){
    $groups_array= tadnews::get_all_groups();
    if(empty($enable_group)){
      $g_txt=$default_txt;
    }else{
      $gs=explode(",",$enable_group);
      $g_txt="";
      foreach($gs as $gid){
        $g_txt.=$groups_array[$gid]."{$syb}";
      }
    }
    return $g_txt;
  }

  //取得所有群組
  public function get_all_groups(){
    global $xoopsDB;
    $sql = "select groupid,name from ".$xoopsDB->prefix("groups")."";
    $result = $xoopsDB->query($sql);
    while(list($groupid,$name)=$xoopsDB->fetchRow($result)){
      $data[$groupid]=$name;
    }
    return $data;
  }


  //列出所有作者的下拉選單
  private function news_author_select(){
    global $xoopsDB;
    $sql="select uid from ".$xoopsDB->prefix("tad_news")." group by uid";
    $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
    $opt=_TADNEWS_SHOW_AUTHOR_NEWS."
    <select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?show_uid='+this.value\">
    <option value=''></option>";
    while(list($uid)=$xoopsDB->fetchRow($result)){
      $uid_name=XoopsUser::getUnameFromId($uid,1);
      $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;
      $selected=($this->view_uid==$uid)?"selected":"";
      $opt.="<option value='$uid' $selected>$uid_name</option>";
    }
    $opt.="</select>";
    return $opt;
  }

  //列出分類篩選工具
  private function news_cate_select($not_news=""){
    $cate_select=$this->get_tad_news_cate_option(0,0,$this->view_ncsn,0,"1",$not_news);
    $form=_TADNEWS_SHOW_CATE_NEWS."
    <select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?ncsn='+this.value\">
      $cate_select
    </select>";
    return $form;
  }

  //取得分類下拉選單
  public function get_tad_news_cate_option($of_ncsn=0,$level=0,$v="",$blank=true,$this_ncsn="",$no_self="0",$not_news="null"){
    global $xoopsDB,$xoopsUser,$xoopsModule;
    if(!$xoopsModule){
      $modhandler = &xoops_gethandler('module');
      $xoopsModule = &$modhandler->getByDirname("tadnews");
    }

    if ($xoopsUser) {
      $module_id = $xoopsModule->getVar('mid');
      $isAdmin=$xoopsUser->isAdmin($module_id);
    }else{
      $isAdmin=false;
    }

    $ok_cat = tadnews::chk_user_cate_power();

    if($isAdmin){
      $left=$level*10;
      $level+=1;

      $and_not_news=($not_news!="null")?"and not_news='{$not_news}'":"";


      $option=($of_ncsn or !$isAdmin)?"":"<option value='0'></option>";

      $option=="";
      $sql = "select ncsn,nc_title,not_news from ".$xoopsDB->prefix("tad_news_cate")." where of_ncsn='{$of_ncsn}' $and_not_news order by sort";
      $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));

      while(list($ncsn,$nc_title,$not_news)=$xoopsDB->fetchRow($result)){
        if(!in_array($ncsn,$ok_cat))continue;
        if($no_self=='1' and $this_ncsn==$ncsn)continue;
        $selected=($v==$ncsn)?"selected":"";
        $color=($not_news=='1')?"red":"black";
        $option.="<option value='{$ncsn}' style='padding-left: {$left}px;color:{$color};' $selected>{$nc_title}</option>";
        $option.= tadnews::get_tad_news_cate_option($ncsn,$level,$v,$this_ncsn,$no_self,$not_news);
      }
    }else{
      $all_ncsn=implode(",",$ok_cat);
      $sql = "select ncsn,nc_title,not_news from ".$xoopsDB->prefix("tad_news_cate")." where ncsn in($all_ncsn) $and_not_news order by sort";
      $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
      while(list($ncsn,$nc_title,$not_news)=$xoopsDB->fetchRow($result)){
        if(!in_array($ncsn,$ok_cat))continue;
        if($no_self=='1' and $this_ncsn==$ncsn)continue;
        $selected=($v==$ncsn)?"selected":"";
        $color=($not_news=='1')?"red":"black";
        $option.="<option value='{$ncsn}' style='padding-left: {$left}px;color:{$color};' $selected>{$nc_title}</option>";
      }

    }
    return $option;
  }

  //判斷目前登入者在哪些類別中有發表的權利
  public function chk_user_cate_power($kind="post"){
    global $xoopsDB,$xoopsUser,$xoopsModule;
    if(empty($xoopsUser))return false;
    if(!$xoopsModule){
      $modhandler = &xoops_gethandler('module');
      $xoopsModule = &$modhandler->getByDirname("tadnews");
    }
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
    if($isAdmin){
      $ok_cat[]="";
    }
    $user_array=$xoopsUser->getGroups();

    $col=($kind=="post")?"enable_post_group":"enable_group";

    //非管理員才要檢查
    $where=($isAdmin)?"":"where $col!=''";

    $sql = "select ncsn,{$col} from ".$xoopsDB->prefix("tad_news_cate")." $where";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));

    while(list($ncsn,$power)=$xoopsDB->fetchRow($result)){
      if($isAdmin or $kind=="pass"){
        $ok_cat[]=$ncsn;
      }else{
        $power_array=explode(",",$power);
        foreach($power_array as $gid){
          if(in_array($gid,$user_array)){
            $ok_cat[]=$ncsn;
            break;
          }
        }
      }
    }
    return $ok_cat;
  }



  //批次工具
  private function batch_tool(){

    //分析目前觀看得是新聞還是自訂頁面
    //$not_news=($this->kind=="news")?'0':'1';

    $move="<label class='radio'>
    <input type='radio' name='act' value='move_news'>"._TADNEWS_MOVE_TO."
    </label>
    <select name='ncsn'>".$this->get_tad_news_cate_option(0,0,"","","1")."</select>";

    $del="<label class='radio'><input type='radio' name='act' value='del_news'>"._TADNEWS_DEL."</label>";

    $tool="
    <div class=\"row-fluid\">
      <h3>"._TADNEWS_BATCH_TOOLS."</h3>
      <div class='well'>
        <div class='span3'>{$move}</div>
        <div class='span3'>{$del}</div>
        <div class='span3'>
        <input type='hidden' name='kind' value='{$this->kind}'>
        <input type='hidden' name='op' value='batch'>
        <input type='submit' value='"._TADNEWS_NP_SUBMIT."'>
        </div>
      </div>
    </div>";
    return $tool;
  }

  //判斷本文是否該用戶需簽收
  private function have_read_chk($have_read_group="",$nsn=""){
    global $xoopsDB,$xoopsUser;

    //取得目前使用者的所屬群組
    if($xoopsUser){
      $User_Groups=$xoopsUser->getGroups();
      $uid=$xoopsUser->getVar('uid');
    }else{
       //未登入者什麼也不秀出來
      return;
    }

    if(!empty($have_read_group)){

      $have_read_group_arr=explode(",",$have_read_group);

      foreach($User_Groups as $gid){

        if(in_array($gid,$have_read_group_arr)){
           $time=$this->chk_sign_status($uid,$nsn);
           if(!empty($time)){
             $main="<div class='span10 offset1 well' style='background-color:#FFFF99;text-align:center;'>".sprintf(_TADNEWS_SIGN_OK,$time)."</div>";
           }else{
             $main="
             <form action='index.php' method='post' class='form-horizontal'>
             <input type='hidden' name='nsn' value='$nsn'>
             <input type='hidden' name='uid' value='$uid'>
             <input type='hidden' name='op' value='have_read'>
             <div style='text-align:center;'>
             <button type='submit' class='btn btn-primary btn-large'>"._TADNEWS_I_HAVE_READ."</button>
             </div>
             </form>";
           }
           return $main;
        }
      }
    }

     //不需簽收
    return;
  }

  //判斷簽收時間
  private function chk_sign_status($uid="",$nsn=""){
    global $xoopsDB,$xoopsUser;
     $sql="select sign_time from ".$xoopsDB->prefix("tad_news_sign")." where uid='$uid' and nsn='$nsn'";
     $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
     list($sign_time)=$xoopsDB->fetchRow($result);
     return $sign_time;
  }


  //取得附檔
  private function get_news_files($nsn="",$mode=""){
    global $xoopsUser,$xoopsDB,$xoopsModule;
    if(!$xoopsModule){
      $modhandler = &xoops_gethandler('module');
      $xoopsModule = &$modhandler->getByDirname("tadnews");
    }
    $config_handler =& xoops_gethandler('config');
    $xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    //取得目前使用者的所屬群組
    if($xoopsUser){
      $reader_uid=$xoopsUser->getVar('uid');
    }else{
      $reader_uid="";
    }
    //$files=show_files("nsn",$nsn,true,$mode);

    $this->TadUpFiles->set_col('nsn',$nsn);
    $files=$this->TadUpFiles->show_files('upfile',true,$mode,false,false);  //是否縮圖,顯示模式 filename、small,顯示描述,顯示下載次數


    $news=$this->get_tad_news($nsn);
    if($xoopsModuleConfig['download_after_read']=='1' and !empty($news['have_read_group'])){
      $time=$this->chk_sign_status($reader_uid,$nsn);
      if(empty($time) and !empty($files)){

        $files=($mode=="filename")?_TADNEWS_DOWNLOAD_AFTER_READ:"<div class='well'>"._TADNEWS_DOWNLOAD_AFTER_READ."</div>";
      }
    }

    return $files;
  }

  //以流水號取得某筆tad_news資料
  public function get_tad_news($nsn="",$uid_chk=false,$xlanguage=true){
    global $xoopsDB,$xoopsUser,$xoopsModule;
    if(empty($nsn))return;
    if(!$xoopsModule){
      $modhandler = xoops_gethandler('module');
      $xoopsModule = $modhandler->getByDirname("tadnews");
    }
    $sql = "select * from ".$xoopsDB->prefix("tad_news")." where nsn='$nsn'";
    $result = $xoopsDB->query($sql) or redirect_header("index.php",3,show_error($sql));
    $data=$xoopsDB->fetchArray($result);
    //die($sql);
    //die($data['news_title']);
    $news_content=strip_tags($data['news_content']);

    //支援xlanguage
    $news_content=tadnews::xlang($news_content,$xlanguage);

    //$data['news_description']=xoops_substr($news_content, 0, 300);
    $data['news_description']=mb_substr ($news_content , 0 , 300 , _CHARSET);

    if($uid_chk){
      if(empty($xoopsUser)) redirect_header("index.php",3,_TADNEWS_NO_ADMIN_POWER);
      $module_id = $xoopsModule->getVar('mid');
      $isAdmin=$xoopsUser->isAdmin($module_id);
      $uid=$xoopsUser->getVar('uid');
      if(!$isAdmin and $uid!=$data['uid']){
         redirect_header("index.php",3,_TADNEWS_NO_ADMIN_POWER);
      }
    }

    return $data;
  }

  //前置字串
  public function mk_prefix_tag($tag_sn,$mode='1'){
    global $xoopsUser,$xoopsDB;

    if(empty($tag_sn))return;

    $and_enable=($mode=="all")?"":"and enable='1'";

    $sql="select color,tag from ".$xoopsDB->prefix("tad_news_tags")." where `tag_sn`='$tag_sn' {$and_enable}";
    $result=$xoopsDB->query($sql);
    list($color,$tag)=$xoopsDB->fetchRow($result);


    $prefix_tag="<span class=\"label\" style='background-color:$color;font-weight:normal;cursor:pointer;' onClick=\"location.href='".XOOPS_URL."/modules/tadnews/index.php?tag_sn=$tag_sn'\" >$tag</span>";
    return $prefix_tag;
  }


  //抓出目前新聞的上下文編號
  private function news_sort($now_nsn="",$now_ncsn=""){
    global $xoopsDB,$xoopsUser;
    $today=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
    $next_ok=false;

    if($this->kind==="page"){
      $news=$this->get_tad_news($now_nsn);
      $now_ncsn=$news['ncsn'];
      $order="page_sort";
    }else{
      $order="start_day desc";
    }

    //取得目前使用者的所屬群組
    if($xoopsUser){
      $User_Groups=$xoopsUser->getGroups();
    }else{
      $User_Groups=array();
    }
    $where_cate=($now_ncsn)?"and ncsn='$now_ncsn'":"";


    $sql = "select nsn,news_title,start_day,enable_group,ncsn from ".$xoopsDB->prefix("tad_news")."  where enable='1' $where_cate and start_day < '{$today}' and (end_day > '{$today}' or end_day='0000-00-00 00:00:00') order by {$order}";


    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
    $nsnsort="";
    $myts =MyTextSanitizer::getInstance();
    while(list($nsn,$news_title,$start_day,$enable_group,$ncsn)=$xoopsDB->fetchRow($result)){
      if(!$this->read_power_chk($ncsn,$enable_group)){
        continue;
      }

      $date=substr($start_day,0,10);

      if($next_ok){
        $nsnsort['next']['nsn']=$nsn;
        $nsnsort['next']['title']=$myts->htmlSpecialChars($news_title);;
        $nsnsort['next']['date']=$date;
        return $nsnsort;
        exit;
      }elseif($now_nsn==$nsn){
        $next_ok=true;
      }else{
        $nsnsort['back']['nsn']=$nsn;
        $nsnsort['back']['title']=$myts->htmlSpecialChars($news_title);;
        $nsnsort['back']['date']=$date;
      }
    }
    return $nsnsort;
  }


  //判斷本文及所屬分類是否允許該用戶之所屬群組觀看
  private function read_power_chk($ncsn="",$enable_group=""){
    global $xoopsDB,$xoopsUser;

    //取得目前使用者的所屬群組
    if($xoopsUser){
      $User_Groups=$xoopsUser->getGroups();
    }else{
      $User_Groups=array();
    }

    //判斷本文之所屬分類是否允許該用戶之所屬群組觀看
    if(!$this->chk_cate_power($ncsn,$User_Groups)){
      return false;
    }

    //判斷本文是否允許該用戶之所屬群組觀看
    if(!$this->chk_news_power($enable_group,$User_Groups)){
      return false;
    }

    return true;
  }



  //計數器
  public function add_counter($nsn){
    global $xoopsDB;

    $sql = "update ".$xoopsDB->prefix("tad_news")." set  counter = counter + 1 where nsn='$nsn'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, show_error($sql));
    return $nsn;
  }

  //解析分類的設定檔
  public function get_setup($setup=""){
    if(empty($setup))return "";
    $set=explode(";",$setup);
    foreach($set as $s){
      $ss=explode("=",$s);
      $key=$ss[0];
      $val=$ss[1];
      $all[$key]=$val;
    }
    return $all;
  }

  private function show_error($sql=""){
    if(_TAD_NEWS_ERROR_LEVEL==1){
      return mysql_error()."<p>$sql</p>";
    }elseif(_TAD_NEWS_ERROR_LEVEL==2){
      return mysql_error();
    }elseif(_TAD_NEWS_ERROR_LEVEL==3){
      return "sql error";
    }
    return;
  }

/*********************發布*************************/

  //tad_news編輯表單
  public function tad_news_form($nsn="",$ncsn="",$mode=''){
    global $xoopsDB,$xoopsUser,$isAdmin,$xoopsModuleConfig,$xoopsTpl;

    include_once XOOPS_ROOT_PATH."/modules/tadtools/formValidator.php";
    $formValidator= new formValidator("#myForm",false);
    $formValidator_code=$formValidator->render('topLeft');


    include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
    //取得目前使用者的所屬群組
    if($xoopsUser){
      $User_Groups=$xoopsUser->getGroups();
    }else{
      $User_Groups=array();
    }

    $nscn_arr=tadnews::chk_user_cate_power("post");
    if(empty($nscn_arr)) redirect_header("index.php",3, _TADNEWS_NO_ADMIN_POWER."<br>".implode(";",$nscn_arr));

    //抓取預設值
    if(!empty($nsn)){
      $DBV=$this->get_tad_news($nsn,true,false);
    }else{
      $DBV=array();
    }

    //預設值設定

    $nsn=(!isset($DBV['nsn']))?$nsn:$DBV['nsn'];
    $ncsn=(!isset($DBV['ncsn']))?$ncsn:$DBV['ncsn'];
    $news_title=(!isset($DBV['news_title']))?"":$DBV['news_title'];
    $news_content=(!isset($DBV['news_content']))?"":$DBV['news_content'];
//die($news_content);
    $start_day=(!isset($DBV['start_day']) or $DBV['start_day']=="0000-00-00 00:00:00")?date("Y-m-d H:i:s",xoops_getUserTimestamp(time())):$DBV['start_day'];
    $end_day=(!isset($DBV['end_day']) or $DBV['end_day']=="0000-00-00 00:00:00")?"":$DBV['end_day'];
    $enable=(!isset($DBV['enable']))?"":$DBV['enable'];
    $uid=(!isset($DBV['uid']))?$xoopsUser->getVar('uid'):$DBV['uid'];

    $passwd=(!isset($DBV['passwd']))?"":$DBV['passwd'];
    $enable_group=(!isset($DBV['enable_group']))?"":explode(",",$DBV['enable_group']);
    $have_read_group=(!isset($DBV['have_read_group']))?"":explode(",",$DBV['have_read_group']);

    $prefix_tag=(!isset($DBV['prefix_tag']))?"":$DBV['prefix_tag'];
    $always_top=(!isset($DBV['always_top']))?"0":$DBV['always_top'];
    $always_top_date=(!isset($DBV['always_top_date']) or $DBV['always_top_date']=="0000-00-00 00:00:00")?date("Y-m-d H:i:s",time()+15*86400):$DBV['always_top_date'];


    $always_top_checked=($always_top=='1')?"checked":"";


    $SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false, $enable_group, 4, true);
    $SelectGroup_name->addOption("", _TADNEWS_ALL_OK, false);
    $SelectGroup_name->setExtra("class='span12'");
    $enable_group = $SelectGroup_name->render();


    $SelectGroup_name2 = new XoopsFormSelectGroup("", "have_read_group", false, $have_read_group, 4, true);
    $SelectGroup_name2->addOption("", _TADNEWS_ALL_NO, false);
    $SelectGroup_name2->setExtra("class='span12'");
    $have_read_group = $SelectGroup_name2->render();

    //標籤選單
    $prefix_tag_menu=tadnews::prefix_tag_menu($prefix_tag);

    //類別選單
    $cate_select=tadnews::get_tad_news_cate_option(0,0,$ncsn);
    //取得分類數
    $cate_num=tadnews::get_cate_num();

    if($this->editor=="elrte"){
      if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/elrte.php")){
        redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
      }
      include_once XOOPS_ROOT_PATH."/modules/tadtools/elrte.php";
      $elrte=new elrte("tadnews","news_content",$news_content);
      $elrte->setHeight(350);
      $editor=$elrte->render();
    }else{
      if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/fck.php")){
        redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
      }
      include_once XOOPS_ROOT_PATH."/modules/tadtools/fck.php";
      $fck=new FCKEditor264("tadnews","news_content",$news_content);
      $fck->setHeight(350);
      $editor=$fck->render();
    }

    //$editor="<textarea>$news_content</textarea>";

    $op=(empty($nsn))?"insert_tad_news":"update_tad_news";

    $pic=$pic_css="";
    if(!empty($nsn)){
      $pic=$this->get_news_doc_pic("news_pic",$nsn,"big",'db',null,'demo_cover_pic');
      //die($pic);
      if(!empty($pic)){
        $sql = "select files_sn,description from ".$xoopsDB->prefix("tadnews_files_center")." where `col_name`='news_pic' and `col_sn`='{$nsn}' order by sort limit 0,1";
        $result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
        list($files_sn,$pic_css)=$xoopsDB->fetchRow($result);
      }
    }
    $use_pic_css=empty($pic_css)?"":"true";

    //creat_cate_group
    $new_cate_input=empty($cate_num)?_TADNEWS_NAME:"";
    $creat_new_cate=empty($cate_num)?_TADNEWS_CREAT_FIRST_CATE:_TADNEWS_CREAT_NEWS_CATE;
    $creat_cate_tool=(tadnews::chk_news_power(implode(",",$xoopsModuleConfig['creat_cate_group']),$User_Groups))?
    "<input type='text' name='new_cate' id='new_cate_input' class='span2' value='$new_cate_input' placeholder='$creat_new_cate'>":"";

    $now=time();
    $jquery_path=get_jquery(true);

    $css=tadnews::get_pic_css($pic_css);
    $pic_css_set_hide=empty($pic_css)?"$('#pic_css_set').hide();":"";


    $cate_menu=empty($cate_num)?"<div class='span2 text-right'>"._TADNEWS_CREAT_FIRST_CATE._TAD_FOR."</div>":"<select name='ncsn' id='ncsn' class='span2'>$cate_select</select>";



    $jquery_code="
    <script type=\"text/javascript\">
    $('#color').mColorPicker({
    imageFolder: '".XOOPS_URL."/modules/tadnews/class/mColorPicker/images/'
    });

    $(document).ready(function() {

      var \$tabs = $('#jquery-tabs".$now."').tabs({ cookie: { expires: 30 } });
      $pic_css_set_hide
      $('#pic_css').change(function(){
        if($('#pic_css').val()=='true'){
          $('#pic_css_set').show();
        }else{
          $('#pic_css_set').hide();
        }
      });

      $('#setup_form').hide();
      if(!$always_top){
        $('#top_date_input').hide();
      }

      $('#show_input_form').click(function() {
        if ($('#setup_form').is(':visible')) {
           $('#setup_form').slideUp();
        } else{
           $('#setup_form').slideDown();
        }
      });

      $('#always_top').click(function() {
        if ($('#top_date_input').is(':visible')) {
           $('#top_date_input').fadeOut();
        } else{
           $('#top_date_input').fadeIn();
        }
      });

    });

    </script>";

    $form="";
    if($mode=="return"){
      $form['jquery']=$jquery_path;
      $form['jquery_code']=$jquery_code;
      $form['action']=$_SERVER['PHP_SELF'];
      $form['nsn']=$nsn;
      $form['uid']=$uid;
      $form['op']=$op;
      $form['cate_menu']=$cate_menu;
      $form['creat_cate_tool']=$creat_cate_tool;
      $form['prefix_tag_menu']=$prefix_tag_menu;
      $form['news_title']=$news_title;
      $form['news_content']=$news_content;
      $form['editor']=$editor;
      $form['jquery_tabs_id']="jquery-tabs{$now}";
      $form['start_day']=$start_day;
      $form['end_day']=$end_day;
      $form['always_top_checked']=$always_top_checked;
      $form['always_top_date']=$always_top_date;
      $form['enable_group']=$enable_group;
      $form['have_read_group']=$have_read_group;
      $form['enable_checked1']=chk($enable,"1","1");
      $form['enable_checked0']=chk($enable,"0");
      $form['passwd']=$passwd;
      $form['use_pic_css']=chk($use_pic_css,"",1,"selected");
      $form['use_pic_css_true']=chk($use_pic_css,"true",0,"selected");
      $form['pic_css_width']=$css['width'];
      $form['pic_css_height']=$css['height'];
      $form['pic_css_border_width']=$css['border_width'];
      $form['pic_css_border_style_solid']=chk($css["border_style"],"solid",1,"selected");
      $form['pic_css_border_style_dashed']=chk($css["border_style"],"dashed",0,"selected");
      $form['pic_css_border_style_double']=chk($css["border_style"],"double",0,"selected");
      $form['pic_css_border_style_dotted']=chk($css["border_style"],"dotted",0,"selected");
      $form['pic_css_border_style_groove']=chk($css["border_style"],"groove",0,"selected");
      $form['pic_css_border_style_ridge']=chk($css["border_style"],"ridge",0,"selected");
      $form['pic_css_border_style_inset']=chk($css["border_style"],"inset",0,"selected");
      $form['pic_css_border_style_outset']=chk($css["border_style"],"outset",0,"selected");
      $form['pic_css_border_style_none']=chk($css["border_style"],"none",0,"selected");
      $form['pic_css_border_color']=$css['border_color'];
      $form['pic_css_float_left']=chk($css["float"],"left",1,"selected");
      $form['pic_css_float_right']=chk($css["float"],"right",0,"selected");
      $form['pic_css_float_none']=chk($css["float"],"none",0,"selected");
      $form['pic_css_margin']=$css['margin'];
      $form['pic_css_background_repeat_no_repeat']=chk($css["background_repeat"],"no-repeat",1,"selected");
      $form['pic_css_background_repeat_repeat']=chk($css["background_repeat"],"repeat",0,"selected");
      $form['pic_css_background_repeat_x']=chk($css["background_repeat"],"repeat-x",0,"selected");
      $form['pic_css_background_repeat_y']=chk($css["background_repeat"],"repeat-y",0,"selected");
      $form['pic_css_background_position_left_top']=chk($css["background_position"],"left top",1,"selected");
      $form['pic_css_background_position_left_center']=chk($css["background_position"],"left center",0,"selected");
      $form['pic_css_background_position_left_bottom']=chk($css["background_position"],"left bottom",0,"selected");
      $form['pic_css_background_position_right_top']=chk($css["background_position"],"right top",0,"selected");
      $form['pic_css_background_position_right_center']=chk($css["background_position"],"right center",0,"selected");
      $form['pic_css_background_position_right_bottom']=chk($css["background_position"],"right bottom",0,"selected");
      $form['pic_css_background_position_center_top']=chk($css["background_position"],"center top",0,"selected");
      $form['pic_css_background_position_center_center']=chk($css["background_position"],"center center",0,"selected");
      $form['pic_css_background_position_center_bottom']=chk($css["background_position"],"center bottom",0,"selected");
      $form['pic_css_background_size']=chk($css["background_size"],"",1,"selected");
      $form['pic_css_background_size_contain']=chk($css["background_size"],"contain",0,"selected");
      $form['pic_css_background_size_cover']=chk($css["background_size"],"cover",0,"selected");
      $form['pic']=$pic;
      $form['files_sn']=$files_sn;


      $this->TadUpFiles->set_col("nsn",$nsn);
      $upform=$this->TadUpFiles->upform(true,'upfile',NULL,true);
      $form['upform']=$upform;

      //$this->TadUpFiles->set_col("news_pic",$nsn);
      //$upform2=$this->TadUpFiles->js_upform('upfile2');
      //$upform2=$this->TadUpFiles->upform(true,'upfile2',1,true,"gif|jpg|png");
      //$form['upform2']=$upform2;


      $form['bootstrap']=get_bootstrap();
      $form['formValidator_code']=$formValidator_code;

      return $form;
    }else{
      $xoopsTpl->assign("jquery" , $jquery_path);
      $xoopsTpl->assign("jquery_code" , $jquery_code);
      $xoopsTpl->assign("action" , $_SERVER['PHP_SELF']);
      $xoopsTpl->assign("nsn" , $nsn);
      $xoopsTpl->assign("uid" , $uid);
      $xoopsTpl->assign("op" , $op);
      $xoopsTpl->assign("cate_menu" , $cate_menu);
      $xoopsTpl->assign("creat_cate_tool" , $creat_cate_tool);
      $xoopsTpl->assign("prefix_tag_menu" , $prefix_tag_menu);
      $xoopsTpl->assign("news_title" , $news_title);
      $xoopsTpl->assign("news_content" , $news_content);
      $xoopsTpl->assign("editor" , $editor);
      $xoopsTpl->assign("jquery_tabs_id" , "jquery-tabs{$now}");
      $xoopsTpl->assign("start_day" , $start_day);
      $xoopsTpl->assign("end_day" , $end_day);
      $xoopsTpl->assign("always_top_checked" , $always_top_checked);
      $xoopsTpl->assign("always_top_date" , $always_top_date);
      $xoopsTpl->assign("enable_group" , $enable_group);
      $xoopsTpl->assign("have_read_group" , $have_read_group);
      $xoopsTpl->assign("enable_checked1" , chk($enable,"1","1"));
      $xoopsTpl->assign("enable_checked0" , chk($enable,"0"));
      $xoopsTpl->assign("passwd" , $passwd);
      $xoopsTpl->assign("use_pic_css" , chk($use_pic_css,"",1,"selected"));
      $xoopsTpl->assign("use_pic_css_true" , chk($use_pic_css,"true",0,"selected"));
      $xoopsTpl->assign("pic_css_width" , $css['width']);
      $xoopsTpl->assign("pic_css_height" , $css['height']);
      $xoopsTpl->assign("pic_css_border_width" , $css['border_width']);
      $xoopsTpl->assign("pic_css_border_style_solid" , chk($css["border_style"],"solid",1,"selected"));
      $xoopsTpl->assign("pic_css_border_style_dashed" , chk($css["border_style"],"dashed",0,"selected"));
      $xoopsTpl->assign("pic_css_border_style_double" , chk($css["border_style"],"double",0,"selected"));
      $xoopsTpl->assign("pic_css_border_style_dotted" , chk($css["border_style"],"dotted",0,"selected"));
      $xoopsTpl->assign("pic_css_border_style_groove" , chk($css["border_style"],"groove",0,"selected"));
      $xoopsTpl->assign("pic_css_border_style_ridge" , chk($css["border_style"],"ridge",0,"selected"));
      $xoopsTpl->assign("pic_css_border_style_inset" , chk($css["border_style"],"inset",0,"selected"));
      $xoopsTpl->assign("pic_css_border_style_outset" , chk($css["border_style"],"outset",0,"selected"));
      $xoopsTpl->assign("pic_css_border_style_none" , chk($css["border_style"],"none",0,"selected"));
      $xoopsTpl->assign("pic_css_border_color" , $css['border_color']);
      $xoopsTpl->assign("pic_css_float_left" , chk($css["float"],"left",1,"selected"));
      $xoopsTpl->assign("pic_css_float_right" , chk($css["float"],"right",0,"selected"));
      $xoopsTpl->assign("pic_css_float_none" , chk($css["float"],"none",0,"selected"));
      $xoopsTpl->assign("pic_css_margin" , $css['margin']);
      $xoopsTpl->assign("pic_css_background_repeat_no_repeat" , chk($css["background_repeat"],"no-repeat",1,"selected"));
      $xoopsTpl->assign("pic_css_background_repeat_repeat" , chk($css["background_repeat"],"repeat",0,"selected"));
      $xoopsTpl->assign("pic_css_background_repeat_x" , chk($css["background_repeat"],"repeat-x",0,"selected"));
      $xoopsTpl->assign("pic_css_background_repeat_y" , chk($css["background_repeat"],"repeat-y",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_left_top" , chk($css["background_position"],"left top",1,"selected"));
      $xoopsTpl->assign("pic_css_background_position_left_center" , chk($css["background_position"],"left center",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_left_bottom" , chk($css["background_position"],"left bottom",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_right_top" , chk($css["background_position"],"right top",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_right_center" , chk($css["background_position"],"right center",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_right_bottom" , chk($css["background_position"],"right bottom",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_center_top" , chk($css["background_position"],"center top",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_center_center" , chk($css["background_position"],"center center",0,"selected"));
      $xoopsTpl->assign("pic_css_background_position_center_bottom" , chk($css["background_position"],"center bottom",0,"selected"));
      $xoopsTpl->assign("pic_css_background_size" , chk($css["background_size"],"",1,"selected"));
      $xoopsTpl->assign("pic_css_background_size_contain" , chk($css["background_size"],"contain",0,"selected"));
      $xoopsTpl->assign("pic_css_background_size_cover" , chk($css["background_size"],"cover",0,"selected"));
      $xoopsTpl->assign("pic" , $pic);
      $xoopsTpl->assign("files_sn" , $files_sn);

      $this->TadUpFiles->set_col("nsn",$nsn);
      $upform=$this->TadUpFiles->upform(true,'upfile',NULL,true);
      $xoopsTpl->assign( "upform" , $upform) ;


      $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
      $xoopsTpl->assign( "formValidator_code" , $formValidator_code) ;
    }
  }



  //標籤下拉選單
  private function prefix_tag_menu($prefix_tag=""){
    global $xoopsDB,$xoopsModuleConfig;

    $sql="select tag_sn,color,tag from ".$xoopsDB->prefix("tad_news_tags")." where `enable`='1'";
    $result=$xoopsDB->query($sql);
    $option="";
    while(list($tag_sn,$color,$tag)=$xoopsDB->fetchRow($result)){
      $selected=($prefix_tag==$tag_sn)?"selected":"";
      $option.="<option value='{$tag_sn}' $selected>{$tag}</option>";
    }

    $select = "<select name='prefix_tag' class='span2'><option value=''>"._TADNEWS_PREFIX_TAG."</option>$option</select>";
    return $select;
  }



  //取得分類數
  private function get_cate_num(){
    global $xoopsDB;
    $sql = "select count(*) from ".$xoopsDB->prefix("tad_news_cate")." where not_news='0'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
    list($count)=$xoopsDB->fetchRow($result);
    return $count;
  }


  //取得新聞封面圖片檔案
  private function get_news_doc_pic($col_name="",$col_sn="",$mode="big",$style="db",$only_url=false,$id='cover_pic'){
    global $xoopsDB,$xoopsUser,$xoopsModule;

    $sql = "select * from ".$xoopsDB->prefix("tadnews_files_center")." where `col_name`='{$col_name}' and `col_sn`='{$col_sn}' order by sort";

    $result=$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
    while($all=$xoopsDB->fetchArray($result)){
      //以下會產生這些變數： $files_sn, $col_name, $col_sn, $sort, $kind, $file_name, $file_type, $file_size, $description
      foreach($all as $k=>$v){
        $$k=$v;
      }

      $style_set=($style=='db')?$description:$style;

      if(empty($style) and !$only_url)return;

      if($mode!="big"){
        if($only_url){
          return XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name}";
        }else{
          $img=($style=='db')?"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn'><div id='$id' style='background-image:url(".XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name});{$style_set}'></div></a>":"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='".XOOPS_URL."/uploads/tadnews/image/.thumbs/{$file_name}'></a>";
          return $img;
        }
      }else{
        if($only_url){
          return XOOPS_URL."/uploads/tadnews/image/{$file_name}";
        }else{
          $img=($style=='db')?"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn'><div id='$id' style='background-image:url(".XOOPS_URL."/uploads/tadnews/image/{$file_name});{$style_set}'></div></a>":"<a href='".XOOPS_URL."/modules/tadnews/index.php?nsn=$col_sn' class='thumbnails' style='{$style_set}'><img src='".XOOPS_URL."/uploads/tadnews/image/{$file_name}'></a>";
          return $img;
        }
      }
    }
    return ;
  }


  //取得圖片的 CSS 設定
  private function get_pic_css($pic_css=''){
    if(empty($pic_css)){
      $css['width']=200;
      $css['height']=150;
      $css['border_width']='1';
      $css['border_style']='solid';
      $css['border_color']='#909090';
      $css['background_position']='left top';
      $css['background_repeat']='no-repeat';
      $css['float']='left';
      $css['margin']='4';
      $css['background_size']='contain';
    }else{
      $cssArr=explode(';' , $pic_css);
      foreach($cssArr as $css_set){
        list($k,$v)=explode(':',$css_set);
        $k=trim($k);
        $v=trim($v);
        $set[$k]=$v;
      }
      $css['width']=is_null($set['width'])?null:str_replace('px','',$set['width']);
      $css['height']=is_null($set['height'])?null:str_replace('px','',$set['height']);
      if(!is_null($set['border'])){
        list($borderwidth,$borderstyle,$bordercolor)=explode(' ',$set['border']);
        $css['border_width']=is_null($borderwidth)?null:str_replace('px','',$borderwidth);
        $css['border_style']=is_null($borderstyle)?null:$borderstyle;
        $css['border_color']=is_null($bordercolor)?null:$bordercolor;
      }

      $css['background_position']=is_null($set['background-position'])?null:$set['background-position'];
      $css['background_repeat']=is_null($set['background-repeat'])?null:$set['background-repeat'];
      $css['float']=is_null($set['float'])?null:$set['float'];
      $css['margin']=is_null($set['margin'])?null:str_replace('px','',$set['margin']);
      $css['background_size']=is_null($set['background-size'])?null:$set['background-size'];
    }
    return $css;
  }


  //新增資料到tad_news中
  public function insert_tad_news(){
    global $xoopsDB,$xoopsUser,$isAdmin,$xoopsModuleConfig;
    $uid=$xoopsUser->getVar('uid');

    if(empty($_POST['enable_group']) or in_array("",$_POST['enable_group'])){
      $enable_group="";
    }else{
      $enable_group=implode(",",$_POST['enable_group']);
    }

      //需簽收群組
    if(empty($_POST['have_read_group']) or in_array("",$_POST['have_read_group'])){
      $have_read_group="";
    }else{
      $have_read_group=implode(",",$_POST['have_read_group']);
    }

    //新分類
    if(!empty($_POST['new_cate'])){
      $ncsn=$this->creat_tad_news_cate($_POST['ncsn'],$_POST['new_cate']);
    }else{
      $ncsn=intval($_POST['ncsn']);
    }

    $myts =MyTextSanitizer::getInstance();
    $news_title=$myts->addSlashes($_POST['news_title']);
    $news_content=$myts->addSlashes($_POST['news_content']);
    $always_top=(empty($_POST['always_top']))?"0":"1";
    $pic_css=tadnews::mk_pic_css($_POST['pic_css']);
    //die($pic_css);
    if(!empty($_FILES['upfile2']) and empty($pic_css) and $_POST['pic_css']['use_pic_css']){
      $pic_css=$xoopsModuleConfig['cover_pic_css'];
    }

    $sql = "insert into ".$xoopsDB->prefix("tad_news")." (ncsn,news_title,news_content,start_day,end_day,enable,uid,passwd,enable_group,prefix_tag,always_top,always_top_date,have_read_group) values('{$ncsn}','{$news_title}','{$news_content}','{$_POST['start_day']}','{$_POST['end_day']}','{$_POST['enable']}','{$uid}','{$_POST['passwd']}','{$enable_group}','{$_POST['prefix_tag']}','{$always_top}','{$_POST['always_top_date']}','{$have_read_group}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

    //取得最後新增資料的流水編號
    $nsn=$xoopsDB->getInsertId();

    //處理上傳的檔案
    //upload_file('upfile',"nsn",$nsn);
    $this->TadUpFiles->set_col('nsn',$nsn);
    $this->TadUpFiles->upload_file('upfile',$xoopsModuleConfig['pic_width'],$xoopsModuleConfig['thumb_width']);


    //修改暫存封面圖
    if($_POST['files_sn']){
      $pic_css=$this->mk_pic_css($_POST['pic_css']);

      $files_sn=intval($_POST['files_sn']);
      $sql="update ".$xoopsDB->prefix("tadnews_files_center")." set col_name='news_pic' , col_sn='{$nsn}' , description='{$pic_css}' where files_sn='$files_sn'";
      $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

      $pic=$this->get_news_doc_pic("news_pic",$nsn,"big",'db',true,'demo_cover_pic');

      $ff=explode('.',$_FILES['upfile2']['name']);
      foreach($ff as $ext_name){
        $ext=strtolower($ext_name);
      }
      $new_name="news_pic_{$nsn}_1.{$ext}";
      $this->TadUpFiles->rename_file($files_sn,$new_name);
    }

    $xoopsUser->incrementPost();

    $cate=$this->get_tad_news_cate($_POST['ncsn']);
    $page=($cate['not_news']=='1')?"page":"index";
    header("location: ".XOOPS_URL."/modules/tadnews/{$page}.php?nsn={$nsn}");
    exit;
    return $nsn;
  }


  //新增資料到tad_news_cate中
  private function creat_tad_news_cate($of_ncsn="",$new_cate="",$not_news='0'){
    global $xoopsDB,$xoopsModuleConfig;
    $enable_group=$enable_post_group=$setup="";
    $sort=$this->get_max_sort($of_ncsn);

    $myts =MyTextSanitizer::getInstance();
    $new_cate=$myts->addSlashes($new_cate);

    $sql = "insert into ".$xoopsDB->prefix("tad_news_cate")." (of_ncsn,nc_title,enable_group,enable_post_group,sort,not_news,setup) values('{$of_ncsn}','{$new_cate}','{$enable_group}','{$enable_post_group}','{$sort}','{$not_news}','{$setup}')";
    //die($sql);
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _TADNEWS_DB_ADD_ERROR1);
    //取得最後新增資料的流水編號
    $ncsn=$xoopsDB->getInsertId();
    return $ncsn;
  }


  //自動取得新排序
  public function get_max_sort($of_ncsn="",$not_news=0){
    global $xoopsDB,$xoopsModule;
    $sql = "select max(sort) from ".$xoopsDB->prefix("tad_news_cate")." where of_ncsn='$of_ncsn' and not_news='$not_news'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
    list($sort)=$xoopsDB->fetchRow($result);
    return ++$sort;
  }


  //把圖片的 CSS 設定整成成一般css
  private function mk_pic_css($set=''){
    if(empty($set) or empty($set['use_pic_css'])){
      $pic_css='';
    }else{
      $pic_css='';
      $pic_css.=is_null($set['width'])?'':"width:{$set['width']}px; ";
      $pic_css.=is_null($set['height'])?'':"height:{$set['height']}px; ";
      $pic_css.=is_null($set['border_width'])?'':"border:{$set['border_width']}px {$set['border_style']} {$set['border_color']}; ";
      $pic_css.=is_null($set['background_position'])?'':"background-position:{$set['background_position']}; ";
      $pic_css.=is_null($set['background_repeat'])?'':"background-repeat:{$set['background_repeat']}; ";
      $pic_css.=is_null($set['background_size'])?'':"background-size:{$set['background_size']}; ";
      $pic_css.=is_null($set['float'])?'':"float:{$set['float']}; ";
      $pic_css.=is_null($set['margin'])?'':"margin:{$set['margin']}px; ";

    }
    return $pic_css;
  }

  //以流水號取得某筆tad_news_cate資料
  public function get_tad_news_cate($ncsn=""){
    global $xoopsDB;
    if(empty($ncsn))return;
    $ncsn=intval($ncsn);
    $sql = "select * from ".$xoopsDB->prefix("tad_news_cate")." where ncsn='$ncsn'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
    $data=$xoopsDB->fetchArray($result);
    return $data;
  }



  //更新tad_news某一筆資料
  public function update_tad_news($nsn=""){
    global $xoopsDB,$xoopsUser,$xoopsModuleConfig;
    $uid=$xoopsUser->getVar('uid');

    //確認有管理員或本人才能管理
    $news=$this->get_tad_news($nsn);
    if(!$this->chk_who($news['uid'])){
      redirect_header($_SERVER['PHP_SELF'],3, _TADNEWS_NO_ADMIN_POWER);
    }

    //可讀群組
    if(empty($_POST['enable_group']) or in_array("",$_POST['enable_group'])){
      $enable_group="";
    }else{
      $enable_group=implode(",",$_POST['enable_group']);
    }

    //需簽收群組
    if(empty($_POST['have_read_group']) or in_array("",$_POST['have_read_group'])){
      $have_read_group="";
    }else{
      $have_read_group=implode(",",$_POST['have_read_group']);
    }

    if(!empty($_POST['new_cate'])){
      $ncsn=$this->creat_tad_news_cate($_POST['ncsn'],$_POST['new_cate']);
    }else{
      $ncsn=intval($_POST['ncsn']);
    }

    $myts =MyTextSanitizer::getInstance();
    $news_title=$myts->addSlashes($_POST['news_title']);
    $news_content=$myts->addSlashes($_POST['news_content']);
    $always_top=(empty($_POST['always_top']))?"0":"1";


    $sql = "update ".$xoopsDB->prefix("tad_news")." set  ncsn = '{$ncsn}', news_title = '{$news_title}', news_content = '{$news_content}', start_day = '{$_POST['start_day']}', end_day = '{$_POST['end_day']}', enable = '{$_POST['enable']}', passwd = '{$_POST['passwd']}', enable_group = '{$enable_group}',prefix_tag='{$_POST['prefix_tag']}',always_top='{$always_top}',always_top_date='{$_POST['always_top_date']}',have_read_group='{$have_read_group}' where nsn='$nsn'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

    //處理上傳的檔案
    //upload_file('upfile',"nsn",$nsn);
    $this->TadUpFiles->set_col('nsn',$nsn);
    $this->TadUpFiles->upload_file('upfile',$xoopsModuleConfig['pic_width'],$xoopsModuleConfig['thumb_width'],NULL,NULL,true);

    //修改暫存封面圖
    if($_POST['files_sn']){
      $pic_css=$this->mk_pic_css($_POST['pic_css']);

      $files_sn=intval($_POST['files_sn']);
      $sql="update ".$xoopsDB->prefix("tadnews_files_center")." set col_name='news_pic' , col_sn='{$nsn}' , description='{$pic_css}' where files_sn='$files_sn'";
      $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

    }

    $cate=$this->get_tad_news_cate($_POST['ncsn']);
    $page=($cate['not_news']=='1')?"page":"index";
    header("location: ".XOOPS_URL."/modules/tadnews/{$page}.php?nsn={$nsn}");
    exit;
  }


  //身份查核
  private function chk_who($author_id=""){
    global $xoopsDB,$xoopsUser,$xoopsModule;
    if(empty($xoopsUser))return false;
    if(!$xoopsModule){
      $modhandler = &xoops_gethandler('module');
      $xoopsModule = &$modhandler->getByDirname("tadnews");
    }
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
    if($isAdmin)return true;

    $uid=$xoopsUser->getVar('uid');
    if($uid==$author_id) return true;

    return false;
  }


  //刪除tad_news某筆資料資料
  public function delete_tad_news($nsn=""){
    global $xoopsDB,$xoopsUser,$xoopsModule;

    //確認有管理員或本人才能管理
    $news=tadnews::get_tad_news($nsn);
    if(!tadnews::chk_who($news['uid'])){
      redirect_header($_SERVER['PHP_SELF'],3, _TADNEWS_NO_ADMIN_POWER);
    }

    $sql = "delete from ".$xoopsDB->prefix("tad_news")." where nsn='$nsn'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

    //刪除檔案
    //del_files("","nsn",$nsn);

    $this->TadUpFiles->set_col("nsn",$nsn);
    $this->TadUpFiles->del_files();
  }


  //支援xlanguage
  private function xlang($news_content="",$xlanguage=true){
    if($xlanguage and function_exists('xlanguage_ml')){
      $news_content=xlanguage_ml($news_content);
    }
    return $news_content;
  }

}

?>
