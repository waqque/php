<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
$name = trim($_POST['name'] ?? '');
$surname = trim($_POST['surname'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$topic = trim($_POST['topic'] ?? '');
$payment = trim($_POST['payment'] ?? '');
$newsletter = isset($_POST['newsletter']) ? 'Да' : 'Нет';
function redirectBack($errorCode, $data) {
    $params = array(
        'error' => $errorCode,
        'name' => $data['name'],
        'surname' => $data['surname'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'topic' => $data['topic'],
        'payment' => $data['payment'],
        'newsletter' => isset($data['newsletter_original']) ? 'yes' : 'no'
    );
    header('Location: index.php?' . http_build_query($params));
    exit;
}
if (empty($name) || empty($surname) || empty($email) || empty($phone) || empty($topic) || empty($payment)) {
    redirectBack(1, array(
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'phone' => $phone,
        'topic' => $topic,
        'payment' => $payment,
        'newsletter_original' => isset($_POST['newsletter'])
    ));
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectBack(2, array(
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'phone' => $phone,
        'topic' => $topic,
        'payment' => $payment,
        'newsletter_original' => isset($_POST['newsletter'])
    ));
}

$directory = 'applications';
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

$filename = $directory . '/app_' . date('Y-m-d_H-i-s') . '_' . rand(1000, 9999) . '.txt';

$content = "========================================\n";
$content .= "ЗАЯВКА НА КОНФЕРЕНЦИЮ\n";
$content .= "========================================\n";
$content .= "Дата и время: " . date('Y-m-d H:i:s') . "\n";
$content .= "----------------------------------------\n";
$content .= "Имя: " . $name . "\n";
$content .= "Фамилия: " . $surname . "\n";
$content .= "Email: " . $email . "\n";
$content .= "Телефон: " . $phone . "\n";
$content .= "Тематика: " . $topic . "\n";
$content .= "Метод оплаты: " . $payment . "\n";
$content .= "Рассылка: " . $newsletter . "\n";
$content .= "========================================\n";

if (file_put_contents($filename, $content)) {
    header('Location: index.php?success=1');
    exit;
} else {
    redirectBack(3, array(
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'phone' => $phone,
        'topic' => $topic,
        'payment' => $payment,
        'newsletter_original' => isset($_POST['newsletter'])
    ));
}
?>