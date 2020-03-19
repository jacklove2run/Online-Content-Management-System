<?php
require("./dbConnection.php");
ini_set("display_errors","On");
error_reporting(E_ALL);
$username = $_POST["add_name"];
$password = md5($_POST["add_password"]);

$data = new dbConnection();
$sql = $data->checkUser($username);
$res = $data->runSql($sql);
if ($res && $res->num_rows != 0 && ($res->fetch_assoc()['cnt'] > 0)) {
    mysqli_free_result($res);
    echo "<script type='text/javascript'>";
    echo "alert('This username has been registered already!');";
    echo "history.back();";
    echo "</script>";
} else {
    $sql = $data->registerUser($username,$password);
    $res = $data->runSql($sql);
    echo json_encode($data->conn->error);
}
$data->closeConn();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register Sucessfully</title>
</head>
 <style type="text/css">
     .wrapper{

         text-align: center;
         width:1000px;
         margin:20px auto;
     }
     h2{
     background-color:#7CCD7C;
     margin:0px;
     text-align:center;
     }
     .my{
         margin:20px auto;
     }
     .my labal{
         text-align: center;
         background-color:     #FFB6C1;
         color: #fff;
         margin:20px auto;
     }
 </style>
<body>
<div class="wrapper">
<h2>Content Management System</h2>
<div class="my">
<labal>Info</labal>
</div>
<labal>Username:</labal><input type="text" value="<?php echo $username ?>" readonly="readonly"><br><br>
<a href="login.html">Back to login</a>
</div>
</body>
</html>



