var isallchecked = 0;

$(document).ready(function(){
    //뒤로가기
    $("#backBtn").on("click", function(){
        var pageNum = $('#pageNum').val();
        var searchKeyword = $('#searchKeyword').val();
        location.href="board.php?pageNo=" + pageNum + "&searchKey=" + searchKeyword;
    });

    //수정 취소 버튼 클릭시
    $(document).on("click","#cancelBtn", function(){
        var pageNum = $('#pageNum').val();
        var searchKeyword = $('#searchKeyword').val();
        var num = $('#rowNumber').val();
        location.href="board_view.php?num="+ num +"&hitUp=hitUp&pageNo=" + pageNum + "&searchKey=" + searchKeyword;
        // window.location = document.referrer;
    });

    //게시글의 rowNumber 받아오기
    $(".textCursor").on("click", function(){
        var numVal = $(this).find('#rowNo').text();
        // console.log($numVal);
        $("#rowNumber").val(numVal);
    });

    //모달 닫으면 값 초기화
    $('.modal').on('hidden.bs.modal', function (e) {
        // console.log('modal close');
        $(this).find('form')[0].reset();
        $(this).find('#imgBox').html("");
    });

    //검색하기
    $("#searchPlay").on("click", function(){
        var searchVal = $("#searchText").val();
        console.log(searchVal);
        location.href="board.php?searchKey="+ searchVal;
        // function searchKeyword(searchVal);
    });

    //검색 input enter key
    $("#searchText").keypress(function(e) {
        if(e.keyCode == 13) {
            $("#searchPlay").click();
        }
    });

    //전체선택하기
    $("#selectTotal").click(function(){
        if(isallchecked == 0){
          for(k=0;k< $(".selectPoint").length;k++){
              $(".selectPoint")[k].checked = true;
          }
          isallchecked = 1;
        }else{
          for(k=0;k< $(".selectPoint").length;k++){
              $(".selectPoint")[k].checked = false;
          }
          isallchecked = 0;
        }
    });

    //삭제값, 수정값 가져오기
    $(document).on("click",".controlBtn", function(){
        var procVal = $(this).val();
        // console.log($numVal);
        $("#proceName").val(procVal);
    });

    //삭제값, 수정값 가져오기
    $(document).on("click",".controlBtnComm", function(){
        var procVal = $(this).val();
        // console.log($numVal);
        $("#proceNameComm").val(procVal);
    });

    //댓글 수정, 삭제 버튼 클릭시 해당 컬럼 번호 추출
    $(document).on("click",".commetNoVal", function(){
        var commnetVal = $(this).find('.hiddenBox').text();
        // console.log(commnetVal);
        $("#commNumComm").val(commnetVal);
    });
    
    //파일 업로드 함수
    $("#imgPreview").on("change", fileSelect);
    $("#viewImgPreview").on("change", fileListSel);

    //
    $("#imgPreview").on("change", function(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function(f) {
            if(f.type.match("text/*")) {
                $("#imgBox").css("display","none");
            }
            if(f.type.match("image/*")) {
                $("#imgBox").css("display","block");
            }
        })
    });

    var delArr = [];
    //이미지 클릭시 opacity 변경
    $(".imgClick").on("click", function() {
        if($(this).hasClass("delImg") === true) {
            $(this).removeClass("delImg");
        }else {
            $(this).addClass("delImg");
        }

        var delChoose = $(this).children(".deleteImgNum").val();
        $(".delChooseList").val("");
        if($.inArray(delChoose, delArr) == -1) {
            delArr.push(delChoose);
            $(".delChooseList").val(delArr);
        }else {
            delArr.splice($.inArray(delChoose, delArr), 1);
            $(".delChooseList").val(delArr);
        }
    });
});

//글쓰기 체크사항
function writeCheck() {
    if($('#title').val().length < 1) {
        alert('제목을 입력해 주세요.');
        $('#title').focus();
        return false;
    }else if($('#writeContent').val().length < 1) {
        alert('내용을 입력해 주세요.');
        $('#writeContent').focus();
        return false;
    }else if($('#userId').val().length < 1) {
        alert('작성자를 입력해 주세요.');
        $('#userId').focus();
        return false;
    }else if($('#userPassword').val().length < 1) {
        alert('비밀번호를 입력해 주세요.');
        $('#userPassword').focus();
        return false;
    }else if($('#userPassword').val().length > 1) {
        var reg = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
        var password = $('#userPassword').val();
        if(false === reg.test(password)) {
            alert('비밀번호는 8자 이상이어야 하며, 숫자/대문자/소문자/특수문자를 모두 포함해야 합니다.');
            return false;
        }
    }else true;
}

//로그인 체크사항
function loginCheck() {
    if($('#commitId').val().length < 1) {
        alert('작성자를 입력해 주세요.');
        $('#commitId').focus();
        return false;
    }else if($('#commitPassword').val().length < 1) {
        alert('비밀번호를 입력해 주세요.');
        $('#commitPassword').focus();
        return false;
    }else true;
}

//수정하기 비어있는 input 체크
function emptyCheck() {
    if($('#viewTitle').val().length < 1) {
        alert('제목을 입력해 주세요.');
        $('#viewTitle').focus();
        return false;
    }else if($('#viewId').val().length < 1) {
        alert('작성자를 입력해 주세요.');
        $('#viewId').focus();
        return false;
    }else if($('#viewPassword').val().length < 1) {
        alert('비밀번호를 입력해 주세요.');
        $('#viewPassword').focus();
        return false;
    }else true;
}

//댓글 작성하기 비어있느 input 체크
function emptyCommentCheck() {
    if($('#commentId').val().length < 1) {
        alert('작성자를 입력해 주세요.');
        $('#commentId').focus();
        return false;
    }else if($('#commentPassword').val().length < 1) {
        alert('비밀번호를 입력해 주세요.');
        $('#commentPassword').focus();
        return false;
    }else if($('#commentContent').val().length < 1) {
        alert('내용을 입력해 주세요.');
        $('#commentContent').focus();
        return false;
    }else true;
}

//글쓰기 페이지 파일 체크
function fileSelect(e) {
    var enrollFile = [];
    var files = e.target.files;
    var filesArr = Array.prototype.slice.call(files);
    $("#imgBox").html("");
    filesArr.forEach(function(f) {
        if(!(f.type.match("image/*" ) || f.type.match("text/*" ))) {
            alert("잘못된 파일입니다.");
            $('#imgPreview').val("");
            return;
        }

        if(f.size > 5000000){
            alert("파일의 용량 초과 입니다.");
            $("#imgPreview").val("");
            return;
        };

        enrollFile.push(f);

        var reader = new FileReader();
        reader.onload = function(e) {
            if(!(f.type.match("text/*"))) {
                var imgHtml = "<img src=\"" + e.target.result + "\" />";
                $("#imgBox").append(imgHtml);
                $("#imgBox img").addClass("imgSize");
            }
        }
        reader.readAsDataURL(f);
    });
}

//뷰페이지 파일 체크
function fileListSel(e) {
    var enrollFile = [];
    var files = e.target.files;
    var filesArr = Array.prototype.slice.call(files);
    $("#imgBox").html("");
    filesArr.forEach(function(f) {
        if(!(f.type.match("image/*" ) || f.type.match("text/*" ))) {
            alert("잘못된 파일입니다.");
            $('#viewImgPreview').val("");
            return;
        }

        if(f.size > 5000000){
            alert("파일의 용량 초과 입니다.");
            $("#viewImgPreview").val("");
            return;
        };

        enrollFile.push(f);

        var reader = new FileReader();
        reader.onload = function(e) {
            if(!(f.type.match("text/*"))) {
                var imgHtml = "<img src=\"" + e.target.result + "\" />";
                $("#imgBox").append(imgHtml);
                $("#imgBox img").addClass("imgSize");
            }else {
                var textHtml = "<img src=\"uploads/textIcon.png\" />";
                $("#imgBox").append(textHtml);
            }
        }
        reader.readAsDataURL(f);
    });
}