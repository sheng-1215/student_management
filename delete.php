<?php 
include_once("db.php");
$id = $_GET['id'];

$qry = $conn -> prepare("DELETE FROM `datachart` WHERE `id` = ?");
$qry -> bind_param("i",$id);
$qry -> execute();

header("Location:index.php");
exit();
?>