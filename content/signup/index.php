<?php
require_once(__DIR__ . "/../common.php");
MySessionStart();
// バリデーションメッセージを取得
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
unset($_SESSION['flash']);

// リダイレクト時 入力していた値を取得
$before = isset($_SESSION['before']) ? $_SESSION['before'] : [];
unset($_SESSION['before']);

// トークンの値を設定
$_SESSION['token'] = uniqid('', true);
$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録</title>
</head>
<body>
    <?php if(!isset($_SESSION['login'])): ?>
    <h1>新規登録</h1>
    <form action="create.php" method="POST">
        <label for="name">名前</label><br>
        <input type="text" name="name" id="name" value="<?php echo isset($before['name']) ? $before['name'] : ''; ?>"><br>
        <?php echo isset($flash['name']) ? '<p>' . $flash['name'] . '</p><br>' : ''; ?>

        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" value="<?php echo isset($before['email']) ? $before['email'] : ''; ?>"><br>
        <?php echo isset($flash['email']) ? '<p>' . $flash['email'] . '</p><br>' : ''; ?>

        <label for="password">パスワード</label><br>
        <input type="password"  name="password" id="password"><br>
        <?php echo isset($flash['password']) ? '<p>' . $flash['password'] . '</p><br>' : ''; ?>
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