<?php
require('numCollection.php');
require('outlet.php');
$conn = getDB(serverName,serverId,serverPassword);

if(isset($_POST['proce'])) {
    $proce = $_POST['proce'];
    
    //글쓰기
    if($proce === "writeForm") {
        $title = $_POST["title"];
        $writeContent = $_POST["writeContent"];
        $userId = $_POST["userId"];
        $userPassword = $_POST["userPassword"];

        //password 암호화
        $encryptPassword = password_hash($userPassword, PASSWORD_DEFAULT);

        //file 유효성 체크 size, extension, mime
        foreach ($_FILES["fileToUpload"]["tmp_name"] as $n => $name) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$n]);
            $uploadOk = 1;
            $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if ($_FILES["fileToUpload"]["size"][$n] > 5000000) {
                header("location:/board.php?fileup=noSize");
                exit();
            }

            $validationMime = array("image/*", "text/*");
            $booleanMime = array_search($_FILES["fileToUpload"]["type"][$n],$validationMime);
            if (!isset($booleanMime)) {
                header("location:/board.php?fileup=noExt");
                exit();
            }

            $validationExt = array("jpg", "jpeg", "png", "gif", "pdf", "txt");
            $booleanExt = array_search($FileType,$validationExt);
            if(!isset($booleanExt)) {
                header("location:/board.php?fileup=noExt");
                exit();
            }
        }

        $query =<<<SQL
INSERT INTO dbo.TB_board (title, writeContent, userId, userPassword) 
    VALUES (:title,:writeContent,:userId,:userPassword);
SQL;

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':title',$title,PDO::PARAM_STR);
        $stmt->bindValue(':writeContent',$writeContent,PDO::PARAM_STR);
        $stmt->bindValue(':userId',$userId,PDO::PARAM_STR);
        $stmt->bindValue(':userPassword',$encryptPassword,PDO::PARAM_STR);
        $stmt->execute();
        $nowNum = $conn->lastInsertId();

        /////////////////////////////////////////////////
        foreach ($_FILES["fileToUpload"]["tmp_name"] as $n => $name) {
            $name = $_FILES["fileToUpload"]["name"][$n];
            list($upFileName, $extension) = explode(".", $name);
            $realFileName = $upFileName."_".$nowNum.".".$extension;
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$n], $target_dir.$realFileName)) {
                echo basename( $_FILES["fileToUpload"]["name"][$n]). "성공!!!";
            }
        /////////////////////////////////////////////////

        $query =<<<SQL
INSERT INTO dbo.TB_file (upFileName, fileNum, realFileName) 
    VALUES (:upFileName,:fileNum,:realFileName);
SQL;
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':upFileName',$name,PDO::PARAM_STR);
        $stmt->bindValue(':fileNum',$nowNum,PDO::PARAM_INT);
        $stmt->bindValue(':realFileName',$realFileName,PDO::PARAM_STR);
        $stmt->execute();
    }
        // 확인하기
        // if ($stmt) {  
        //     echo "Row successfully inserted.\n";  
        // } else {  
        //     echo "Row insertion failed.\n";  
        //     die(print_r(sqlsrv_errors(), true));  
        // } 
        header("location:/board.php");
    }

//     if($proce === "loginForm") {
//         $commitId = $_POST["commitId"];
//         $commitPassword = $_POST["commitPassword"];
//         $no = $_POST['rowNumber'];

//         $query =<<<SQL
// SELECT userId, userPassword FROM dbo.TB_board where num = :num;
// SQL;
//         $stmt = $conn->prepare($query);
//         $stmt->bindValue(':num',$no,PDO::PARAM_INT);
//         $stmt->execute();
//         $row = $stmt->fetch();
//         if($commitId === $row['userId']) {
//             if(password_verify($commitPassword,$row['userPassword'])) {
//                 header("location:/board_view.php?num=$no");
//             }else
//                 header("location:/board_view.php?reInput=no");
//         }else
//             header("location:/board_view.php?reInput=no");
        
//     }

    //수정버튼 클릭시 로그인
    if($proce === "updateForm") {
        $commitId = $_POST["commitId"];
        $commitPassword = $_POST["commitPassword"];
        $no = $_POST['rowNumber'];
        $pageNum = $_POST["pageNum"];
        $searchKeyword = $_POST["searchKeyword"];
        
        $query =<<<SQL
SELECT userId, userPassword FROM dbo.TB_board where num = :num;
SQL;
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':num',$no,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if($commitId === $row['userId']) {
            if(password_verify($commitPassword,$row['userPassword'])) {
                echo $commitId."<br>".$commitPassword."<br>".$no."<br>".$proce;
                header("location:/board_view.php?num=$no&update=update&pageNo=$pageNum&searchKey=$searchKeyword");
            }else
                header("location:/board_view.php?num=$no&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        }else
            header("location:/board_view.php?num=$no&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        
    }

    //수정하기
    if($proce === "updateProce") {
        $title = $_POST["title"];
        $writeContent = $_POST["writeContent"];
        $commitId = $_POST["commitId"];
        $commitPassword = $_POST["commitPassword"];
        $no = $_POST['rowNumber'];
        $pageNum = $_POST["pageNum"];
        $searchKeyword = $_POST["searchKeyword"];
        $delImgCol = $_POST['delChooseList'];

        //file 유효성 체크 size, extension, mime
        foreach ($_FILES["fileToUpload"]["tmp_name"] as $n => $name) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$n]);
            $uploadOk = 1;
            $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if ($_FILES["fileToUpload"]["size"][$n] > 5000000) {
                header("location:/board.php?fileup=noSize");
                exit();
            }

            $validationMime = array("image/*", "text/*");
            $booleanMime = array_search($_FILES["fileToUpload"]["type"][$n],$validationMime);
            if (!isset($booleanMime)) {
                header("location:/board.php?fileup=noExt");
                exit();
            }

            $validationExt = array("jpg", "jpeg", "png", "gif", "pdf", "txt");
            $booleanExt = array_search($FileType,$validationExt);
            if(!isset($booleanExt)) {
                header("location:/board.php?fileup=noExt");
                exit();
            }
        }
        
        //글 수정하기
        $query =<<<SQL
SELECT userId, userPassword FROM dbo.TB_board WHERE num = :num;
SQL;
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':num',$no,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if($commitId === $row['userId']) {
            if(password_verify($commitPassword,$row['userPassword'])) {
                $query =<<<SQL
UPDATE dbo.TB_board 
    SET title = :title, writeContent = :writeContent 
        where num = :num;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':title',$title,PDO::PARAM_STR);
                $stmt->bindValue(':writeContent',$writeContent,PDO::PARAM_STR);
                $stmt->bindValue(':num',$no,PDO::PARAM_INT);
                $stmt->execute();

                // 파일 제외 하기
                $delImgList = explode(",", $delImgCol);
                foreach ($delImgList as $selectOne) {
                    $query =<<<SQL
UPDATE dbo.TB_file 
    SET imgYN = 'N'
        where num = :fileImgNum;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':fileImgNum',$selectOne,PDO::PARAM_INT);
                $stmt->execute();
                }

                /////////////////////////////////////////////////
                foreach ($_FILES["fileToUpload"]["tmp_name"] as $n => $name) {
                    $name = $_FILES["fileToUpload"]["name"][$n];
                    list($upFileName, $extension) = explode(".", $name);
                    $realFileName = $upFileName."_".$no.".".$extension;
                    if($name == null || $realFileName == null) break;
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$n], $target_dir.$realFileName)) {
                        echo basename( $_FILES["fileToUpload"]["name"][$n]). "성공!!!";
                    }
                /////////////////////////////////////////////////
                            
                $query =<<<SQL
INSERT INTO dbo.TB_file (upFileName, fileNum, realFileName) 
    VALUES (:upFileName,:fileNum,:realFileName);
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':upFileName',$name,PDO::PARAM_STR);
                $stmt->bindValue(':realFileName',$realFileName,PDO::PARAM_STR);
                $stmt->bindValue(':fileNum',$no,PDO::PARAM_INT);
                $stmt->execute();
                }
                header("location:/board_view.php?num=$no&pageNo=$pageNum&searchKey=$searchKeyword");
            }else
                header("location:/board_view.php?num=$no&reInput=no&update=update&pageNo=$pageNum&searchKey=$searchKeyword");
        }else
            header("location:/board_view.php?num=$no&reInput=no&update=update&pageNo=$pageNum&searchKey=$searchKeyword");
    }

    //삭제 하기
    if($proce === "deleteForm") {
        $commitId = $_POST["commitId"];
        $commitPassword = $_POST["commitPassword"];
        $no = $_POST['rowNumber'];
        $pageNum = $_POST["pageNum"];
        $searchKeyword = $_POST["searchKeyword"];

        $query =<<<SQL
SELECT userId, userPassword FROM dbo.TB_board where num = :num;
SQL;
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':num',$no,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if($commitId === $row['userId']) {
            if(password_verify($commitPassword,$row['userPassword'])) {
                $query =<<<SQL
DELETE FROM dbo.TB_file where fileNum = :fileNum;
DELETE FROM dbo.TB_board where num = :num;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':fileNum',$no,PDO::PARAM_INT);
                $stmt->bindValue(':num',$no,PDO::PARAM_INT);
                $stmt->execute();
                print("<script>location.href='board.php';</script>");
            }else
                header("location:/board_view.php?num=$no&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        }else
            header("location:/board_view.php?num=$no&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        
    }
}


?>