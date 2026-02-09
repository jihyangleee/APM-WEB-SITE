<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

$id = isset($_GET["id"]) ? $_GET["id"] : null;
$title = '';
$content = '';

if($id){
    $result = $mysqli->query("select * from posts where id=".$id) or die("query error => ".$mysqli->error);
    $rs = $result->fetch_object();
    if(!$rs || $rs->userid != $_SESSION['user_id']){
        echo"<script>alert('자신의 글이 아니면 편집할 수 없습니다.');history.back();</script>";
        exit;
    }
    if($rs){
        $title = $rs->title;
        $content= $rs->content;
    }
}


?>
<form method="post" action="/pages/post/post_ok.php" enctype="multipart/form-data">
    en
    <input type="hidden" name="id" value ="<?php echo htmlspecialchars($id);?>">
    <div class="mb-3">
        <label for="inputarea1" class="form-label">제목</label>
        <input type="text" name="title" class="form-control" id="inputarea1" placeholder="제목을 입력하시오" value="<?php echo htmlspecialchars($title); ?>">

    </div>
    <div class="mb-3">
        <label for="textarea1" class="form-label">내용</label>
        <textarea class="form-control" id="textarea1" name="content" rows="3"><?php echo htmlspecialchars($content);?></textarea>
    </div>
    <!-- 파일 업로드 -->
    <div class="mb-3">
        <label for="userfile" class="form-label">파일 업로드</label>
        <input type="file" name="userfile" class="form-control" id="userfile">
    </div>
<button type="submit" class="btn btn-primary">등록</button>
<span>
</form>
