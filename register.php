<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
session_unset();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <main>
        <h2>ユーザー登録</h2>
        <form action="register_process.php" method="post">
            <div class="form-group">
                <input class="form-control" type="text" name="ID" placeholder=" " value="<?= htmlspecialchars(@$_POST['ID'], ENT_QUOTES, 'UTF-8') ?>">
                <label class="form-label">ID</label>
                <p class="text-danger"><?= htmlspecialchars(@$errors['ID'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder=" " value="<?= htmlspecialchars(@$_POST['password'], ENT_QUOTES, 'UTF-8') ?>">
                <label class="form-label">パスワード</label>
                <p class="text-danger"><?= htmlspecialchars(@$errors['password'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="text-center mt-3">
                <button class="btn" type="submit">登録</button>
            </div>
            <?php if (!empty($errors['register'])): ?>
                <p class="text-danger text-center"><?= htmlspecialchars($errors['register'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </form>
    </main>
</body>
</html>