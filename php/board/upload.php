<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//     if($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg"
&& $FileType != "gif" && $FileType != "pdf" && $FileType != "txt" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF & pdf files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        header("location:/board.php");
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
/*
    $pageNo = $pageNo * 5;
    $query =<<<SQL
    declare @a int=?;
    declare @a int, @str varchar(10)
    set @a = :a
    set @str = :str
    @str = '%' + @str
SELECT num, title, hits, createDate, userId, writeContent FROM dbo.TB_board WHERE title LIKE @a ORDER BY num DESC OFFSET @a ROWS FETCH NEXT 5 ROWS ONLY;
SQL;
    $stmt = $conn->query($query);
    */
    
?>


<!-- <form method="post" action="upload.php" enctype="multipart/form-data">
<input type="file" size=100 name="upload"><hr>
<input type="submit" value="send">
</form> -->
<!-- strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); -->

<?php
/*
 echo "confirm file information <br />";
 $uploads_dir = 'uploads';
 $uploadfile = $_FILES['upload'] ['name'];
//  $inin = file_get_contents($uploadfile);
 if(move_uploaded_file($_FILES['upload']['tmp_name'],"$uploads_dir/$uploadfile")){
    chmod("$uploads_dir/$uploadfile", 755);
  echo "파일이 업로드 되었습니다.<br />";
  echo "<img src ={$_FILES['upload']['name']}> <p>";
  echo $aa = pathinfo(basename($_FILES['upload']['name']),PATHINFO_EXTENSION)."<br />";
  echo "1. file name : {$_FILES['upload']['name']}<br />";
  echo "2. file type : {$_FILES['upload']['type']}<br />";
  echo "3. file size : {$_FILES['upload']['size']} byte <br />";
  echo "4. temporary file name : {$_FILES['upload']['size']}<br />";
//   echo fopen("C:\usr\local\www\phpsample.ybmnet.co.kr/$uploads_dir/$uploadfile", "r");
 } else {
  echo "파일 업로드 실패 !! 다시 시도해주세요.<br />";
 }
 */
?>

