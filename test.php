<?php
/* 
Template Name: IP-testi
*/

header("Content-Type: text/plain; charset=utf-8");

echo "=== Palvelimen näkemä IP-osoite ===\n";
echo "REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? 'Ei saatavilla') . "\n\n";

echo "=== Muut headerit ===\n";
$keys = [
'HTTP_X_FORWARDED_FOR',
'HTTP_X_REAL_IP',
'HTTP_CLIENT_IP',
'HTTP_CF_CONNECTING_IP',
'HTTP_X_CLUSTER_CLIENT_IP',
'HTTP_FORWARDED_FOR',
'HTTP_FORWARDED'
];
foreach ($keys as $k) {
    echo "$k: " . ($_SERVER[$k] ?? 'Ei asetettu') . "\n";
}

exit;

