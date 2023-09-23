<?php
require_once(__DIR__ . "/../common.php");
MySessionStart();
// バリデーションメッセージを取得
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
unset($_SESSION['flash']);

// トークンの値を設定
$_SESSION['token'] = uniqid('', true);
$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
<?php if(!isset($_SESSION['login'])): ?>
    <h1>ログイン</h1>
    <form action="login.php" method="POST">
        <label for="email">メールアドレス</label><br>
        <input type="email" name="email"><br>

        <label for="password">パスワード</label><br>
        <input type="password" name="password"><br>
        <br>
        <input type="submit" value="送信">
        <?php echo isset($flash['error']) ? '<br><p>' . $flash['error'] . '</p><br>' : ''; ?>

        <!-- トークン -->
        <input type="hidden" name="token" value="<?php echo $token; ?>">
    </form>
<?php else: ?>
    <h1>ログインしています</h1>
    <a href="../logout.php">ログアウト</a><br>
<?php endif; ?>
</body>
</html>