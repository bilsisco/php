<?php
// Mail hesap bilgileri
$mailhost = '{cp64.servername.co:993/imap/ssl}INBOX';
$mailuser = 'axxxxx'; // Mail kullanıcı adı
$mailpass = 'xxxxx'; // Bu alanı siz doldurmalısınız

// Bağlantı kur
$inbox = @imap_open($mailhost, $mailuser, $mailpass);

if (!$inbox) {
    die('Mail kutusuna bağlanılamadı: ' . imap_last_error());
}

// Son 10 mail için mesaj numaralarını al
$emails = imap_search($inbox, 'ALL');

echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <title>Gelen Mailler</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .email { background: #fff; border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        h3 { color: #333; }
    </style>
</head>
<body>";

echo "<h2>📬 Gelen Mailler (Son 10 Mesaj)</h2>";
echo "<div>";

if ($emails) {
    rsort($emails); // En yeni mailler önce
    $count = 0;

    foreach ($emails as $email_number) {
        if (++$count > 10) break;

        // Mail başlık bilgileri
        $overview = imap_fetch_overview($inbox, $email_number, 0)[0];

        // Mail içeriği (text/plain kısmı)
        $message = imap_fetchbody($inbox, $email_number, 1);

        echo "<div class='email'>";
        echo "<strong>Konu:</strong> " . htmlspecialchars(imap_utf8($overview->subject)) . "<br>";
        echo "<strong>Gönderen:</strong> " . htmlspecialchars(imap_utf8($overview->from)) . "<br>";
        echo "<strong>Tarih:</strong> " . htmlspecialchars(imap_utf8($overview->date)) . "<br><br>";
        echo "<p><strong>Mesaj:</strong><br>" . nl2br(htmlspecialchars(trim(imap_utf8($message)))) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Hiç mail bulunamadı.</p>";
}

echo "</div></body></html>";

// Bağlantıyı kapat
imap_close($inbox);
?>
