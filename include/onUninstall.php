<?php
function xoops_module_uninstall_tadnews(&$module) {
  GLOBAL $xoopsDB;

	$date=date("Ymd");

 	rename(XOOPS_ROOT_PATH."/uploads/tadnews",XOOPS_ROOT_PATH."/uploads/tadnews_bak_{$date}");
	return true;
}

function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
?>
