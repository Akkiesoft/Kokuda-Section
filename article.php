<?php
	include 'common.php';

	if (isset($_GET['articleid'])) {
		$article = intval($_GET['articleid']);
		$file = $modulepath.'/data/'.$article.'.dat';
	}
	else {
		header('Location:./');
		exit;
	}
	if (!file_exists($file)) {
		header('Location:./');
		exit;
	}

	include ( XOOPS_ROOT_PATH . '/header.php' );
	print mb_convert_encoding(file_get_contents($file), 'EUC-JP', 'UTF-8');
	include ( XOOPS_ROOT_PATH . '/footer.php' );
?>