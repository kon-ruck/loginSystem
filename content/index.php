<?php
session_start([
    'cookie_lifetime' => 60*60*24*7,
]);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録</title>
</head>
<body>
    <h1>ログインシステム</h1>
    <?php if(!isset($_SESSION['login'])): ?>
        <a href="/signup">新規登録</a><br>
        <a href="/login">ログイン</a><br>
        <a href="/member.php">ログインしないとみられないページ</a><br>
    <?php else: ?>
        <h2>ログインしています。</h2>
        <a href="/logout.php">ログアウト</a><br>
        <a href="/member.php">ログインしないとみられないページ</a><br>
    <?php endif; ?>
</body>
</html>