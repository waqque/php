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

$date = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'] ?? getenv('REMOTE_ADDR') ?? 'Unknown';
$delimiter = '||';
$separator = '|';

$fields = [$name, $surname, $email, $phone, $topic, $payment, $newsletter];
foreach ($fields as &$field) {
    $field = str_replace($delimiter, $separator, $field);
    $field = str_replace(["\n", "\r"], ' ', $field);
}
unset($field);

$dataFile = 'applications.dat';
$record = $date . $delimiter . $ip . $delimiter . $name . $delimiter . $surname . $delimiter . $email . $delimiter . $phone . $delimiter . $topic . $delimiter . $payment . $delimiter . $newsletter . PHP_EOL;

if (!file_exists($dataFile)) {
    $header = "дата" . $delimiter . "ip" . $delimiter . "имя" . $delimiter . "фамилия" . $delimiter . "email" . $delimiter . "телефон" . $delimiter . "тематика" . $delimiter . "оплата" . $delimiter . "рассылка" . PHP_EOL;
    file_put_contents($dataFile, $header, LOCK_EX);
}

if (file_put_contents($dataFile, $record, FILE_APPEND | LOCK_EX) !== false) {
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