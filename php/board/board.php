<?php
require('numCollection.php');
require('outlet.php');
$conn = getDB(serverName,serverId,serverPassword);
?>
<!DOCTYPE html>
<html>
  <?php
    require('header.php');
  ?>
    <body>
        <section class="wrap">
          <h1>게시판</h1>
            <div id="titleBox">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#writeModal">글쓰기</button>
              <span>전체 게시글 : <?= $totalColum = totalColum($conn,(isset($_GET['searchKey']))?$_GET['searchKey']:""); ?></span>
              <div class="input-group searchBox">
                <?php
                  if(!isset($_GET['searchKey'])) {
                ?>
                    <input type="text" class="form-control" id="searchText" placeholder="검색어를 입력해주세요">
                <?php
                  }else {
                ?>
                <input type="text" class="form-control" id="searchText" placeholder="검색어를 입력해주세요" value="<?= $_GET['searchKey'] ?>">
                <?php
                  }
                ?>
                <div class="input-group-append">
                  <button class="btn btn-primary" id="searchPlay" type="button">검색</button>
                </div>
              </div>
            </div>
            
            <table class="table">
                <tr class="tableTitle">
                    <td>번호</td>
                    <td>제목</td>
                    <td>조회수</td>
                    <td>댓글수</td>
                    <td>작성자</td>
                    <td>작성일</td>
                </tr>
                <!-- 생성될 부분(반복될 부분) -->
                <?php
                // 페이지 번호 유무
                $listScale = 5;//한번에 볼 수 있는 리스트 개수
                $pageScale = 5; //보여질 넘버 수 ex)1 2 3 4 5 >>
                $pageNo = isset($_GET['pageNo'])?$_GET['pageNo']-1:0;
                
                //검색 사용 유무를 통한 검색
                $searchVal = isset($_GET['searchKey'])?$_GET['searchKey']:"";
                $rows = getList($conn,$pageNo, $searchVal, $listScale);
                ?>
                
                <?php
                $n = 1;
                foreach ($rows as $row) :
                  $commetRows = commentList($conn,$row['num']);
                ?>
                    <tr class="textCursor">
                        <td id="rowNo" class="textCenter"><?= (($totalColum - $n) +1) - (($pageNo) * $listScale) ?></td>
                        <td id="titleNum<?= $row['num']?>"><a href="board_view.php?num=<?= $row['num'] ?>&pageNo=<?= $pageNo+1 ?>&searchKey=<?= $searchVal ?>"><?= htmlspecialchars($row['title']) ?></a></td>
                        <td class="textCenter"><?= $row['hits'] ?></td>
                        <td class="textCenter"><?= count($commetRows) ?></td>
                        <td class="textCenter"><?= $row['userId'] ?></td>
                        <td class="textCenter"><?= $row['createDate'] ?></td>
                    </tr>

                <?php
                $n++;
                endforeach
                ?>
                <!-- 반복 끝 -->
              </table>
              <?php
              //전체 페이지 수 & 페이지 구현
              $totalPage = getSearchTotal($conn,$searchVal,$listScale); //전체 페이지 수
              $blockPage = floor($totalPage/$pageScale); //묶음 페이지 수
              $nowPage = floor($pageNo/$pageScale);//현재 묶음 페이지 번호
              
              ?>
              <!-- 숫자 페이징 -->
              <nav aria-label="Page navigation example">
                <ul class="pagination">
                  <li class="page-item">
                    <?php 
                      if($nowPage > 0){
                        $preStart = ($nowPage-1)*$pageScale; 
                    ?>

                    <a class="page-link" href="?pageNo=<?= $preStart + 1 ?>&searchKey=<?= $searchVal ?>" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                      <span class="sr-only">Previous</span>
                    </a>
                        
                    <?php
                      }
                    ?>
                  </li>
                  <?php
                    $blockPageNo = $nowPage * $pageScale;
                    for($i = $blockPageNo; $i < $blockPageNo + $pageScale; $i++){
                      if($i < $totalPage){
                        if($pageNo == $i) {
                  ?>
                  
                  <li class="page-item active"><a class="page-link" href="?pageNo=<?= $i+1 ?>&searchKey=<?= $searchVal ?>"><?= $i + 1 ?></a></li>
                        
                  <?php
                        }else {
                  ?>
                  <li class="page-item"><a class="page-link" href="?pageNo=<?= $i+1 ?>&searchKey=<?= $searchVal ?>"><?= $i + 1 ?></a></li>
                  <?php
                        }
                      }
                    }
                  ?>
                  <li class="page-item">
                    <?php
                      if($nowPage < $blockPage){
                    ?>

                    <a class="page-link" href="?pageNo=<?= $i + 1 ?>&searchKey=<?= $searchVal ?>" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                      <span class="sr-only">Next</span>
                    </a>
                    
                    <?php
                      }
                    ?>
                  </li>
                </ul>
              </nav>

        </section>

        <!-- 글쓰기 modal창 -->
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
                <form onsubmit="return writeCheck()" action="crud.php" method="POST" enctype="multipart/form-data">
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
                  <div class="form-group">
                    <label for="fileToUpload" class="col-form-label">첨부파일 : </label>
                    <input type="file" name="fileToUpload[]" id="imgPreview" multiple>
                  </div>
                  <div class="form-group">
                    <div id="imgBox"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">작성하기</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>

    </body>
</html>

<?php
//파일 오류 경고창
if(isset($_GET['fileup'])) {
  if($_GET['fileup'] === "noExt") {
    print "<script>alert('잘못된 파일입니다.');</script>";
    print("<script>location.href='board.php';</script>"); 
  }else if($_GET['fileup'] === "noSize") {
    print "<script>alert('파일의 용량 초과 입니다.');</script>";
    print("<script>location.href='board.php';</script>"); 
  }
}
//데이터 출력 후 statement를 해제한다
unset($stmt);
//데이터 베이스 접속을 해제한다
unset($conn);
?>