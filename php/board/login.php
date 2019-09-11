<?php
    require('header.php');
?>

<form onsubmit="return loginCheck()" action="crud.php" method="POST">
<input type="hidden" name="proce" value="loginForm">
<input type="hidden" name="rowNumber" id="rowNumber" value="">
    <div class="form-group">
    <label for="commitId" class="col-form-label">작성자 : </label>
    <input type="text" class="form-control" name="commitId" id="commitId">
    </div>
    <div class="form-group">
    <label for="commitPassword" class="col-form-label">비밀번호 : </label>
    <input type="password" class="form-control" name="commitPassword" id="commitPassword">
    </div>
    <div>
    <button type="submit" class="btn btn-primary" id="commitBtn">확인</button>
    </div>
</form>