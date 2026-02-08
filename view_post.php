<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.');location.href='/login.php';</script>";
    exit;
}

$userid = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT * FROM posts WHERE userid = ? ORDER BY created_at DESC");
if (!$stmt) {
    die("query prepare error => ".$mysqli->error);
}
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    echo "<script>alert('등록된 글이 없습니다.');history.back();</script>";
    exit;
}
?>

<?php while ($rs = $result->fetch_object()): ?> 
    <!-- 각각의 id를 하나씩 가져와서 title, userid 와 같은 것을 들고옴 -->
<article class="blog-post">
    <h2 class="blog-post-title"><?php echo htmlspecialchars($rs->title);?></h2>
    <p class="blog-post-meta"><?php echo $rs->created_at;?> by <a href="#"><?php echo htmlspecialchars($rs->userid);?></a></p>

    <hr>
    <p>
        <?php echo $rs->content;?>
    </p>
    <?php if(!empty($rs->filename)): ?>
    <p>
        <strong>첨부파일:</strong> 
        <a href="/download.php?file=<?php echo urlencode($rs->filename); ?>"><?php echo htmlspecialchars($rs->filename); ?></a>
    </p>
    <?php endif; ?>
    <hr>
    
    <nav class="blog-pagination" aria-label="Pagination">
        <a class="btn btn-outline-secondary" href="/index.php">목록</a>
        <a class="btn btn-outline-secondary" href="/reply.php?id=<?php echo $rs->id;?>">답글</a>
        <a class="btn btn-outline-secondary" href="/post.php?id=<?php echo $rs->id;?>">수정</a>
        <a class="btn btn-outline-secondary" href="/delete.php?id=<?php echo $rs->id;?>">삭제</a>
    </nav>
</article>
<?php endwhile; ?>
<?php $stmt->close(); ?>
<!-- 수정하는 곳을 등록하는 곳과 같은 곳에서 한 후 ok해주는 파트도 동일한 곳에서 나타나게 한다. -->
