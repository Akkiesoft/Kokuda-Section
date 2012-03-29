<?php
include 'common.php';

if (isset($_POST['save'])) {
	$id = intval($_POST['save']);
	$title = mb_convert_encoding($_POST['atitle'], 'UTF-8', 'EUC-JP');
	$body = mb_convert_encoding($_POST['abody'], 'UTF-8', 'EUC-JP');

	if ($id == 0) {
		$id = 0;
		foreach($xml as $item){
			$j = intval($item->id);
			if ($id < $j) {
				$id = $item->id;
			}
		}
		$id += 1;
		$newarticle = $xml->addChild('article');
		$newarticle->addChild('id',$id);
		$newarticle->addChild('title',$title);
		$newarticle->addChild('isDeleted','0');
		file_put_contents($xmlpath, $xml->asXML());
	} else {
		// 元の記事の情報を取得
		$arrayno = getArrayNofromXML($xml, $id);
		$mototitle = $xml->article[$arrayno]->title;
		if ($mottitle != $title) {
			// 記事名が書き換わった
			$xml->article[$arrayno]->title = $title;
			file_put_contents($xmlpath, $xml->asXML());
		}
	}

	$path = $modulepath.'/data/'.$id.'.dat';
	file_put_contents($path, $body);

	$title = htmlspecialchars($title);

	xoops_cp_header();
	$out = <<<EOM
<h1>{$title} を保存しました。</h1>
<ul>
<li><a href="edit.php?id={$id}">編集画面に戻る</a></li>
<li><a href="../article.php?articleid={$id}">編集した記事を確認する</a></li>
<li><a href="./">記事一覧に戻る</a></li>
</ul>
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
if ($id == 0) {
	/* New */
	$titleLabel = '新規記事の作成';
	$title = '';
	$dat   = '';
} else {
	$path    = $modulepath.'/data/'.$id.'.dat';
	if (!file_exists($path)) {
		header('Location:./');
		exit;
	}
	if (!is_writable($path)) {
		xoops_cp_header();
		print mb_convert_encoding('<h1>エラー</h1><p>データファイルに書き込み権限がありません。ファイル書き込み権限の設定を確認してください。<br>対象パス: '.$path, 'EUC-JP');
		xoops_cp_footer();
	}
	$dat   = htmlspecialchars(file_get_contents($path));
	$arrayno = getArrayNofromXML($xml, $id);
	$info  = $xml->article[$arrayno];
	$title = htmlspecialchars($info->title);
	$titleLabel = $title.' の編集';
	$readable = $info->readable;
}
$out = <<<EOM
<a href="./">&lt;&nbsp;一覧に戻る</a><br>
<h1>{$titleLabel}</h1>
<form action="./edit.php" method="post" name="editform">
記事名: <input name="atitle" value="{$title}" style="width:40em;"><br><br>
記事本文:<br>
<textarea name="abody" style="width:70em;height:50em;">{$dat}</textarea><br>
<input type="hidden" name="save" value="{$id}">
<input type="submit" value="保存">
<input type="button" value="プレビュー" onClick="preview()">
</form>

EOM;

$out = mb_convert_encoding($out, 'EUC-JP');
xoops_cp_header();
print $out;

print <<<EOM
<script type="text/javascript">
function preview(){
	var form = document.forms['editform'];
	var previewBody = form.abody.value;
	var previewWindow = window.open('','previewWindow','scrollbars=1,resizable=1');
	previewWindow.document.clear();
	previewWindow.document.write('<html><head><title>Preview</title></head>');
	previewWindow.document.write('<body>'+previewBody+'</body></html>');
	previewWindow.document.close();
	previewWindow.focus();
}
</script>
EOM;
xoops_cp_footer();
?>
