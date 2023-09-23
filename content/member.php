<?php
require_once(__DIR__ . "/common.php");
MySessionStart();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンバー</title>
</head>
<body>
    <!-- ログインしているかどうかチェックする -->
    <?php if(checkLogin()): ?>
        <h1>メンバー</h1>
        <h2>このページはログインしないと見れません。</h2>
        <h3>これが見れるという事はログインしているという事です。</h3>
    <?php else: ?>
        <p>ログインしてください</p>
        <a href="http://localhost:3000/login">ログイン</a>
    <?php endif; ?>
</body>
</html>