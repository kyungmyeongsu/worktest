<?php
//DB연결
function getDB($serverName,$serverId,$serverPassword) {
    try {  
        $conn = new PDO( "sqlsrv:server=$serverName ; Database=sisa", $serverId, $serverPassword);  
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
        return $conn;
    } catch(Exception $e) {   
        die( print_r( $e->getMessage() ) );   
    }  
}

//리스트 가져오기
function getList($conn,$pageNo,$searchVal,$listScale) {
    $pageOver = $pageNo * $listScale;
    $trimVal = trim($searchVal);
    $mainQuery =<<<SQL
SELECT num, title, hits, createDate, userId, writeContent 
    FROM dbo.TB_board 
    WHERE 1 = 1
SQL;
    $likeQuery =<<<SQL
    AND title LIKE :searchVal 
SQL;

    $etcQuery =<<<SQL
    ORDER BY num DESC 
    OFFSET :pageOver ROWS FETCH NEXT :listScale ROWS ONLY;
SQL;
    $query = ($searchVal == "")? $mainQuery.$etcQuery : $mainQuery.$likeQuery.$etcQuery;
    $stmt = $conn->prepare($query);
    if(!($searchVal == "")) {
        $stmt->bindValue(':searchVal',"%".$trimVal."%" ,PDO::PARAM_STR);
    }
    $stmt->bindValue(':pageOver',$pageOver ,PDO::PARAM_INT);
    $stmt->bindValue(':listScale',$listScale ,PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}

//전체페이지 수 가져오기
function getSearchTotal($conn,$searchVal,$listScale) {
    // global $conn;
    $trimVal = trim($searchVal);
    $mainQuery =<<<SQL
SELECT count(*) FROM dbo.TB_board WHERE 1 = 1
SQL;
    $likeQuery =<<<SQL
    AND title LIKE :searchVal;
SQL;
    $query = ($searchVal == "")? $mainQuery : $mainQuery.$likeQuery;
    $stmt = $conn->prepare($query);
    if(!($searchVal == "")) {
        $stmt->bindValue(':searchVal',"%".$trimVal."%" ,PDO::PARAM_STR);
    }
    $stmt->execute();
    $rowCount = $stmt->fetchColumn();
    $totalPage = ceil($rowCount/$listScale); //총 페이지 수
    return $totalPage;
}

//상세보기 페이지 조회수 증가
function getHits($conn,$no) {
    // global $conn;
    $query =<<<SQL
UPDATE dbo.TB_board 
    SET hits = hits + 1 
        where num = :num;
SQL;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':num',$no,PDO::PARAM_INT);
    $stmt->execute();
}

//상세보기 페이지 정보 가져오기
function getDetail($conn,$no) {
    // global $conn;
    $query =<<<SQL
SELECT num, title, hits, createDate, userId, writeContent  
    FROM dbo.TB_board 
        where num = :num;
SQL;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':num',$no,PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
}


//전체 게시글 수
function totalColum($conn,$searchVal) {
    // global $conn;
    $trimVal = trim($searchVal);
    $mainQuery =<<<SQL
SELECT count(*) FROM dbo.TB_board WHERE 1 = 1
SQL;
    $likeQuery =<<<SQL
    AND title LIKE :searchVal;
SQL;
    $query = ($searchVal == "")? $mainQuery : $mainQuery.$likeQuery;
    $stmt = $conn->prepare($query);
    if(!($searchVal == "")) {
        $stmt->bindValue(':searchVal',"%".$trimVal."%" ,PDO::PARAM_STR);
    }
    $stmt->execute();
    $totalColum = $stmt->fetchColumn();
    return $totalColum;
}

//같은 게시글 파일 관련정보 받아오기
function fileInfo($conn,$no) {
    $query =<<<SQL
SELECT num, upFileName, fileNum, downCount, realFileName, imgYN  
    FROM dbo.TB_file 
        where fileNum = :num;
SQL;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':num',$no,PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

//선택 게시글 파일 관련정보 받아오기
function selectFileInfo($conn,$no) {
    $query =<<<SQL
SELECT num, upFileName, fileNum, downCount, realFileName, imgYN  
    FROM dbo.TB_file 
        where num = :num;
SQL;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':num',$no,PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
}

//다운로드 시 다운로드 횟수 증가
function downFile($conn,$no) {
    $query=<<<SQL
UPDATE dbo.TB_file SET downCount = downCount + 1 
    WHERE num = :num;
SQL;
    $stmt = $conn->prepare($query);
    $stmt->bindValue('num',$no,PDO::PARAM_INT);
    $stmt->execute();
}

//댓글 리스트 가져오기
function commentList($conn,$no) {
    $query =<<<SQL
SELECT num, commentContent, commentId 
    FROM dbo.TB_comment
        WHERE commentNum = :commentNum;
SQL;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':commentNum',$no,PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetchAll();
    return $row;
}

//해당 게시글의 전체 댓글 수 가져오기
function TotalCommentNum($conn,$no) {
    $query =<<<SQL
SELECT count(*) 
    FROM dbo.TB_board 
        JOIN dbo.TB_comment 
            ON TB_board.num = TB_comment.commentNum 
                WHERE commentNum = :commentNum;
SQL;
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':commentNum', $no, PDO::PARAM_INT);
    $stmt->execute();
    $commCount = $stmt->fetchColumn();
    return $commCount;
}

?>