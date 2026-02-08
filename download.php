<?php session_start();

// GET 파라미터로 파일명 받기
$filename = isset($_GET['file']) ? basename($_GET['file']) : '';
$filepath = $_SERVER['DOCUMENT_ROOT'] . '/tmp/' . $filename;

// 파일 존재 확인
if($filename === '' || !file_exists($filepath)){
    echo "<script>alert('파일이 존재하지 않습니다.');history.back();</script>";
    exit;
}

// 다운로드 헤더 설정
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));

// 파일 출력
readfile($filepath);
exit;
?>
