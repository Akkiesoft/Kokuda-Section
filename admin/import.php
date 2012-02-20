<?php
include 'common.php';

if (isset($_POST['import'])) {
	/* インポート前のdatファイルの掃除 */
	foreach($xml->article as $item) {
		if (intval($item->isDeleted)) { continue; }
		$id = intval($item->id);
		unlink($modulepath.'/data/'.$id.'.dat');
	}

	/* インポート処理 */
	$xmlTemplate = '<articles></articles>';
	$xml = simplexml_load_string($xmlTemplate);
	$report = '';

	$xoopsDB =& Database::getInstance();
	$sql = "SELECT articleid,title,maintext FROM xoops_xfs_article;";
	$res = $xoopsDB->query($sql);
	while(list($id,$title,$body) = $xoopsDB->fetchRow($res)) {
		$id = intval($id);
		$title = mb_convert_encoding($title, 'UTF-8', 'EUC-JP');
		$body = mb_convert_encoding($body, 'UTF-8', 'EUC-JP');
	
		/* ここにXMLを保存する処理 */
		$newarticle = $xml->addChild('article');
		$newarticle->addChild('id',$id);
		$newarticle->addChild('title',$title);
		$newarticle->addChild('isDeleted','0');
		file_put_contents($xmlpath, $xml->asXML());

		/* データ */
		$path = $modulepath.'/data/'.$id.'.dat';
		file_put_contents($path, $body);

		/* 結果出力 */
		$report .= '<tr><td>'.$id.'</td><td>'.$title.'</td>'
				.  '<td><a href="../article.php?articleid='.$id.'" target="_blank">表示する</a></td></tr>';
	}

	$out = <<<EOM
<a href="./">&lt;&nbsp;一覧に戻る</a><br>
<h1>インポート結果</h1>
<p>以下の通りインポートを実行しました。</p>
<table border="1">
<tr><th>記事ID</th><th style="width:40em;">記事の題名</th><th>記事のリンク</th></tr>
{$report}
</table>
EOM;

	$out = mb_convert_encoding($out, 'EUC-JP');
	xoops_cp_header();
	print $out;
	xoops_cp_footer();
	exit;
}

$out = <<<EOM
<a href="./">&lt;&nbsp;一覧に戻る</a><br>
<h1>XF-SECTIONの記事をKokuda Sectionにインポートする</h1>
<p>XF-SECTIONの記事データをKokuda Sectionにインポートします。<br>
この機能は以下の条件でインポートを行います。きちんと理解した上で使用してください。</p>
<ul>
<li>XF-SECTIONから引き継ぐのは、記事のID、記事の題名、記事の本文です。</li>
<li>本文はHTMLのみをサポートします。XOOPSの書式は使用できません。</li>
<li>引き継ぎを実行すると、Kokuda Sectionにある既存のデータはすべて破棄されます。</li>
</ul>
<p>問題がなければ、実行ボタンをクリックしてインポートを開始してください。</p>
<form action="import.php" method="POST">
<input type="hidden" name="import" value="">
<input type="submit" value="インポートを実行する">
</form>
EOM;

$out = mb_convert_encoding($out, 'EUC-JP');
xoops_cp_header();
print $out;
xoops_cp_footer();
?>
