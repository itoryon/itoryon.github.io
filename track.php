<?php
// Создаем/открываем базу данных
$db = new SQLite3('domains.db');

// Создаем таблицу если не существует
$db->exec("CREATE TABLE IF NOT EXISTS visitor_domains (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    domain TEXT NOT NULL,
    referrer_url TEXT,
    user_agent TEXT,
    screen_res TEXT,
    language TEXT,
    ip_address TEXT,
    visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Получаем данные
$data = json_decode(file_get_contents('php://input'), true) ?: $_GET;

if ($data) {
    $stmt = $db->prepare("INSERT INTO visitor_domains 
        (domain, referrer_url, user_agent, screen_res, language, ip_address) 
        VALUES (:domain, :ref, :ua, :screen, :lang, :ip)");
    
    $stmt->bindValue(':domain', $data['r'] ?? 'direct');
    $stmt->bindValue(':ref', $data['u'] ?? '');
    $stmt->bindValue(':ua', $data['a'] ?? '');
    $stmt->bindValue(':screen', $data['s'] ?? '');
    $stmt->bindValue(':lang', $data['l'] ?? '');
    $stmt->bindValue(':ip', $_SERVER['REMOTE_ADDR'] ?? '');
    
    $stmt->execute();
}

// Для пикселя возвращаем 1x1 изображение
header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
?>