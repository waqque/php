<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявка на участие в конференции</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 200px; vertical-align: top; }
        input, select { width: 250px; padding: 5px; }
        .error { color: red; margin-bottom: 15px; padding: 10px; background: #ffeeee; border-left: 3px solid red; }
        .success { color: green; margin-bottom: 15px; padding: 10px; background: #eeffee; border-left: 3px solid green; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .required { color: red; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
    <h1>Заявка на участие в конференции</h1>

    <?php
    $old_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $old_surname = isset($_GET['surname']) ? htmlspecialchars($_GET['surname']) : '';
    $old_email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
    $old_phone = isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : '';
    $old_topic = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : '';
    $old_payment = isset($_GET['payment']) ? htmlspecialchars($_GET['payment']) : '';
    $old_newsletter = isset($_GET['newsletter']) && $_GET['newsletter'] == 'yes' ? 'checked' : '';
    ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success">Ваша заявка успешно принята! Спасибо за участие.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="error">Ошибка: Все поля обязательны для заполнения!</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 2): ?>
        <div class="error">Ошибка: Некорректный email адрес!</div>
    <?php endif; ?>

    <form method="POST" action="submit.php">
        <div class="form-group">
            <label>Имя: <span class="required">*</span></label>
            <input type="text" name="name" value="<?php echo $old_name; ?>" required>
        </div>

        <div class="form-group">
            <label>Фамилия: <span class="required">*</span></label>
            <input type="text" name="surname" value="<?php echo $old_surname; ?>" required>
        </div>

        <div class="form-group">
            <label>Email: <span class="required">*</span></label>
            <input type="email" name="email" value="<?php echo $old_email; ?>" required>
        </div>

        <div class="form-group">
            <label>Телефон: <span class="required">*</span></label>
            <input type="tel" name="phone" value="<?php echo $old_phone; ?>" required>
        </div>

        <div class="form-group">
            <label>Тематика: <span class="required">*</span></label>
            <select name="topic" required>
                <option value="">-- Выберите --</option>
                <option value="Бизнес" <?php echo $old_topic == 'Бизнес' ? 'selected' : ''; ?>>Бизнес</option>
                <option value="Технологии" <?php echo $old_topic == 'Технологии' ? 'selected' : ''; ?>>Технологии</option>
                <option value="Реклама и Маркетинг" <?php echo $old_topic == 'Реклама и Маркетинг' ? 'selected' : ''; ?>>Реклама и Маркетинг</option>
            </select>
        </div>

        <div class="form-group">
            <label>Метод оплаты: <span class="required">*</span></label>
            <select name="payment" required>
                <option value="">-- Выберите --</option>
                <option value="WebMoney" <?php echo $old_payment == 'WebMoney' ? 'selected' : ''; ?>>WebMoney</option>
                <option value="Яндекс.Деньги" <?php echo $old_payment == 'Яндекс.Деньги' ? 'selected' : ''; ?>>Яндекс.Деньги</option>
                <option value="PayPal" <?php echo $old_payment == 'PayPal' ? 'selected' : ''; ?>>PayPal</option>
                <option value="кредитная карта" <?php echo $old_payment == 'кредитная карта' ? 'selected' : ''; ?>>кредитная карта</option>
            </select>
        </div>

        <div class="form-group">
            <label>Получать рассылку:</label>
            <input type="checkbox" name="newsletter" value="yes" <?php echo $old_newsletter; ?>> Да
        </div>

        <div class="form-group">
            <label></label>
            <button type="submit">Отправить заявку</button>
        </div>
    </form>

    <hr>
    <p><a href="admin.php">Панель администратора (просмотр заявок)</a></p>
</body>
</html>