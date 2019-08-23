<?php
require_once('db.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Board_View</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="board.css">

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="board.js"></script>
    </head>
    <body>
        <header>
            <h1><?= $_GET['userId'] ?>의 게시판 내용</h1>
        </header>
        <section class="wrap">
          <table class="table">
            <tr>
              <td>제목</td>
              <td>제목내용~~</td>
            </tr>
            <tr>
              <td>내용</td>
              <td>작성내용~~</td>
            </tr>
            <tr>
              <td>작성자</td>
              <td>작성자 이름~</td>
            </tr>
            <tr>
              <td>작성일</td>
              <td>작성일~~</td>
            </tr>
          </table>
          <button type="button" class="btn btn-primary">삭제</button>
          <button type="button" class="btn btn-primary">수정</button>
          <button type="button" class="btn btn-primary" id="backBtn">뒤로가기</button>
        </section>
    </body>
</html>

<?php
//데이터 출력 후 statement를 해제한다
// sqlsrv_free_stmt($stmt);
//데이터 베이스 접속을 해제한다
sqlsrv_close($dbconn);
?>
