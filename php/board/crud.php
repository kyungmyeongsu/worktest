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

        //공백 입력했을 때 체크
        if(trim($title) == "" || trim($userId) == "" || trim($userPassword) == " ") {
            header("location:/board.php");
            exit();
        }

        //password 암호화
        $encryptPassword = password_hash($userPassword, PASSWORD_DEFAULT);

        //file 유효성 체크 size, extension, mime
        if($_FILES["fileToUpload"]["name"]["0"] == "") {
            $uploadOk = 0;
        } else {
            $uploadOk = 1;
        }
        foreach ($_FILES["fileToUpload"]["tmp_name"] as $n => $name) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$n]);
            
            $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if ($_FILES["fileToUpload"]["size"][$n] > 5000000) {
                $uploadOk = 0;
                header("location:/board.php?fileup=noSize");
                exit();
            }

            $validationMime = array("image/*", "text/*");
            $booleanMime = array_search($_FILES["fileToUpload"]["type"][$n],$validationMime);
            if (!isset($booleanMime)) {
                $uploadOk = 0;
                header("location:/board.php?fileup=noExt");
                exit();
            }

            $validationExt = array("jpg", "jpeg", "png", "gif", "txt");
            $booleanExt = array_search($FileType,$validationExt);
            if(!isset($booleanExt)) {
                $uploadOk = 0;
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
        if($uploadOk == 1) {
            foreach ($_FILES["fileToUpload"]["tmp_name"] as $n => $name) {
                $name = $_FILES["fileToUpload"]["name"][$n];
                list($upFileName, $extension) = explode(".", $name);
                $encodeFileName = base64_encode($upFileName);
                $realFileName = $encodeFileName.".".$extension;
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
        }
        // 확인하기 필요시 주석 해제
        // if ($stmt) {  
        //     echo "Row successfully inserted.\n";  
        // } else {  
        //     echo "Row insertion failed.\n";  
        //     die(print_r(sqlsrv_errors(), true));  
        // } 
        header("location:/board.php");
    }

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

        //공백 입력했을 때 체크
        if(trim($title) == "" || trim($commitId) == "" || trim($commitPassword) == " ") {
            header("location:/board_view.php?num=$no&update=update&pageNo=$pageNum&searchKey=$searchKeyword");
            exit();
        }

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
SELECT realFileName 
    FROM dbo.TB_file
        WHERE num = :fileImgNum;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':fileImgNum',$selectOne,PDO::PARAM_INT);
                $stmt->execute();
                $realFiName = $stmt->fetch();
                unlink('uploads/'.$realFiName['realFileName']);

                    $query =<<<SQL
DELETE FROM dbo.TB_file 
    WHERE num = :fileImgNum;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':fileImgNum',$selectOne,PDO::PARAM_INT);
                $stmt->execute();
                }

                /////////////////////////////////////////////////
                foreach ($_FILES["fileToUpload"]["tmp_name"] as $n => $name) {
                    $name = $_FILES["fileToUpload"]["name"][$n];
                    list($upFileName, $extension) = explode(".", $name);
                    $encodeFileName = base64_encode($upFileName);
                    $realFileName = $encodeFileName.".".$extension;
                    if($name == null || $upFileName == null) break;
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

    //게시글 삭제 하기
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
DELETE FROM dbo.TB_comment where commentNum = :commentNum;
DELETE FROM dbo.TB_board where num = :num;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':fileNum',$no,PDO::PARAM_INT);
                $stmt->bindValue(':commentNum',$no,PDO::PARAM_INT);
                $stmt->bindValue(':num',$no,PDO::PARAM_INT);
                $stmt->execute();
                print("<script>location.href='board.php';</script>");
            }else
                header("location:/board_view.php?num=$no&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        }else
            header("location:/board_view.php?num=$no&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        
    }

    //댓글 작성하기
    if($proce === "commentWrite") {
        $commentId = $_POST["commentId"];
        $commentPassword = $_POST["commentPassword"];
        $commentContent = $_POST['commentContent'];
        $no = $_POST['commentNo'];
        $pageNum = $_POST["pageNum"];
        $searchKeyword = $_POST["searchKeyword"];

        //공백 입력했을 때 체크
        if(trim($commentContent) == "" || trim($commentId) == "" || trim($commentPassword) == "") {
            header("location:/board_view.php?num=$no&pageNo=$pageNum&searchKey=$searchKeyword");
            exit();
        }

        //password 암호화
        $encryptPassword = password_hash($commentPassword, PASSWORD_DEFAULT);

        $query =<<<SQL
INSERT INTO dbo.TB_comment (commentContent, commentId, commentPassword, commentNum) 
    VALUES (:commentContent,:commentId,:commentPassword, :commentNum);
SQL;

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':commentContent',$commentContent,PDO::PARAM_STR);
        $stmt->bindValue(':commentId',$commentId,PDO::PARAM_STR);
        $stmt->bindValue(':commentPassword',$encryptPassword,PDO::PARAM_STR);
        $stmt->bindValue(':commentNum',$no,PDO::PARAM_INT);
        $stmt->execute();
        // header("location:/board_view.php?num=$no&pageNo=$pageNum&searchKey=$searchKeyword");
    }

    //댓글 수정전 확인 절차
    if($proce === "commtUpdateForm") {
        $commitId = $_POST["commitId"];
        $commitPassword = $_POST["commitPassword"];
        $no = $_POST['commNum'];
        $num = $_POST['rowNumber'];
        $pageNum = $_POST["pageNum"];
        $searchKeyword = $_POST["searchKeyword"];
        
        $query =<<<SQL
SELECT commentId, commentPassword FROM dbo.TB_comment where num = :num;
SQL;
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':num',$no,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if($commitId === $row['commentId']) {
            if(password_verify($commitPassword,$row['commentPassword'])) {
                echo $commitId."<br>".$commitPassword."<br>".$no."<br>".$proce;
                header("location:/board_view.php?num=$num&commUp=$no&pageNo=$pageNum&searchKey=$searchKeyword");
            }else
                header("location:/board_view.php?num=$num&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        }else
            header("location:/board_view.php?num=$num&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
    }

    //댓글 수정하기
    if($proce === "commtUpdateProce") {
        $commitId = $_POST["commitId"];
        $commitPassword = $_POST["commitPassword"];
        $commentContent = $_POST["commentContent"];
        $no = $_POST['commentNum'];
        $num = $_POST['rowNumber'];
        $pageNum = $_POST["pageNum"];
        $searchKeyword = $_POST["searchKeyword"];

        //공백 입력했을 때 체크
        if(trim($commentContent) == "" || trim($commitId) == "" || trim($commitPassword) == "") {
            header("location:/board_view.php?num=$num&commUp=$no&pageNo=$pageNum&searchKey=$searchKeyword");
            exit();
        }

        $query =<<<SQL
SELECT commentId, commentPassword FROM dbo.TB_comment WHERE num = :commentNum;
SQL;
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':commentNum',$no,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if($commitId === $row['commentId']) {
            if(password_verify($commitPassword,$row['commentPassword'])) {
                $query =<<<SQL
UPDATE dbo.TB_comment 
    SET commentContent = :commentContent 
        where num = :commentNum;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':commentContent',$commentContent,PDO::PARAM_STR);
                $stmt->bindValue(':commentNum',$no,PDO::PARAM_INT);
                $stmt->execute();
                
                header("location:/board_view.php?num=$num&pageNo=$pageNum&searchKey=$searchKeyword");
            }else
                header("location:/board_view.php?num=$num&reInput=no&commUp=$no&pageNo=$pageNum&searchKey=$searchKeyword");
        }else
            header("location:/board_view.php?num=$num&reInput=no&commUp=$no&pageNo=$pageNum&searchKey=$searchKeyword");
    }

    //댓글 삭제하기
    if($proce === "commtDeleteForm") {
        $commitId = $_POST["commitId"];
        $commitPassword = $_POST["commitPassword"];
        $no = $_POST['commNum'];
        $num = $_POST['rowNumber'];
        $pageNum = $_POST["pageNum"];
        $searchKeyword = $_POST["searchKeyword"];

        $query =<<<SQL
SELECT commentId, commentPassword FROM dbo.TB_comment where num = :num;
SQL;
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':num',$no,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        if($commitId === $row['commentId']) {
            if(password_verify($commitPassword,$row['commentPassword'])) {
                $query =<<<SQL
DELETE FROM dbo.TB_comment where num = :num;
SQL;
                $stmt = $conn->prepare($query);
                $stmt->bindValue(':num',$no,PDO::PARAM_INT);
                $stmt->execute();
                header("location:/board_view.php?num=$num&pageNo=$pageNum&searchKey=$searchKeyword");
            }else
                header("location:/board_view.php?num=$num&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
        }else
            header("location:/board_view.php?num=$num&reInput=no&pageNo=$pageNum&searchKey=$searchKeyword");
    }
}

    // 파일 다운로드 받은 수
if(isset($_GET['fileProc'])) {
    $fileDownCtn = $_GET['fileProc'];
    if($fileDownCtn == "fileDownCtn") {
        $no = $_GET['num'];

        downFile($conn,$no);
        
        $fileCount = selectFileInfo($conn,$no);
        echo $fileCount['downCount'];
    }
}
?>