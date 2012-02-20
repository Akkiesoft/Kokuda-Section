<?php
include 'common.php';

$out = "<h1>記事の一覧</h1><ul>\n";
foreach ($xml as $item) {
	$id    = intval($item->id);
	$title = htmlspecialchars($item->title);
	$deleted = intval($item->isDeleted);
	if ($deleted) { continue; }
	$out .= '<li><a href="article.php?articleid='.$id.'">'.$title."</li>\n";
}
$out .= "</ul>\n";
$out = mb_convert_encoding($out, 'EUC-JP');

include ( XOOPS_ROOT_PATH . '/header.php' );
print $out;
include ( XOOPS_ROOT_PATH . '/footer.php' );
?>
