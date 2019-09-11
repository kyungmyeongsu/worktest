<?php
require('numCollection.php');
require_once('outlet.php');
$no = $_GET['num'];
$pageNum = $_GET['pageNo'];
$searchKeyword = $_GET['searchKey'];
$conn = getDB(serverName,serverId,serverPassword);
if(isset($_GET['update'])?true:false) {}else getHits($conn,$no);
$listSet = getDetail($conn,$no);
$fileRows = fileInfo($conn,$no);
?>

<!DOCTYPE html>
<html>
  <?php
    require('header.php');
  ?>
    <body>
        <section class="wrap">
          <h1>"<?= $listSet['title'] ?>" 의 게시판 내용</h1>
          <?php
            if(isset($_GET['update'])?true:false) {
          ?>
          <form action="crud.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="proce" value="updateProce">
            <input type="hidden" name="pageNum" value="<?= $pageNum ?>">
            <input type="hidden" name="searchKeyword" value="<?= $searchKeyword ?>">
            <input type="hidden" name="rowNumber" value="<?= $listSet['num'] ?>">
            <table class="table">
              <tr>
                <td class="colSize">제목</td>
                <td><input type="text" class="form-control" name="title" value="<?= $listSet['title'] ?>"></td>
              </tr>
              <tr class = "contentSize">
                <td class="colSize">내용</td>
                <td><textarea class="form-control" name="writeContent" rows="6"><?= $listSet['writeContent'] ?></textarea></td>
              </tr>
              <tr>
                <td class="colSize">작성자</td>
                <td><input type="text" class="form-control" name="commitId"></td>
              </tr>
              <tr>
                <td class="colSize">비밀번호</td>
                <td><input type="password" class="form-control" name="commitPassword"></td>
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
                </div>
                <?php }}endforeach ?>
                <div id="imgBox"></div>
                </div>
                </td>
              </tr>
            </table>
            <button type="button" class="btn btn-secondary" id="cancelBtn">취소</button>
            <button type="submit" class="btn btn-primary">확인</button>
          </form>
          <?php
            }else {
          ?>
          <table class="table">
            <tr>
              <td class="colSize">제목</td>
              <td><?= $listSet['title'] ?></td>
            </tr>
            <tr class = "contentSize">
              <td class="colSize">내용</td>
              <td><?= nl2br($listSet['writeContent']) ?></td>
            </tr>
            <tr>
              <td class="colSize">작성자</td>
              <td><?= $listSet['userId'] ?></td>
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
              <p><a class="textNowp" href="uploads/<?= $fileRow['realFileName'] ?>" download><?= $fileRow['upFileName'] ?></a></p>
              <p>다운로드 수 : <b><?= $fileRow['downCount'].$fileRow['num'] ?></b></p>
                  <?php 
                    $realFile = (isset($fileRow['realFileName']))?$fileRow['realFileName']:"";
                    list($fileNm, $exten) = explode(".",$realFile); 
                    if($exten == "jpg" || $exten == "png" || $exten == "gif" || $exten == "jepg") {
                  ?>
                  <div><img src="uploads/<?= $fileRow['realFileName'] ?>" width="90%"></div>
              </div>
                  <?php }}endforeach ?>
              </td>
            </tr>
          </table>
          <button type="button" class="btn btn-danger controlBtn" class="titlePoint" data-toggle="modal" data-target="#loginModal" value="deleteForm">삭제</button>
          <button type="button" class="btn btn-secondary controlBtn" class="titlePoint" data-toggle="modal" data-target="#loginModal" value="updateForm">수정</button>
          <button type="button" class="btn btn-primary" id="backBtn">목록</button>
          <?php
            }
          ?>
          <input type="hidden" id="pageNum" value="<?= $pageNum ?>">
          <input type="hidden" id="searchKeyword" value="<?= $searchKeyword ?>">
        </section>
    </body>

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
</html>

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
