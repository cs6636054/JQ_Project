<?php
include "connect.php";

$response = "denied";
$column_to_check = null;
$value_to_check = null;

if (isset($_GET["username"])) {
    $column_to_check = "username";
    $value_to_check = trim($_GET["username"]);

} elseif (isset($_GET["fullname"])) {
    $column_to_check = "fullname"; 
    $value_to_check = trim($_GET["fullname"]);
}

if ($column_to_check && !empty($value_to_check)) {

    $sql = "SELECT COUNT(*) FROM jq_users WHERE " . $column_to_check . " = :value";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':value', $value_to_check, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            $response = "okay"; //ไม่ซ้ำ
        } else {
            $response = "denied"; //ซ้ำ
        }

    } catch (PDOException $e) {
        error_log("Database error in checkName.php: " . $e->getMessage());
    }
}

usleep(100000);
echo $response;

?>