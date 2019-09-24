<?php
require('numCollection.php');
require_once('outlet.php');
$no = $_GET['num'];
$pageNum = $_GET['pageNo'];
$searchKeyword = $_GET['searchKey'];
$conn = getDB(serverName,serverId,serverPassword);
if(isset($_GET['hitUp'])) {
  getHits($conn,$no);
}
$listSet = getDetail($conn,$no);
$fileRows = fileInfo($conn,$no);
$commetRows = commentList($conn,$no);
?>

<!DOCTYPE html>
<html>
  <?php
    require('header.php');
  ?>
    <body>
        <section class="wrap">
          <h1>"<?= htmlspecialchars($listSet['title']) ?>" 의 게시판 내용</h1>
          <?php
            if(isset($_GET['update'])?true:false) {
          ?>
          <!-- 수정시 게시판 뷰페이지 -->
          <form action="crud.php" method="POST" enctype="multipart/form-data" onsubmit="return emptyCheck()">
            <input type="hidden" name="proce" value="updateProce">
            <input type="hidden" name="pageNum" value="<?= $pageNum ?>">
            <input type="hidden" name="searchKeyword" value="<?= $searchKeyword ?>">
            <input type="hidden" name="rowNumber" value="<?= $listSet['num'] ?>">
            <table class="table viewLi">
              <tr>
                <td class="colSize">제목</td>
                <td><input type="text" class="form-control" id="viewTitle" name="title" value="<?= htmlspecialchars($listSet['title']) ?>"></td>
              </tr>
              <tr class = "contentSize">
                <td class="colSize">내용</td>
                <td><textarea class="form-control" name="writeContent" rows="6"><?= htmlspecialchars($listSet['writeContent']) ?></textarea></td>
              </tr>
              <tr>
                <td class="colSize">작성자</td>
                <td><input type="text" id="viewId" class="form-control" name="commitId"></td>
              </tr>
              <tr>
                <td class="colSize">비밀번호</td>
                <td><input type="password" id="viewPassword" class="form-control" name="commitPassword"></td>
              </tr>
              <tr>
                <td class="colSize">조회수</td>
                <td><?= $listSet['hits'] ?></td>
              </tr>
              <tr>
                <td class="colSize">작성일</td>
                <td><?= $listSet['createDate'] ?></td>
              </tr>
              <tr>
                <td class="colSize">등록사진</td>
                <td>
                <div><input type="file" name="fileToUpload[]" id="viewImgPreview" class="imgDel" multiple></div>
                <input type="hidden" class="delChooseList" name="delChooseList" value="">
                <div class="viewImgBox">
                <?php foreach ($fileRows as $fileRow) : 
                  if($fileRow['imgYN'] == 'Y') {
                ?>
                <div class="viewImgBoxSize cursorBtn imgClick">
                  <input type="hidden" class="deleteImgNum" value="<?= $fileRow['num'] ?>">
                    <?php 
                      $realFile = (isset($fileRow['realFileName']))?$fileRow['realFileName']:"";
                      list($fileNm, $exten) = explode(".",$realFile); 
                      if($exten == "jpg" || $exten == "png" || $exten == "gif" || $exten == "jepg") {
                    ?>
                  <div class="preHidden"><img src="uploads/<?= $fileRow['realFileName'] ?>" width="90%"></div>
                  <button type="button" class="btn btn-info btnClose">삭제</button>
                <?php }else if($exten == "txt") { ?>
                  <div class="preHidden"><img src="uploads/textIcon.png" width="60%"></div>
                  <button type="button" class="btn btn-info btnClose">삭제</button>
                <?php }?>
                </div>
                <?php }endforeach ?>
                </div>
                <div id="imgBox"></div>
                </td>
              </tr>
            </table>
            <button type="button" class="btn btn-secondary" id="cancelBtn">취소</button>
            <button type="submit" class="btn btn-primary">확인</button>
          </form>
          <?php
            }else {
          ?>
          <!-- 게시글 뷰 테이블 -->
          <table class="table viewLi">
            <tr>
              <td class="colSize">제목</td>
              <td><?= htmlspecialchars($listSet['title']) ?></td>
            </tr>
            <tr class = "contentSize">
              <td class="colSize">내용</td>
              <td><?= nl2br(htmlspecialchars($listSet['writeContent'])) ?></td>
            </tr>
            <tr>
              <td class="colSize">작성자</td>
              <td><?= htmlspecialchars($listSet['userId']) ?></td>
            </tr>
            <tr>
              <td class="colSize">조회수</td>
              <td><?= $listSet['hits'] ?></td>
            </tr>
            <tr>
              <td class="colSize">작성일</td>
              <td><?= $listSet['createDate'] ?></td>
            </tr>
            <tr>
              <td class="colSize">등록사진</td>
              <td class="viewImgBox">
              <?php foreach ($fileRows as $fileRow) : 
                if($fileRow['imgYN'] == 'Y') {
              ?>
              <div class="viewImgBoxSize">
              
              <p class="aaa"><a class="textNowp" href="fileDownload.php?num=<?= $fileRow['num'] ?>"><?= $fileRow['upFileName'] ?>
              <input class="downNo" type="hidden" value="<?= $fileRow['num'] ?>"></a></p>
              <?php 
                    $realFile = (isset($fileRow['realFileName']))?$fileRow['realFileName']:"";
                    list($fileNm, $exten) = explode(".",$realFile); 
                    if($exten == "jpg" || $exten == "png" || $exten == "gif" || $exten == "jepg") {
                      ?>
                  <div>다운로드 수 : <b class="downBoldNo<?= $fileRow['num'] ?>"><?= $fileRow['downCount'] ?></b></div>
                  <div><img src="uploads/<?= $fileRow['realFileName'] ?>" width="90%"></div>
                <?php }else if($exten == "txt") { ?>
                  <div>다운로드 수 : <b class="downBoldNo<?= $fileRow['num'] ?>"><?= $fileRow['downCount'] ?></b></div>
                  <div class="preHidden"><img src="uploads/textIcon.png" width="60%"></div>
                <?php }?>
                </div>
                <?php }endforeach ?>
              </td>
            </tr>
          </table>

          <button type="button" class="btn btn-danger controlBtn viewBtnVal"  data-toggle="modal" data-target="#loginModal" value="deleteForm">삭제</button>
          <button type="button" class="btn btn-secondary controlBtn viewBtnVal" data-toggle="modal" data-target="#loginModal" value="updateForm">수정</button>
          <button type="button" class="btn btn-primary" id="backBtn">목록</button>

          <!-- 댓글 작성하기 -->
          <div id="commentWrap">
            <h3>댓글 작성하기</h3>
            <form id="commentForm" onsubmit="return emptyCommentCheck()">
              <input type="hidden" name="proce" value="commentWrite">
              <input type="hidden" name="commentNo" value="<?= $no ?>">
              <input type="hidden" name="pageNum" value="<?= $pageNum ?>">
              <input type="hidden" name="searchKeyword" value="<?= $searchKeyword ?>">
              <div id="commentBox">
                <div class="input-group mb-3 commentWidth">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">작성자</span>
                  </div>
                  <input type="text" name="commentId" id="commentId" class="form-control">
                </div>

                <div class="input-group mb-3 commentWidth">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">비밀번호</span>
                  </div>
                  <input type="password" name="commentPassword" id="commentPassword" class="form-control">
                </div>
              </div>

              <div class="commentInbox">
                <textarea class="form-control" name="commentContent" id="commentContent"></textarea>
                <button type="button" class="comtBtn">댓글 작성</button>
              </div>
            </form>
          </div>

          <!-- 댓글 리스트 -->
          <div id="commentListTable">
            <h4>댓글 내용 <span class="commentTotal">&#40; 총 댓글수 : <b class="totalComment"><?= count($commetRows) ?></b> &#41;</span></h4>
            <form id="commentUpForm" action="crud.php" method="POST">
            <table class="table commtLi">
              <?php
              //댓글 수정 부분
                foreach ($commetRows as $commentRow) {
              ?>
                <tr class="commentViewList">
                  <td><?= nl2br(htmlspecialchars($commentRow['commentContent'])) ?></td>
                  <td><?= htmlspecialchars($commentRow['commentId']) ?></td>
                  <td class="commBtnBox">
                    <button type="button" class="btn btn-danger controlBtnComm commetNoVal" data-toggle="modal" data-target="#commloginModal" value="commtDeleteForm">삭제<span class="hiddenBox"><?= $commentRow['num'] ?></span></button>
                    <button type="button" class="btn btn-secondary controlBtnComm commetNoVal" data-toggle="modal" data-target="#commloginModal" value="commtUpdateForm">수정<span class="hiddenBox"><?= $commentRow['num'] ?></span></button>
                  </td>
                </tr>
              <?php
                }
              ?>
            </table>
            </form>
          </div>
          <?php
            }
          ?>
          <!-- 수정 취소시 받아올 값 -->
          <input type="hidden" id="pageNum" value="<?= $pageNum ?>">
          <input type="hidden" id="searchKeyword" value="<?= $searchKeyword ?>">
        </section>
    </body>

    <!-- modal에 값을 받아와서 처리한다. -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="loginModalLabel">로그인</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              <form onsubmit="return loginCheck()" action="crud.php" method="POST">
                <input type="hidden" name="proce" id="proceName" value="">
                <input type="hidden" name="pageNum" value="<?= $pageNum ?>">
                <input type="hidden" name="searchKeyword" value="<?= $searchKeyword ?>">
                <input type="hidden" name="rowNumber" id="rowNumber" value="<?= $listSet['num'] ?>">
                <input type="hidden" name="commNum" id="commNum" value="">
                <div class="form-group">
                  <label for="commitId" class="col-form-label">작성자 : </label>
                  <input type="text" class="form-control" name="commitId" id="commitId">
                </div>
                <div class="form-group">
                  <label for="commitPassword" class="col-form-label">비밀번호 : </label>
                  <input type="password" class="form-control" name="commitPassword" id="commitPassword">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary cloose" data-dismiss="modal">닫기</button>
                  <button type="submit" class="btn btn-primary" id="commitBtn">확인</button>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="commloginModal" tabindex="-1" role="dialog" aria-labelledby="commloginModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="commloginModalLabel">로그인</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              <form onsubmit="return loginCheck()" id="commentFormTable">
                <input type="hidden" name="proce" id="proceNameComm" value="">
                <input type="hidden" name="pageNumComm" value="<?= $pageNum ?>">
                <input type="hidden" name="searchKeywordComm" value="<?= $searchKeyword ?>">
                <input type="hidden" name="rowNumberComm" id="rowNumberComm" value="<?= $listSet['num'] ?>">
                <input type="hidden" name="commNumComm" id="commNumComm" value="">
                <div class="form-group">
                  <label for="commitId" class="col-form-label">작성자 : </label>
                  <input type="text" class="form-control" name="commitIdComm" id="commitIdComm">
                </div>
                <div class="form-group">
                  <label for="commitPassword" class="col-form-label">비밀번호 : </label>
                  <input type="password" class="form-control" name="commitPasswordComm" id="commitPasswordComm">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary cloose" data-dismiss="modal">닫기</button>
                  <button type="button" class="btn btn-primary" id="commSubBtn">확인</button>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
</html>

<script type="text/javascript">
  var filedownUrl = "./crud.php";
  // 다운로드 클릭시 다운로드 수 카운트 새로고침
  $(document).ready(function() {
    $(".textNowp").on("click", function() {
      var fileNumber = $(this).find(".downNo").val();
      $.ajax({
        url: filedownUrl,
        type: 'GET',
        data: {num: fileNumber, fileProc: 'fileDownCtn'},
        success: function (data) {
            // console.log(data);  
            $(".downBoldNo"+fileNumber).html(data);
        },
        error : function (jqXHR, textStatus, errorThrown) {
            alert('ERRORS: ' + textStatus);
        }
      });  
    });

    //댓글 작성시 페이지 이동없이 댓글 붙이기
    $(".comtBtn").on("click", function() {
      var queryString = $("#commentForm").serialize() ;
      // console.log(queryString);
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: queryString,
        success: function (data) {
          if(data == "error") {
            alert("입력되지 않은 값을 확인해주세요.");
          }else {
            $('#commentWrap').find('form')[0].reset();
            var urlCom = (document.URL).replace('hitUp=hitUp&','');
            // console.log(urlCom);
            $('.commtLi').load(urlCom +  ' .commtLi');
            $('.totalComment').load(urlCom +  ' .totalComment');
          }
        },
        error : function (jqXHR, textStatus, errorThrown) {
            alert('ERRORS: ' + textStatus);
        }
      });
    });

    //로그인 후 댓글 수정폼으로 변경 인지 삭제인 지 구분
    $("#commSubBtn").on("click", function() {
      var queryString = $("#commentFormTable").serialize() ;
      // console.log(queryString);
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: queryString,
        success: function (data) {
          if(data == "error") {
            alert("다시 확인해주세요.");
          }else {
            $('.modal-body').find('form')[0].reset();
            if(data == "delete") {
              // console.log(data);
              var urlCom = (document.URL).replace('hitUp=hitUp&','');
              // console.log(urlCom);
              $('.commtLi').load(urlCom +  ' .commtLi');
              $('.totalComment').load(urlCom +  ' .totalComment');
            }else {
              $('.commtLi').html(data);
            }
            $('#commloginModal').modal("hide"); 
          }
        },
        error : function (jqXHR, textStatus, errorThrown) {
            alert('ERRORS: ' + textStatus);
        }
      });
    });

    //수정완료 후 변경
    $(document).on("click","#submitCommBtn", function() {
      var queryString = $("#commentUpForm").serialize() ;
      // console.log(queryString);
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: queryString,
        success: function (data) {
          if(data == "error") {
            alert("다시 확인해주세요.");
          }else {
            $('.modal-body').find('form')[0].reset();
            var urlCom = (document.URL).replace('hitUp=hitUp&','');
            // console.log(urlCom);
            $('.commtLi').load(urlCom +  ' .commtLi');
          }
        },
        error : function (jqXHR, textStatus, errorThrown) {
            alert('ERRORS: ' + textStatus);
        }
      });
    });

  });
</script>
<?php
//아이디와 비밀번호를 재확인
if(isset($_GET['reInput'])) {
  if($_GET['reInput'] === "no")
    print "<script>alert('아이디와 비밀번호를 확인해주세요.');</script>";
}
//데이터 출력 후 statement를 해제한다
unset($stmt);
//데이터 베이스 접속을 해제한다
unset($dbconn);
?>
