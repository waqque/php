<?php
include_once 'functions.php';

$message = '';
$deletedCount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $selectedApps = $_POST['selected_apps'] ?? [];
    $deletedCount = deleteApplications($selectedApps);
    
    if ($deletedCount > 0) {
        $message = "Удалено заявок: " . $deletedCount;
    }
}

$applications = getAllApplications();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель - Заявки на конференцию</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1300px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        tr:hover { background: #f9f9f9; }
        .delete-btn { background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .delete-btn:hover { background: #c82333; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px; border-left: 3px solid #28a745; }
        .checkbox-col { width: 30px; text-align: center; }
        .no-data { text-align: center; padding: 40px; color: #666; }
        .button-group { margin-bottom: 15px; }
        .button-group button { margin-right: 10px; padding: 5px 15px; cursor: pointer; border-radius: 4px; border: 1px solid #ddd; background: #f0f0f0; }
        .button-group button:hover { background: #e0e0e0; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .stats { background: #e7f3ff; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Управление заявками на конференцию</h1>
        <a href="index.php" class="back-link"><- Вернуться к форме подачи заявки</a>
        
        <?php if ($message): ?>
            <div class="success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if (empty($applications)): ?>
            <div class="no-data">
                Нет ни одной заявки.<br>
                <a href="index.php">Заполните форму</a> чтобы создать первую заявку.
            </div>
        <?php else: ?>
            <div class="stats">
                Всего заявок: <strong><?= count($applications) ?></strong>
            </div>
            
            <form method="POST" action="">
                <div class="button-group">
                    <button type="button" onclick="selectAll(true)">Выбрать все</button>
                    <button type="button" onclick="selectAll(false)">Снять все</button>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th class="checkbox-col"><input type="checkbox" id="selectAllCheckbox" onclick="toggleAll(this)"></th>
                            <th>Дата и время</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Тематика</th>
                            <th>Метод оплаты</th>
                            <th>Рассылка</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                        <tr>
                            <td class="checkbox-col">
                                <input type="checkbox" name="selected_apps[]" value="<?= htmlspecialchars($app['filename']) ?>" class="app-checkbox">
                            </td>
                            <td><?= htmlspecialchars($app['datetime']) ?></td>
                            <td><?= htmlspecialchars($app['name']) ?></td>
                            <td><?= htmlspecialchars($app['surname']) ?></td>
                            <td><?= htmlspecialchars($app['email']) ?></td>
                            <td><?= htmlspecialchars($app['phone']) ?></td>
                            <td><?= htmlspecialchars($app['topic']) ?></td>
                            <td><?= htmlspecialchars($app['payment']) ?></td>
                            <td><?= $app['newsletter'] == 'Да' ? 'Да' : 'Нет' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <button type="submit" name="delete" class="delete-btn" onclick="return confirmDelete()">
                    Удалить выбранные заявки
                </button>
            </form>
        <?php endif; ?>
    </div>
    
    <script>
        function selectAll(select) {
            var checkboxes = document.querySelectorAll('.app-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = select;
            }
            var masterCheckbox = document.getElementById('selectAllCheckbox');
            if (masterCheckbox) masterCheckbox.checked = select;
        }
        
        function toggleAll(source) {
            var checkboxes = document.querySelectorAll('.app-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
        
        function confirmDelete() {
            var checkboxes = document.querySelectorAll('.app-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Пожалуйста, выберите хотя бы одну заявку для удаления.');
                return false;
            }
            return confirm('Вы уверены, что хотите удалить выбранные заявки (' + checkboxes.length + ' шт.)?');
        }
    </script>
</body>
</html>