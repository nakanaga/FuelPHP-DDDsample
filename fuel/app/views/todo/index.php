<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Todo List</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <h1>Todo List</h1>

    <?php if (Session::get_flash('success')): ?>
        <div class="alert alert-success"><?= Session::get_flash('success') ?></div>
    <?php endif; ?>

    <?php if (Session::get_flash('error')): ?>
        <div class="alert alert-danger"><?= Session::get_flash('error') ?></div>
    <?php endif; ?>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <p>未完了: <strong><?= $pending_count ?></strong>件</p>
            <p>完了率: <strong><?= number_format($completion_rate * 100, 1) ?>%</strong></p>
        </div>
        <div class="col-md-6 text-right">
            <a href="/todo/create" class="btn btn-primary">新規作成</a>
            <a href="/todo/complete_all" class="btn btn-success">全て完了</a>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>ステータス</th>
                <th>作成日時</th>
                <th>完了日時</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($todos)): ?>
                <tr><td colspan="6" class="text-center">Todoがありません</td></tr>
            <?php else: ?>
                <?php foreach ($todos as $todo): ?>
                    <tr>
                        <td><?= $todo['id'] ?></td>
                        <td><?= htmlspecialchars($todo['title']) ?></td>
                        <td>
                            <?php if ($todo['status'] === 'completed'): ?>
                                <span class="label label-success">完了</span>
                            <?php else: ?>
                                <span class="label label-warning">未完了</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $todo['created_at'] ?></td>
                        <td><?= $todo['completed_at'] ?? '-' ?></td>
                        <td>
                            <?php if ($todo['status'] === 'pending'): ?>
                                <a href="/todo/complete/<?= $todo['id'] ?>" class="btn btn-sm btn-success">完了</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
