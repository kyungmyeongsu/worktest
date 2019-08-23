<?php
require_once('db.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Board</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="board.css">

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="board.js"></script>
    </head>
    <body>
        <header>
            <h1>게시판</h1>
        </header>
        <section class="wrap">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#writeModal">글쓰기</button>
            <table class="table">
                <tr>
                    <td>번호</td>
                    <td>제목</td>
                    <td>조회수</td>
                    <td>작성일</td>
                </tr>
                <!-- 생성될 부분(반복될 부분) -->
                <?php
                $query = "SELECT * FROM dbo.TB_board";

                $stmt = sqlsrv_query($dbconn,$query);
                
                while($row = sqlsrv_fetch_array($stmt)){
                ?>
                    <tr>
                        <td><?= $row['num']?></td>
                        <td><a href="board_view.php?userId=<?= $row['userId']?>"><?= $row['title'] ?></a></td>
                        <td><?= $row['hits'] ?></td>
                        <td><?= $row['createDate']->format('Y-m-d H:i:s') ?></td>
                    </tr>
                <?php
                }
                ?>
                <!-- 반복 끝 -->
            </table>
        </section>
    </body>
</html>

<?php
//데이터 출력 후 statement를 해제한다
sqlsrv_free_stmt($stmt);
//데이터 베이스 접속을 해제한다
sqlsrv_close($dbconn);
?>


<div class="modal fade" id="writeModal" tabindex="-1" role="dialog" aria-labelledby="boardModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="boardModalLabel">작성하기</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="outlet.php" method="POST">
        <input type="hidden" name="proce" value="writeForm">
          <div class="form-group">
            <label for="title" class="col-form-label">제목 : </label>
            <input type="text" class="form-control" name="title" id="title">
          </div>
          <div class="form-group">
            <label for="writeContent" class="col-form-label">내용 : </label>
            <textarea class="form-control" name="writeContent" id="writeContent" rows="4"></textarea>
          </div>
          <div class="form-group">
            <label for="userId" class="col-form-label">작성자 : </label>
            <input type="text" class="form-control" name="userId" id="userId">
          </div>
          <div class="form-group">
            <label for="userPassword" class="col-form-label">비밀번호 : </label>
            <input type="password" class="form-control" name="userPassword" id="userPassword">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            <button type="submit" class="btn btn-primary">작성하기</button>
         </div>
        </form>
      </div>
    </div>
  </div>
</div>