<?php

function validateRequired($fields)
{
    foreach ($fields as $field) {
        if (empty(trim($field))) {
            return false;
        }
    }
    return true;
}
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function redirectWithError($errorCode, $postData)
{
    $params = [
        'error' => $errorCode,
        'name' => $postData['name'] ?? '',
        'surname' => $postData['surname'] ?? '',
        'email' => $postData['email'] ?? '',
        'phone' => $postData['phone'] ?? '',
        'topic' => $postData['topic'] ?? '',
        'payment' => $postData['payment'] ?? '',
        'newsletter' => isset($postData['newsletter']) ? 'yes' : 'no'
    ];
    
    $query = http_build_query($params);
    header('Location: index.php?' . $query);
    exit;
}


function generateUniqueFilename()
{
    return 'applications/app_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.txt';
}


function saveApplication($data)
{
    $directory = 'applications';
    
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0777, true)) {
            return false;
        }
    }
    
    $filename = generateUniqueFilename();
    
    $content = "========================================\n";
    $content .= "ЗАЯВКА НА КОНФЕРЕНЦИЮ\n";
    $content .= "========================================\n";
    $content .= "Дата и время: " . date('Y-m-d H:i:s') . "\n";
    $content .= "----------------------------------------\n";
    $content .= "Имя: " . $data['name'] . "\n";
    $content .= "Фамилия: " . $data['surname'] . "\n";
    $content .= "Email: " . $data['email'] . "\n";
    $content .= "Телефон: " . $data['phone'] . "\n";
    $content .= "Тематика: " . $data['topic'] . "\n";
    $content .= "Метод оплаты: " . $data['payment'] . "\n";
    $content .= "Рассылка: " . $data['newsletter'] . "\n";
    $content .= "========================================\n\n";
    
    return file_put_contents($filename, $content) !== false;
}

function getAllApplications()
{
    $applications = [];
    $directory = 'applications';
    
    if (!is_dir($directory)) {
        return $applications;
    }
    
    $files = glob($directory . '/*.txt');
    
    if (!$files) {
        return $applications;
    }
    
    rsort($files);
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $data = parseApplicationFile($content);
        
        if (!empty($data)) {
            $data['filename'] = basename($file);
            $applications[] = $data;
        }
    }
    
    return $applications;
}

function parseApplicationFile($content)
{
    $data = [];
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
    
    return $data;
}
function deleteApplications($filenames)
{
    $deletedCount = 0;
    
    if (empty($filenames)) {
        return $deletedCount;
    }
    
    foreach ($filenames as $filename) {
        $filePath = 'applications/' . basename($filename);
        if (is_file($filePath)) {
            if (unlink($filePath)) {
                $deletedCount++;
            }
        }
    }
    
    return $deletedCount;
}