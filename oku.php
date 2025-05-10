<?php
// Aynı dizindeki veri.txt dosyasını oku
$dosyaYolu = 'veri.txt';

if (file_exists($dosyaYolu)) {
    // Dosyanın içeriğini oku
    $icerik = file_get_contents($dosyaYolu);

    // HTML çıktı olarak göster
    echo "<h2>Dosya İçeriği:</h2>";
    echo "<pre>" . htmlspecialchars($icerik) . "</pre>";
} else {
    echo "<p>veri.txt dosyası bulunamadı.</p>";
}
?>