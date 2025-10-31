<?php
try {
	$pdo = new PDO("mysql:host=localhost;dbname=168DB_56;charset=utf8", "168DB56", "uWxQ93ms");
} catch (PDOException $e) {
	echo "เกิดข้อผิดพลาด : ".$e->getMessage();
}
?>