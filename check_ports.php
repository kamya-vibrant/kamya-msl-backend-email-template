<?php
function checkPort($host, $port) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 0.5);
    if (is_resource($connection)) {
        fclose($connection);
        return false; // Port is in use
    }
    return true; // Port is free
}

$startPort = 8000;
$endPort = 8100;
$host = '170.64.128.141';

for ($port = $startPort; $port <= $endPort; $port++) {
    if (checkPort($host, $port)) {
        echo "Port $port is free\n";
    } else {
        echo "Port $port is in use\n";
    }
}
?>