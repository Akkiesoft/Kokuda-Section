<?php
include '../xoops_version.php';
include '../../../mainfile.php';
include '../../../include/cp_header.php';
mb_internal_encoding('UTF-8');

$modulepath = XOOPS_ROOT_PATH.'/modules/'.$modversion['dirname'];

if (!is_dir($modulepath.'/data')) {
	xoops_cp_header();
	print mb_convert_encoding('<h1>エラー</h1><p>dataディレクトリがないので、作ってください。パーミッションは777でおねがいします。<br>作る先のパス: '.$modulepath.'/data</p>', 'EUC-JP');
	xoops_cp_footer();
	exit;
}
if (!is_writable($modulepath.'/data')) {
	xoops_cp_header();
	print mb_convert_encoding('<h1>エラー</h1><p>dataディレクトリのパーミッションを777に設定してください。</p>', 'EUC-JP');
	xoops_cp_footer();
	exit;
}

// JSONは全部で使うので先に読む
$jsonpath = $modulepath.'/data/list.json';
if (is_writable($jsonpath)) {
	$json = json_decode(file_get_contents($jsonpath), TRUE);
} else {
	if (file_exists($jsonpath)) {
		xoops_cp_header();
		print mb_convert_encoding('<h1>エラー</h1><p>list.jsonに書き込み権限がありません。</p>', 'EUC-JP');
		xoops_cp_footer();
		exit;
	}
	$json = '';
}

function getArrayNofromJSON($json, $id){
	$cnt=0;
	foreach($json as $item){
		if ($item['id'] == $id) {
			return $cnt;
		}
		$cnt++;
	}
	return false;
}
?>
