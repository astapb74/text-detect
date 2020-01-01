<?php

require_once "./db.php";

define('DB_STRING', 'sqlite:text.db');
define('DB_USER', NULL);
define('DB_PASS', NULL);

$db = new \Common\Repository;

if (!empty($_GET['code'])) {
	$code = (int)$_GET['code'];

	$file = $db->getOne("SELECT * FROM alphavit WHERE code = :code;", [
		':code' => $code
	]);

	header ('Content-Type: image/png');

	echo $file['file'];
}


?>