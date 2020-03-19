<?php
require_once('./checkLogin.php');
require_once("./dbConnection.php");
require_once("./constValue.php");

ini_set("display_errors","On");
error_reporting(E_ALL);
$login = new checkLogin();
if (!$login->loginStatus) {
    $ret = ['login' => false];
    header('Content-type: application/json');
    echo json_encode($ret);
    exit;
}
$user_id = $login->user_id;
$path = urldecode(isset($_GET['path']) ? $_GET['path'] : '/root');

$data = new dbConnection();
$sql = $data->getAllFiles($user_id, $path);
$res = $data->runSql($sql);
$ret = $res->fetch_all(MYSQLI_ASSOC);
$data->closeConn();
header('Content-type: application/json');
echo json_encode($ret);