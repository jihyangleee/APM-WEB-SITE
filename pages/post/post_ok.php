<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";
if(!isset($_SESSION['user_id'])){
  echo"<script>alert('회원 전용 게시판입니다.');location.href='/index.php';</script>";
  exit;
}
$userid=$_SESSION['user_id'];
$title=isset($_POST['title'])? trim($_POST['title']) :'';
$content=isset($_POST['content'])? trim($_POST['content']):'';
$id=isset($_POST['id'])?(int)$_POST['id'] : 0;

// 파일 업로드 처리 + db에 filename 항목 추가 
$uploadedFileName = '';
if(isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK){
    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
    
    // 업로드 디렉토리가 없으면 생성
    if(!is_dir($uploaddir)){
        mkdir($uploaddir, 0755, true);
    }
    

    // 파일명 중복 방지를 위해 고유 이름 생성
    $originalName = basename($_FILES['userfile']['name']);
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $uniqueName = time() . '_' . uniqid() . '.' . $ext;
    $uploadfile = $uploaddir . $uniqueName;

    
    // 허용 확장자 검사
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'zip'];
    if(!in_array(strtolower($ext), $allowedExt)){
        echo "<script>alert('허용되지 않는 파일 형식입니다.');history.back();</script>";
        exit;
    }

    
    // 파일 크기 제한 (5MB)
    if($_FILES['userfile']['size'] > 5 * 1024 * 1024){
        echo "<script>alert('파일 크기는 5MB를 초과할 수 없습니다.');history.back();</script>";
        exit;
    }
    
    
    //tmp 폴더에 저장
    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
        $uploadedFileName = $uniqueName;
    } else {
        // 디버깅: 실패 원인 확인
        $error_msg = '업로드 실패 - ';
        $error_msg .= '대상경로: ' . $uploadfile . ' | ';
        $error_msg .= '임시파일: ' . $_FILES['userfile']['tmp_name'] . ' | ';
        $error_msg .= '디렉토리존재: ' . (is_dir($uploaddir) ? 'Y' : 'N') . ' | ';
        $error_msg .= '쓰기권한: ' . (is_writable($uploaddir) ? 'Y' : 'N');
        echo "<script>alert('" . addslashes($error_msg) . "');history.back();</script>";
        exit;
    }
    

}

//위의 post에서 받은 내용들을 변수에 넣고 그것을 db에 다가 insert하거나 update할 것이기 때문
if($title ==='' || $content===''){
  echo"<script>alert('제목과 내용을 입력하세요.');history.back();</script>";
  exit;
}
// id가 양수인 경우 이미 포스트가 존재 -> 수정상황
if($id > 0){
  // 파일이 새로 업로드된 경우에만 filename 업데이트
  if($uploadedFileName !== ''){
    $stmt = $mysqli->prepare("UPDATE posts SET title=?, content=?, filename=? WHERE id=? AND userid=?");
    if(!$stmt){die($mysqli->error);}
    $stmt->bind_param("sssis", $title, $content, $uploadedFileName, $id, $userid);
  } else {
    $stmt = $mysqli->prepare("UPDATE posts SET title=?, content=? WHERE id=? AND userid=?");
    if(!$stmt){die($mysqli->error);}
    $stmt->bind_param("ssis", $title, $content, $id, $userid);
  }
  $ok = $stmt->execute();
  $affected = $stmt->affected_rows;
  $stmt->close();
  if($ok || $affected >0 ){
    echo "<script>location.href='/pages/post/view_post.php?id=".$id."';</script>";
    exit;
  }else{
    echo"<script>alert('수정권한이 없거나 변경사항이 없다');history.back();</script>";
    exit;
    }
} else{
  $stmt= $mysqli->prepare("INSERT INTO posts (userid, title, content, filename) VALUES(?,?,?,?)");
  if(!$stmt){die($mysqli->error);}
  $stmt->bind_param("ssss", $userid, $title, $content, $uploadedFileName);
  $ok= $stmt->execute();
  $newId= $stmt->insert_id;
  $stmt->close();
  if($ok>0){
    echo"<script>location.href='/pages/post/view_post.php?id=".$newId."';</script>";
    exit;
  }
  else{
    echo"<script>alert('글등록에 실패하였습니다.');history.back();</script>";
    exit; 
  }
}
?>