<?php
require_once(__DIR__ . "/../common.php");
// セッションの開始
MySessionStart();
// Requestを確認
checkRequest();
// PDOオブジェクトの設定
$pdo = new MyPDO();

$sanitize = sanitize($_POST);

// ログイン認証
$pdo->userLogin($sanitize);