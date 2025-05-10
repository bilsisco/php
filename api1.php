<?php
header('Content-Type: application/json');

// Config
$SECRET_KEY = 'sizin_gizli_anahtar_123';
$ALLOWED_IPS = ['127.0.0.1', '176.45.154.12','31.223.31.203','52.89.214.238','34.212.75.30','54.218.53.128','52.32.178.7'];

// Hata yönetimi
error_reporting(E_ALL);
ini_set('display_errors', 1); // Hataları ekranda göster

function log_alert($message) {
    file_put_contents('alerts.log', date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // JSON decode hatası kontrolü
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Geçersiz JSON verisi!');
    }

    // Secret key kontrolü
    if (!isset($data['secret'])) {
        throw new Exception('Secret key eksik');
    }
    if ($data['secret'] !== $SECRET_KEY) {
        throw new Exception('Geçersiz secret key');
    }

    // Gerekli alanlar
    $required_fields = ['symbol', 'price', 'message'];
    foreach($required_fields as $field) {
        if(!isset($data[$field])) {
            throw new Exception("Eksik alan: $field");
        }
    }
    
    process_alert($data);
    echo json_encode(['status' => 'success']);
    
} catch(Exception $e) {
    log_alert('Hata: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function process_alert($data) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=bistsiny_alerts', 'bistsiny_alert', 'PbkFFHrf88[A');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // PDO hatalarını göster
        
        $stmt = $pdo->prepare("INSERT INTO alerts (symbol, price, message) VALUES (?, ?, ?)");
        $stmt->execute([$data['symbol'], $data['price'], $data['message']]);
        
        log_alert("İşlenen alert: " . json_encode($data));
        
    } catch(PDOException $e) {
        throw new Exception("Veritabanı hatası: " . $e->getMessage());
    }
}