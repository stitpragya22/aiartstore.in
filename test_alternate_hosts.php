<?php
$hosts = ['mysql.stackcp.com', 'mysql.gb.stackcp.com'];
foreach ($hosts as $host) {
    $ip = @gethostbyname($host);
    echo "Host $host resolved to $ip\n";
    if ($ip !== $host) {
        foreach ([3306, 43628] as $port) {
            $fp = @fsockopen($ip, $port, $errno, $errstr, 2);
            if ($fp) {
                echo "Port $port is open on $host ($ip)!\n";
                fclose($fp);
            } else {
                echo "Port $port is closed on $host ($ip)\n";
            }
        }
    }
}
