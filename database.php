<?php
session_start();

// ユーザーがログインしていない場合は、login.phpにリダイレクト
if (!isset($_SESSION['my_shop']['user'])) {
    header('Location: login.php');
    exit;
}

const DB_CONNECTION = 'mysql';
const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = '売上';

$dsn = DB_CONNECTION . ":dbname=" . DB_DATABASE . ";host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8";
$db_user = DB_USERNAME;
$db_password = DB_PASSWORD;

try {
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
    exit;
}

$sql = "SELECT `計上日時` AS posting_date, `計上額` AS amount FROM `売上`";
$result = $pdo->query($sql);

if (!empty($result)) {
    echo "<table border='1'>";
    echo "<tr><th>計上日時</th><th>計上額</th></tr>";

    // データを行ごとに出力
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["posting_date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["amount"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_all'])) {
    // テーブルのデータを全削除するSQL
    $sql = "DELETE FROM `売上`";
    $result = $pdo->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="database.css">
    <title>売上データベース</title>
</head>
<body>
    <button style="width: 200px" onclick="location.href='calculator.php'">電卓に戻る</button>
    <form method="post">
        <input style="width: 200px" type="submit" name="delete_all" value="全削除(要更新)">
    </form>
</body>
</html>