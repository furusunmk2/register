<?php
// 設定ファイル「env.php」読み込み（コメントアウトまたは削除）
// require_once '../env.php';
require_once 'lib/DB.php';

// セッション開始
session_start();
session_regenerate_id(true);

const DB_CONNECTION = 'mysql';
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = 'auth';

$db_connection = DB_CONNECTION;
$db_name = DB_DATABASE;
$db_host = DB_HOST;
$db_port = DB_PORT;

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
    exit;
}

// POSTデータ取得
$posts = $_POST;

// id のデータ
$id = $posts['ID'];
// password のデータ
$password = $posts['password'];

// id検索(SQL)
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

// データ変換
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$is_success = false;
if ($user) {
    $hash = $user['password'];
    // パスワードハッシュ検証
    $is_success = password_verify($password, $hash);
}

if ($is_success) {
    // セッションにユーザを登録
    $_SESSION['my_shop']['user'] = $user;

    // ログイン成功の場合、database.php にリダイレクト
    header('Location: database.php');
    exit;
} else {
    // ログイン失敗の場合、login.php にリダイレクト
    $_SESSION['errors']['login'] = 'IDまたはパスワードが間違っています';
    header('Location: login.php');
    exit;
}