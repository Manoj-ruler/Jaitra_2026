<?php
$passwords = [
    'jaitra@admin2k26',
    'kabaddi@admin1207',
    'pickleball@admin5898',
    'volleyball@admin8639',
    'badminton@admin8247'
];

$output = "";
foreach ($passwords as $p) {
    $output .= password_hash($p, PASSWORD_DEFAULT) . "\n";
}
file_put_contents('hashes.txt', $output);
?>
