<?php session_start();
include $_SERVER["DOCUMENT_ROOT"]."/inc/dbcon.php";

// id 파라미터 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('잘못된 접근입니다.');location.href='/recent_blog.php';</script>";
    exit;
}

$postId = (int)$_GET['id'];

// 해당 글 조회
$stmt = $mysqli->prepare("SELECT * FROM posts WHERE id = ?");
if (!$stmt) {
    die("query prepare error => ".$mysqli->error);
}
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo "<script>alert('존재하지 않는 글입니다.');location.href='/recent_blog.php';</script>";
    exit;
}

$rs = $result->fetch_object();
$stmt->close();

// 현재 로그인 사용자가 작성자인지 확인
$isAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $rs->userid;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($rs->title); ?> - TechBlog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container container-post">
        <article class="blog-post">
            <h2 class="blog-post-title"><?php echo htmlspecialchars($rs->title); ?></h2>
            <p class="blog-post-meta">
                <?php echo $rs->created_at; ?> by <a href="#"><?php echo htmlspecialchars($rs->userid); ?></a>
            </p>

            <hr>
            <div class="blog-post-content">
                <?php echo nl2br(htmlspecialchars($rs->content)); ?>
            </div>
            
            <?php if(!empty($rs->filename)): ?>
            <p> 
                <strong>첨부파일:</strong>
                <a href="/download.php?file=<?php echo urlencode($rs->filenamt); ?>"> <?php echo htmlspecialchars($rs->filename);?></a>
            </p>
            <?php endif;?>

            <hr>

            <nav class="blog-pagination" aria-label="Pagination">
                <a class="btn btn-outline-secondary" href="/recent_blog.php">목록</a>
                <?php if ($isAuthor): ?>
                <a class="btn btn-outline-secondary" href="/reply.php?id=<?php echo $rs->id; ?>">답글</a>
                <a class="btn btn-outline-secondary" href="/post.php?id=<?php echo $rs->id; ?>">수정</a>
                <a class="btn btn-outline-secondary" href="/delete.php?id=<?php echo $rs->id; ?>">삭제</a>
                <?php endif; ?>
            </nav>
        </article>
    </div>
</body>
</html>
