<?php
include 'xoops_version.php';
include '../../mainfile.php';

$modulepath = XOOPS_ROOT_PATH.'/modules/'.$modversion['dirname'];

// XMLは全部で使うので先に読む
$xmlpath = $modulepath.'/data/list.xml';
if (file_exists($xmlpath)) {
	$xml = simplexml_load_file($xmlpath);
}
?>