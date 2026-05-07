<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $selectedApps = $_POST['selected_apps'] ?? array();
    $deletedCount = 0;
    
    foreach ($selectedApps as $appId) {
        $filePath = 'applications/' . basename($appId);
        if (file_exists($filePath) && is_file($filePath)) {
            if (unlink($filePath)) {
                $deletedCount++;
            }
        }
    }
    
    $message = "Удалено заявок: " . $deletedCount;
}

$applications = array();
$directory = 'applications';

if (is_dir($directory)) {
    $files = glob($directory . '/*.txt');
    
    if ($files) {
        rsort($files);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);

            $data = array();
            $lines = explode("\n", $content);
            
            foreach ($lines as $line) {
                if (strpos($line, "Дата и время:") === 0) {
                    $data['datetime'] = trim(str_replace("Дата и время:", "", $line));
                } elseif (strpos($line, "Имя:") === 0) {
                    $data['name'] = trim(str_replace("Имя:", "", $line));
                } elseif (strpos($line, "Фамилия:") === 0) {
                    $data['surname'] = trim(str_replace("Фамилия:", "", $line));
                } elseif (strpos($line, "Email:") === 0) {
                    $data['email'] = trim(str_replace("Email:", "", $line));
                } elseif (strpos($line, "Телефон:") === 0) {
                    $data['phone'] = trim(str_replace("Телефон:", "", $line));
                } elseif (strpos($line, "Тематика:") === 0) {
                    $data['topic'] = trim(str_replace("Тематика:", "", $line));
                } elseif (strpos($line, "Метод оплаты:") === 0) {
                    $data['payment'] = trim(str_replace("Метод оплаты:", "", $line));
                } elseif (strpos($line, "Рассылка:") === 0) {
                    $data['newsletter'] = trim(str_replace("Рассылка:", "", $line));
                }
            }
            
            if (!empty($data)) {
                $data['filename'] = basename($file);
                $applications[] = $data;
            }
        }
    }
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
                            <input type="checkbox" name="selected_apps[]" value="<?php echo htmlspecialchars($app['filename']); ?>" class="app-checkbox">
                        </td>
                        <td><?php echo htmlspecialchars($app['datetime']); ?></td>
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