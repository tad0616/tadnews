<?php
include_once "header.php";
include_once XOOPS_ROOT_PATH."/modules/tadtools/TadUpFiles.php" ;
$TadUpFiles=new TadUpFiles("tadnews");

$op=isset($_POST['op'])?$_POST['op']:'';

if($op=="get_pic"){

  $files_sn = isset($_POST['files_sn'])?intval($_POST['files_sn']) : 0;
  echo $TadUpFiles->get_pic_file('images','url',$files_sn);

}else{

  $nsn=intval($_REQUEST['nsn']);

  if(empty($nsn)){
    $rand=rand(0,9999);
    $TadUpFiles->set_col('tmp_news_pic' , $rand , 1);
  }else{
    $TadUpFiles->set_col('news_pic' , $nsn , 1);
  }

  $files_sn=$TadUpFiles->upload_one_file($_FILES['upfile2']['name'],$_FILES['upfile2']['tmp_name'],$_FILES['upfile2']['type'],$_FILES['upfile2']['size'],$xoopsModuleConfig['pic_width'],$xoopsModuleConfig['thumb_width'],NULL ,$xoopsModuleConfig['cover_pic_css'] ,true);


  echo $files_sn;
}
?>