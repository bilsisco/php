<?php
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo in_array('mod_security', $modules) || in_array('mod_security2', $modules)
        ? 'ModSecurity Aktif' : 'ModSecurity Pasif';
} else {
    echo 'Apache modülleri alınamadı.';
}
?>