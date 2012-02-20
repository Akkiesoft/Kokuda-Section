<?php
include '../xoops_version.php';
include '../../../mainfile.php';
include '../../../include/cp_header.php';

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

// XMLは全部で使うので先に読む
$xmlpath = $modulepath.'/data/list.xml';
if (is_writable($xmlpath)) {
	$xml = simplexml_load_file($xmlpath);
} else {
	if (file_exists($xmlpath)) {
		xoops_cp_header();
		print mb_convert_encoding('<h1>エラー</h1><p>list.xmlに書き込み権限がありません。</p>', 'EUC-JP');
		xoops_cp_footer();
		exit;
	}
	$xml = simplexml_load_string('<articles></articles>');
}

function getArrayNofromXML($xml, $id){
	$cnt=0;
	foreach($xml as $item){
		if ($item->id == $id) {
			return $cnt;
		}
		$cnt++;
	}
	return false;
}
?>
