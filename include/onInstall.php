<?php
function xoops_module_install_tadnews(&$module) {
	
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/cate");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/file");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/image");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/image/.thumbs");
	
	//建立電子報佈景
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/themes");
	if(!is_dir(XOOPS_ROOT_PATH."/uploads/tadnews/themes/bluefreedom2")){
		full_copy(XOOPS_ROOT_PATH."/modules/tadnews/images/bluefreedom2", XOOPS_ROOT_PATH."/uploads/tadnews/themes/bluefreedom2");
	}
	return true;
}

//建立目錄
function mk_dir($dir=""){
    //若無目錄名稱秀出警告訊息
    if(empty($dir))return;
    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}

//拷貝目錄
function full_copy( $source="", $target=""){
	if ( is_dir( $source ) ){
		@mkdir( $target );
		$d = dir( $source );
		while ( FALSE !== ( $entry = $d->read() ) ){
			if ( $entry == '.' || $entry == '..' ){
				continue;
			}

			$Entry = $source . '/' . $entry;
			if ( is_dir( $Entry ) )	{
				full_copy( $Entry, $target . '/' . $entry );
				continue;
			}
			copy( $Entry, $target . '/' . $entry );
		}
		$d->close();
	}else{
		copy( $source, $target );
	}
}
?>
