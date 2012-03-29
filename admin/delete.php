<?php
include 'common.php';

if (isset($_POST['delete'])) {
	$id = intval($_POST['delete']);

	// XMLを更新
	$arrayno = getArrayNofromXML($xml, $id);
	$title = htmlspecialchars($xml->article[$arrayno]->title);
	$xml->article[$arrayno]->title = '';
	$xml->article[$arrayno]->isDeleted = '1';
	file_put_contents($xmlpath, $xml->asXML());

	// データを削除
	$path = $modulepath.'/data/'.$id.'.dat';
	unlink($path);

	xoops_cp_header();
	$out = <<<EOM
<h1>{$title} を削除しました。</h1>
<ul><li><a href="./">記事一覧に戻る</a></li></ul>
EOM;
	print mb_convert_encoding($out, 'EUC-JP');
	xoops_cp_footer();
	exit;
}

if (!isset($_GET['id'])) {
	header('Location:./');
	exit;
}
$id = intval($_GET['id']);
$path    = $modulepath.'/data/'.$id.'.dat';
if (!file_exists($path)) {
	header('Location:./');
	exit;
}
$arrayno = getArrayNofromXML($xml, $id);
$info  = $xml->article[$arrayno];
$title = htmlspecialchars($info->title);
$out = <<<EOM
<a href="./">&lt;&nbsp;一覧に戻る</a><br>
<h1>{$title} の削除</h1>
<p>本当にこの記事を削除して良いですか？</p>
<form action="./delete.php" method="post">
<input type="hidden" name="delete" value="{$id}">
<input type="submit" value="削除する">
</form>
EOM;

$out = mb_convert_encoding($out, 'EUC-JP');
xoops_cp_header();
print $out;
xoops_cp_footer();
?>
