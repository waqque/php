<?php
$delimiter = '||';
$dataFile = 'applications.dat';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $selectedApps = $_POST['selected_apps'] ?? [];
    $deletedCount = 0;
    
    if (!empty($selectedApps) && file_exists($dataFile)) {
        $lines = file($dataFile);
        $idsToDelete = $selectedApps;
        
        for ($i = 1; $i < count($lines); $i++) {
            $data = explode($delimiter, $lines[$i]);
            if (!empty($data[0]) && in_array($data[0], $idsToDelete)) {
                if (count($data) >= 10) {
                    $data[9] = 'deleted';
                    $lines[$i] = implode($delimiter, $data) . PHP_EOL;
                    $deletedCount++;
                }
            }
        }
        
        file_put_contents($dataFile, implode('', $lines), LOCK_EX);
    }
    
    $message = "Удалено заявок: " . $deletedCount;
}

$applications = [];

if (file_exists($dataFile)) {
    $lines = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    for ($i = 1; $i < count($lines); $i++) {
        $data = explode($delimiter, $lines[$i]);
        if (count($data) >= 10) {
            $status = $data[9] ?? 'active';
            if ($status !== 'deleted') {
                $applications[] = [
                    'id' => $i,
                    'datetime' => $data[0],
                    'ip' => $data[1],
                    'name' => $data[2],
                    'surname' => $data[3],
                    'email' => $data[4],
                    'phone' => $data[5],
                    'topic' => $data[6],
                    'payment' => $data[7],
                    'newsletter' => $data[8]
                ];
            }
        }
    }
    
    rsort($applications);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель - Заявки</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px auto; padding: 20px; max-width: 1200px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        tr:hover { background: #f9f9f9; }
        .delete-btn { background: #dc3545; color: white; border: none; padding: 10px 20px; cursor: pointer; margin-top: 20px; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-left: 3px solid #28a745; }
        .checkbox-col { width: 30px; text-align: center; }
        .no-data { text-align: center; padding: 40px; color: #666; }
        .button-group { margin-bottom: 15px; }
        .button-group button { margin-right: 10px; padding: 5px 15px; cursor: pointer; }
        .back-link { display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Управление заявками на конференцию</h1>
    <a href="index.php" class="back-link"><- Вернуться к форме</a>
    
    <?php if (isset($message)): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if (empty($applications)): ?>
        <div class="no-data">
            Нет ни одной заявки.<br>
            <a href="index.php">Заполните форму</a> чтобы создать первую заявку.
        </div>
    <?php else: ?>
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
                        <th>IP</th>
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
                            <input type="checkbox" name="selected_apps[]" value="<?php echo $app['id']; ?>" class="app-checkbox">
                        </td>
                        <td><?php echo htmlspecialchars($app['datetime']); ?></td>
                        <td><?php echo htmlspecialchars($app['ip']); ?></td>
                        <td><?php echo htmlspecialchars($app['name']); ?></td>
                        <td><?php echo htmlspecialchars($app['surname']); ?></td>
                        <td><?php echo htmlspecialchars($app['email']); ?></td>
                        <td><?php echo htmlspecialchars($app['phone']); ?></td>
                        <td><?php echo htmlspecialchars($app['topic']); ?></td>
                        <td><?php echo htmlspecialchars($app['payment']); ?></td>
                        <td><?php echo $app['newsletter']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <button type="submit" name="delete" class="delete-btn" onclick="return confirmDelete()">
                Удалить выбранные заявки
            </button>
        </form>
    <?php endif; ?>
    
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