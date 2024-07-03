<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : array();
$member = isset($_SESSION['member']) ? $_SESSION['member'] : array();
session_unset(); // セッションをクリア
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <main>
        <h2>ログイン</h2>
        <form action="auth.php" method="post">
            <div class="form-group">
                <input class="form-control" type="text" name="ID" placeholder=" " value="<?= htmlspecialchars(@$member['ID'], ENT_QUOTES, 'UTF-8') ?>">
                <label class="form-label">ID</label>
                <p class="text-danger"><?= htmlspecialchars(@$errors['ID'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder=" " value="<?= htmlspecialchars(@$member['password'], ENT_QUOTES, 'UTF-8') ?>">
                <label class="form-label">パスワード</label>
                <p class="text-danger"><?= htmlspecialchars(@$errors['password'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="form-group">
                <button class="btn" type="submit">ログイン</button>
            </div>
            <?php if (!empty($errors['login'])): ?>
                <p class="text-danger text-center"><?= htmlspecialchars($errors['login'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </form>
        <div class="form-group">
            <button class="btn" onclick="location.href='calculator.php'">電卓に戻る</button>
        </div>
    </main>
</body>
</html>