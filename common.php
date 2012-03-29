<?php
include 'xoops_version.php';
include '../../mainfile.php';
mb_internal_encoding('UTF-8');

$modulepath = XOOPS_ROOT_PATH.'/modules/'.$modversion['dirname'];

// JSONは全部で使うので先に読む
$jsonpath = $modulepath.'/data/list.json';
if (file_exists($jsonpath)) {
	$json = json_decode(file_get_contents($jsonpath), TRUE);
} else {
	$json = Null;
}
?>
