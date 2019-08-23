<?php
require('db.php');

$proce = $_POST['proce'];

if($proce === "writeForm") {
    $title = $_POST["title"];
    $writeContent = $_POST["writeContent"];
    $userId = $_POST["userId"];
    $userPassword = $_POST["userPassword"];
    $datetime = date('Y-m-d H:i:s');

    print $title."<br>".$writeContent."<br>".$userId."<br>".$userPassword."<br>".$datetime;

    $query = "INSERT INTO dbo.TB_board (title, writeContent, userId, userPassword, createDate, hits) VALUES (?,?,?,?,?,?)";  

    $params = array($title, $writeContent, $userId, $userPassword , $datetime, 0);

    $stmt = sqlsrv_query($dbconn,$query,$params);
    if ($stmt) {  
        echo "Row successfully inserted.\n";  
    } else {  
        echo "Row insertion failed.\n";  
        die(print_r(sqlsrv_errors(), true));  
    } 
    print "<script>location.href='./board.php'</script>";
}

?>