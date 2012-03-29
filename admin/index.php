<?php
include 'common.php';

$out = <<<EOM
<h1>Kokuda Section</h1>
<p>このモジュールは、はげしくシンプルな記事編集モジュールです。</p>
<p><a href="edit.php?id=0">記事を新規作成する</a></p>
EOM;
if ($json) {
	$out .= <<<EOM
<table border="1">
	<tr>
	  <th style="width:3em;">ID</th>
	  <th style="width:40em;">記事名</th>
	  <th colspan="3">メニュー</th>
	</tr>
EOM;
	foreach ($json as $item) {
		$id    = intval($item['id']);
		$title = htmlspecialchars($item['title']);
		$deleted = intval($item['isDeleted']);
		if ($deleted) { continue; }

		$out .= <<<EOM
	<tr>
	  <td>{$id}</td>
	  <td>{$title}</td>
	  <td><a href="edit.php?id={$id}">編集する</a></td>
	  <td><a href="../article.php?articleid={$id}" target="_blank">表示する</td>
	  <td><a href="delete.php?id={$id}">削除する</td>
	</tr>
EOM;
	}
	$out .= "</table>\n";
}
$out .= <<<EOM
<h2>XF-SECTIONのデータをインポートする</h2>
<p><a href="import.php">こちらからどうぞ。</a></p>
EOM;
$out = mb_convert_encoding($out, 'EUC-JP');

xoops_cp_header();
print $out;
xoops_cp_footer();
?>
