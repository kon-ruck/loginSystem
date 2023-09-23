<?php
require_once(__DIR__  . "/config/config.php");

class MyPDO {
    // PDOインスタンス
    public $db;

    // コンストラクタ
    public function __construct(){
        // PDOインスタンスを設定する
        $host = HOST;
        $db_name = DB;
        $user = USER;
        $password = PASSWORD;
        $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8;";
        $this->db = new PDO(
            $dsn,
            $user,
            $password,
            array(
                PDO::ATTR_EMULATE_PREPARES => false,
            ),
        );
    }

    // ユーザーログイン時のチェック
    public function userLogin($user){
        try {
            // メールアドレスでユーザーを検索する
            $stmt = $this->db->prepare('select * from user where email = ?');
            $stmt->bindValue(1, $user['email']);
            $stmt->execute();
            $us = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit(1);
        }
        // ログイン処理
        if($us && password_verify($user['password'], $us['password'])){
            $_SESSION['login'] = $us['id'];
            // ログインが正常にできればトップにリダイレクト
            header(RE_DIRECT_PATH);
            exit(0);
        }else{
            // 間違っていた場合ログインフォームにリダイレクト
            $_SESSION['flash']['error'] = VALI_LOGIN;
            header(LOGIN_RE_DIRECT_PATH);
            exit(0);
        }
    }

    // ユーザーの登録処理
    public function userCreate($user){
        // バリデーションチェック
        validate($this->db, $user);
        try {
            // パスワードをハッシュ化
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
            // 登録処理
            $stmt = $this->db->prepare("insert into user(name, email, password) values(?, ?, ?)");
            $stmt->bindValue(1, $user['name']);
            $stmt->bindValue(2, $user['email']);
            $stmt->bindValue(3, $user['password']);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit(1);
        }
        // ログイン成功ならトップにリダイレクト
        header(RE_DIRECT_PATH);
        exit(0);
    }
}

// サニタイズ処理
function sanitize($post){
    $sanitize = array();
    foreach ($post as $key => $value) {
        $sanitize[$key] = htmlspecialchars($value, ENT_QUOTES);
    }
    return $sanitize;
}

// セッションの開始
function MySessionStart(){
    session_start([
        'cookie_lifetime' => 60*60*24*7,
    ]);
}

// バリデーション関数
function validate($pdo, $user){
    $vali_f = false;
    check_name($vali_f, $user);
    check_email($vali_f, $user, $pdo);
    check_password($vali_f, $user);
    // バリデーションに引っかかれば登録ページに戻してプログラムを停止
    if($vali_f){
        header(SIGNUP_RE_DIRECT_PATH);
        exit(0);
    }
}

// 名前のバリデーション
function check_name(&$f, $u){
    $_SESSION['before']['name'] = $u['name'];
    // 空白チェック
    if(empty($u['name'])){
        $_SESSION['flash']['name'] = VALI_EMPTY;
        $f = true;
        return;
    }
    // 形式チェック
    if(preg_match('/^(,|\.|\s|\^|\$|\\|[1-9])+$/', $u['name'])){
        $_SESSION['flash']['name'] = VALI_FORMAT;
        $f = true;
        return;
    }
}

// メールのバリデーション
function check_email(&$f, $u, $db){
    $_SESSION['before']['email'] = $u['email'];
    // 空白チェック
    if(empty($u['email'])){
        $_SESSION['flash']['email'] = VALI_EMPTY;
        $f = true;
        return;
    }
    // 形式チェック
    if(preg_match('/^[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/', $u['name'])){
        $_SESSION['flash']['email'] = VALI_FORMAT;
        $f = true;
        return;
    }
    // 重複チェック
    try {
        $stmt = $db->prepare("select * from user where email = ?");
        $stmt->bindValue(1, $u['email']);
        $stmt->execute();
        $u = $stmt->fetch();
        if($u){
            $_SESSION['flash']['error'] = VALI_ERROR;
            $f = true;
            return;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit(1);
    }
}

// パスワードのバリデーション
function check_password(&$f, $u){
    // 空白チェック
    if(empty($u['password'])){
        $_SESSION['flash']['password'] = VALI_EMPTY;
        $f = true;
        return;
    }
}

// 不当なアクセスを制限する処理
function checkRequest(){
    // POSTリクエスト かつ POSTにtokenの値がある かつ セッションにtokenの値がある かつ POSTのセッションのtokenの値が一致している
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']){
        // 問題がなければtokenを削除するだけ
        unset($_SESSION['token']);
    }else{
        // 問題があればトップにリダイレクト
        unset($_SESSION['token']);
        header(RE_DIRECT_PATH);
        exit(1);
    }
}

// ログインしているか確認する関数
function checkLogin(){
    return isset($_SESSION['login']);
}

// $user = $pdo->query("select * from user where email = '{$email}'")->fetch();
// $pdo->query("insert into user(name, email, password) values('{$name}', '{$email}', '{$password}')");