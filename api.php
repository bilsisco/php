<?php
// api.php

// Tüm HTTP metodlarını ve formatlarını destekle
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-Type: text/plain'); // Yanıtı basit tut
header("Content-Type: application/json; charset=UTF-8");

// Gelen ham veriyi al (JSON/text/XML ne olursa olsun)
$raw_data = file_get_contents('php://input');

// İstemci IP adresini al (Proxy kullanılıyorsa gerçek IP'yi bul)
$client_ip = $_SERVER['HTTP_CLIENT_IP'] ?? 
             $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
             $_SERVER['REMOTE_ADDR'];

// Ek bilgiler (isteğe bağlı)
$method = $_SERVER['REQUEST_METHOD'];
$timestamp = date('Y-m-d H:i:s');

// Log formatı
$log_entry = "[$timestamp] [$client_ip] [$method]\n" . 
             "RAW DATA:\n" . 
             $raw_data . 
             "\n" . str_repeat("-", 50) . "\n";

// Dosyaya yaz
try {
    file_put_contents('veri.txt', $log_entry, FILE_APPEND);
    echo "Veri kaydedildi!";
} catch (Exception $e) {
    http_response_code(500);
    echo "Hata: " . $e->getMessage();
}