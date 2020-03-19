<?php
require_once('./checkLogin.php');
require_once("./dbConnection.php");
require_once("./constValue.php");

$login = new checkLogin();
if (!$login->loginStatus) {
    echo "<script type='text/javascript'>";
    echo "alert('Wrong username or password, input again!');";
    echo "history.back();";
    echo "</script>";
    exit();
} else {
    $user_id = $login->user_id;
}

// file types allowed
$allowedExts = ['gif', 'jpeg', 'jpg', 'png', 'docx', 'pdf', 'txt'];
//$allowedType = ['image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png'];
ini_set("display_errors","On");
error_reporting(E_ALL);
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if ($_FILES["file"]["size"] > FILE_MAX_SIZE_ALLOWED
    || !in_array($extension, $allowedExts)
    //|| !in_array($_FILES["file"]["type"], $allowedType)
    || $_FILES["file"]["error"] > 0
    ) {
    echo 'Wrong file type';
    exit();
}

$filepath = urldecode($_POST['path']);
$filesize = $_FILES["file"]["size"];
$filename = $_FILES["file"]["name"];
$realname = md5($filepath.$filename.time()).'.'.$extension; //storage name
$data = new dbConnection();
$sql = $data->checkFile($user_id, $filepath, $filename); 
$res = $data->runSql($sql);
if ($res && $res->num_rows != 0 && ($res->fetch_assoc()['cnt'] > 0)) {
    mysqli_free_result($res);
    echo "<script type='text/javascript'>";
    echo "alert('File names conflict!');";
    echo "history.back();";
    echo "</script>";
    exit();
} 

$sql = $data->uploadFile($user_id, $extension, $filepath, $filesize, $filename, $realname);
$data->runSql($sql);
//echo json_encode($data->conn->error);
if (!$data->conn->error) {
    move_uploaded_file($_FILES["file"]["tmp_name"], "./upload/" . $realname);
} else {
    echo "<script type='text/javascript'>";
    echo "alert('Upload Failed');";
    echo "</script>";
}
$data->closeConn();
header("Location:files.html?path={$_POST['path']}");

// if(in_array($extension, $allowedExts) 
//     && $_FILES["file"]["size"] < FILE_MAX_SIZE_ALLOWED
//     && in_array($_FILES["file"]["type"], $allowedType)
// ) {
//     if ($_FILES["file"]["error"] > 0)
//     {
//         echo "Error: " . $_FILES["file"]["error"] . "<br>";
//     }
//     else
//     {
//         echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
//         echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
//         echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//         echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
        
//         // 判断当期目录下的 upload 目录是否存在该文件
//         // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
//         if (file_exists("./upload/" . $_FILES["file"]["name"]))
//         {
//             echo $_FILES["file"]["name"] . " 文件已经存在。 ";
//         }
//         else
//         {
//             // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
//             move_uploaded_file($_FILES["file"]["tmp_name"], "./upload/" . $_FILES["file"]["name"]);
//             echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];
//         }
//     }
// }
// else
// {
//     echo "非法的文件格式";
// }
?>