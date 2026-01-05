<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>新規Todo作成</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <h1>新規Todo作成</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/todo/create">
        <div class="form-group">
            <label for="title">タイトル</label>
            <input type="text" name="title" id="title" class="form-control" required maxlength="100">
        </div>
        <button type="submit" class="btn btn-primary">作成</button>
        <a href="/todo" class="btn btn-default">戻る</a>
    </form>
</div>
</body>
</html>
