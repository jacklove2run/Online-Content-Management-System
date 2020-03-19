<?php
require_once('./checkLogin.php');
require_once("./dbConnection.php");

ini_set("display_errors","On");
error_reporting(E_ALL);

$login = new checkLogin();
if (!$login->loginStatus) {
    echo "<script type='text/javascript'>";
    echo "alert('Wrong username or password, input again!');";
    echo "</script>";
    header("Location:files.html");
    exit();
} else {
    $user_id = $login->user_id;
}

$filepath = urldecode($_POST['path']);
$dirname = $_POST['directory'];
$data = new dbConnection();
$sql = $data->createDir($user_id, $dirname, $filepath);
$res = $data->runSql($sql);
if (!$data->conn->error) {
    echo "<script type='text/javascript'>";
    echo "window.location.href=document.referrer;";
    //echo "window.location.reload();";
    echo "</script>";
} else {
    echo json_encode($data->conn->error);
}
$data->closeConn();