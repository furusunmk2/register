<?php
session_start();

// 設定ファイル「DB.php」読み込み
require_once 'lib/DB.php';

const DB_CONNECTION = 'mysql';
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = 'auth';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
    exit;
}

$errors = [];

// POSTデータ取得
$id = $_POST['ID'] ?? '';
$password = $_POST['password'] ?? '';

// フォームバリデーション
if (empty($id)) {
    $errors['ID'] = 'IDを入力してください。';
}
if (empty($password)) {
    $errors['password'] = 'パスワードを入力してください。';
}

if (empty($errors)) {
    // IDの重複チェック
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        $errors['ID'] = 'このIDは既に登録されています。';
    } else {
        // パスワードのハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ユーザーをデータベースに挿入
        $stmt = $pdo->prepare("INSERT INTO users (id, password) VALUES (?, ?)");
        $stmt->execute([$id, $hashed_password]);

        // 登録成功、ログインページにリダイレクト
        header('Location: login.php');
        exit;
    }
}

$_SESSION['errors'] = $errors;
header('Location: register.php');
exit;