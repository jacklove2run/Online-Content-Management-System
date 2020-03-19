<?php
require_once("./dbConnection.php");
require_once("./constValue.php");

ini_set("display_errors","On");
error_reporting(E_ALL);
$username = $_POST['add_name'];
$password = md5($_POST['add_password']);
$data = new dbConnection();
$sql = $data->selectUser($username,$password);
$res = $data->runSql($sql);
if (!$res || $res->num_rows == 0) {
    $data->closeConn();
    echo "<script type='text/javascript'>";
    echo "alert('Wrong username or password, input again!');";
    echo "history.back();";
    echo "</script>"; 
}
else{
    $id = mysqli_fetch_object($res)->id;
    mysqli_free_result($res);

    $data->closeConn();
    $checksum = md5($id.COOKIE_CHECKSUM_STRING.$id);
    setcookie("id",$id);
    setcookie("checksum", $checksum);
    setcookie("password","TRUE");
    header("Location:files.html");
}
?>
