<?php
require('numCollection.php');
require('outlet.php');
$conn = getDB(serverName,serverId,serverPassword);

$no = $_GET['num'];
$fileRow = selectFileInfo($conn,$no);

$filename = $fileRow['realFileName'];
$reail_filename = urldecode($fileRow['upFileName']);
$file_dir = "uploads/".$filename;

header('Content-Type: application/octetstream');
header('Content-Length: '.filesize($file_dir));
header('Content-Disposition: attachment; filename='.$reail_filename);
header('Content-Transfer-Encoding: binary');
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Expires: 0");

$fp = fopen($file_dir, "r");//파일 포인터 열기
fpassthru($fp);//파일 컨텐츠를 불러오면 바로 출력하여 전송
fclose($fp);//파일 포인터 닫기

?>
